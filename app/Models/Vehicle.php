<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_owner_id',
        'brand',
        'color',
        'model',
        'plate_number',
        'vehicle_img',
        'or_img',
        'cr_img',
    ];
}