<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'homeowner_id',
        'mobilization_date',
        'application_date',
        'completion_date',
        'project_description',
        'selection',
        'status',

    ];

    public function homeowner()
    {
        return $this->belongsTo(HomeOwner::class, 'homeowner_id');
    }
    public function neighbors()
{
    return $this->hasMany(Neighbor::class);
}
}