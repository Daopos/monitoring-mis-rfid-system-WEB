<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outsider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'vehicle_type',
        'brand','color',
        'model',
        'plate_number',
        'rfid',
        'in',
        'out',
        'type_id',
        'valid_id',
        'profile_img',
    ];

    protected $casts = [
        'in' => 'datetime',
        'out' => 'datetime',
    ];
}