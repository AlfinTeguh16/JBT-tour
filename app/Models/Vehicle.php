<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['plate_no','brand','model','capacity','status'];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
