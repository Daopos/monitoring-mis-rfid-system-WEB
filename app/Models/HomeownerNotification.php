<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeownerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_owner_id',
        'title',
        'message',
        'is_read',
    ];

     // Optional: Define the relationship to the HomeOwner model
     public function homeowner()
     {
         return $this->belongsTo(HomeOwner::class);
     }
}