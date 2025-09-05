<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','assignment_id','title','body','is_read','status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }

    public function order() {
        return $this->hasOneThrough(Order::class, Assignment::class, 'id', 'id', 'assignment_id', 'order_id');
    }
}
