<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $fillable = [
        'homeowner_id',
        'position',
    ];

    public function homeowner()
    {
        return $this->belongsTo(HomeOwner::class);
    }
}