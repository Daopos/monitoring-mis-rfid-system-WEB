<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;

    public function receivedMessages()
    {
        return $this->hasMany(Message::class)
                    ->where('recipient_role', 'admin');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class)
                    ->where('sender_role', 'admin');
    }
}
