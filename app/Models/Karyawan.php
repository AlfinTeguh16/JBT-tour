<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table ='tb_karyawan';

    protected $fillable = [
        'id_karyawan',
        'nama',
        'no_telepon',
        'email',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'alamat',
        'jabatan',
        'is_deleted',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];


}
