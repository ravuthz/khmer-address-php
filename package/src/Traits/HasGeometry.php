<?php

namespace Ravuthz\KhmerAddress\Traits;

use Illuminate\Support\Facades\DB;

trait HasGeometry
{
    public function scopeAddGeometry($scope, $force = false)
    {
        if ($force || request()->returnGeometry == 'true') {
            // return $scope->selectRaw('ST_AsGeoJSON(ST_Force2D(sde.st_astext(shape)::text)) AS geometry');
            // return $scope->selectRaw('ST_AsGeoJSON(ST_FlipCoordinates(ST_Force2D(sde.st_astext(shape)::text))) AS geometry');
            $scope->addSelect([
                'geometry' => DB::raw('ST_AsGeoJSON(ST_FlipCoordinates(ST_Force2D(sde.st_astext(shape)::text))) AS geometry')
            ]);
        }
        return $scope;
    }

    public function scopeSelectOutFields($scope, $prefix = null)
    {
        $name = ($prefix ? $prefix . '_' : '') . 'outFields';
        $outFields = request()->get($name) ? explode(',', request()->get($name)) : ['*'];
        return $scope->select($outFields);
    }

    public function scopeWhereDistanceLatLng($scope, $dis = 100, $lat = null, $lng = null)
    {
        if (!$lat && !$lng) {
            $req = request();
            $lat = $req->get('lat', 0);
            $lng = $req->get('lng', 0);
            $dis = $req->get('dis', $dis);
        }

        // http://localhost:8000/api/lands?lat=10.610286734953391&lng=103.52476760684878&dis=200
        $scope->whereRaw("
            ST_DWithin(
                ST_SetSRID(st_geometryfromtext(sde.st_astext(shape)::text), 4326)::geography,
                ST_SetSRID(ST_MakePoint('$lng', '$lat'),4326),
                $dis
            )
        ");
    }
}
