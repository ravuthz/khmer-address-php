<?php

namespace Ravuthz\KhmerAddress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ravuthz\KhmerAddress\Traits\HasGeometry;
use Ravuthz\KhmerAddress\Traits\HasBaseModel;

class Commune extends Model
{
    use HasFactory;
    use HasGeometry, HasBaseModel;

    protected $table = 'communes';
    protected $primaryKey = 'objectid';
    protected $guarded = ['objectid'];
    protected $hidden = ['shape'];
    protected $casts = ['geometry' => 'json'];
}
