<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_owner_id',
        'name',
        'brand',
        'color',
        'model',
        'plate_number',
        'rfid',
        'relationship',
        'date_visit',
        'number_vistiors',
        'status',
        'guard',
        'reason',
        'type_id',
        'valid_id',
        'profile_img',
        'car_type'

    ];

    public function rfidRequest()
{
    return $this->hasOne(VisitorRfidRequest::class);
}
public function homeowner()
{
    return $this->belongsTo(HomeOwner::class, 'home_owner_id'); // Adjust as per your column name
}

public function owner()
{
    return $this->belongsTo(Visitor::class, 'visitor_id'); // Assuming 'visitor_id' is the foreign key.
}

public function visitorGroups()
{
    return $this->hasMany(VisitorGroup::class);
}

}