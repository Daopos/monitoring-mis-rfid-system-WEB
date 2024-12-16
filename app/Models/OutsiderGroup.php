<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutsiderGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'outsider_id',
        'name',
        'type_id',
        'valid_id',
        'profile_img',
    ];
}
