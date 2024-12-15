<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorGateMonitor extends Model
{
    use HasFactory;

    protected $fillable = ['visitor_id', 'in','out',
    'in_img',
    'out_img',];
    public function owner()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
