<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'service_name',
        'description',
        'base_extra_cost',
        'multiplier',
        'max_distance',
        'max_weight',
        'estimated_time',
        'is_active'
    ];
}
