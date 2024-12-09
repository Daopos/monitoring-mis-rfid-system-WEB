<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_owner_id',
        'name',
        'relationship',
        'age',
        'gender',
        'rfid'
    ];
    public function householdGateMonitors()
    {
        return $this->hasMany(HouseholdGateMonitor::class);
    }

    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id'); // Foreign key 'home_owner_id'
    }
}
