<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id','requested_at','service_date',
        'pickup_location','dropoff_location','status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }
}
