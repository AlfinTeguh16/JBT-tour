<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    protected $table = 'tb_laporan_keuangan';

    protected $fillable = ['file_laporan_keuangan', 'status_laporan', 'is_deleted'];

}
