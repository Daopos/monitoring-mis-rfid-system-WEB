<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighbor extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'homeowner_id',
        'status',
    ];
    public function applicant()
{
    return $this->belongsTo(Applicant::class);
}


public function homeowner()
{
    return $this->belongsTo(HomeOwner::class, 'homeowner_id');
}
}