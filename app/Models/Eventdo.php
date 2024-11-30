<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventdo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start',
        'end',
        'status'

    ];
}