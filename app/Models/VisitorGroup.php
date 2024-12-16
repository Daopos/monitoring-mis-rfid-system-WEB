<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'name',
        'type_id',
        'profile_img',
        'valid_id',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
