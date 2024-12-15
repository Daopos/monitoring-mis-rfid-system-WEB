<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'home_owner_id',
        'type',
        'sender_role',
        'recipient_role',
        'guard_name'
    ];

    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id');
    }


}