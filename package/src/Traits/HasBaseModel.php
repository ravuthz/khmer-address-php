<?php

namespace Ravuthz\KhmerAddress\Traits;

trait HasBaseModel
{
    public function getTable()
    {
        return env('DB_SCHEMA', 'public') . '.' . $this->table;
    }

    public function scopeGetOrPaginate($scope)
    {
        $size = request()->get('size') ?? 1000;
        if ($size && $size > 0) {
            return $scope->paginate($size);
        }
        return $scope->get();
    }
}
