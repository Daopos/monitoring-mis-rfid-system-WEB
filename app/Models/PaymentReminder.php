<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use HasFactory;
    protected $fillable = [
        'home_owner_id', 'title', 'amount', 'due_date', 'status'
    ];
    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class);
    }
}
