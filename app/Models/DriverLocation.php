<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    protected $fillable = ['user_id','work_session_id','latitude','longitude','recorded_at'];
}
