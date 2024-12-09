<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GateMonitor extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'in','out'];

    public function owner()
    {
        return $this->belongsTo(HomeOwner::class);
    }

    // public function homeOwner()
    // {
    //     return $this->belongsTo(HomeOwner::class, 'owner_id');
    // }
}