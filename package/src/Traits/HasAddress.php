<?php

namespace Ravuthz\KhmerAddress\Traits;

use Ravuthz\KhmerAddress\Models\Province;
use Ravuthz\KhmerAddress\Models\District;
use Ravuthz\KhmerAddress\Models\Commune;
use Ravuthz\KhmerAddress\Models\Village;
use Ravuthz\KhmerAddress\Models\Road;

trait HasAddress
{

    public function province()
    {
        return $this->belongsTo(Province::class, 'province', 'code');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district', 'code');
    }
    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune', 'code');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village', 'code');
    }

    public function street()
    {
        return $this->belongsTo(Road::class, 'road_objectid', 'objectid')
            ->select('objectid', 'name', 'fee');
    }

    public function scopeAddAddressText($scope)
    {
        $scope->addSelect([
            'street_text' => Road::select('name')->whereRaw('objectid = ' . static::getTable() . '."road_objectid"::numeric'),
            'address_text' => Village::select('gezeeteer_name')->whereRaw('code = ' . static::getTable() . '."village"'),
        ]);
    }

    public function scopeQueryWithFullAddress($scope)
    {
        return $scope->with([
            'street' => function ($street) {
                $street->selectOutFields('street')
                    ->select('objectid', 'name')->get();
            },
            'province' => function ($province) {
                $province->selectOutFields('province')
                    ->select('code', 'name')->get();
            },
            'district' => function ($district) {
                $district->selectOutFields('district')
                    ->select('code', 'name')->get();
            },
            'commune' => function ($commune) {
                $commune->selectOutFields('commune')
                    ->select('code', 'name')->get();
            },
            'village' => function ($village) {
                $village->selectOutFields('village')
                    ->select('code', 'name', 'gezeeteer_name')->get();
            },
        ]);
    }
}
