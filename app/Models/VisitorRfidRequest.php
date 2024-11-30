<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorRfidRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'visitor_id',
        'rfid',
        'requested_at',
        'expiry_date',
        'status', // Add status here
    ];
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}