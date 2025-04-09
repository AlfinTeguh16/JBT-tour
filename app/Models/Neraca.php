<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neraca extends Model
{
    use HasFactory;

    protected $table = 'tb_neraca';

    protected $fillable = [
        'bulan',
        'data_karyawan',
        'draft_pekerjaan',
        'transaksi_draft_pekerjaan',
        'status_transaksi',
        'status_draft_pekerjaan',
        'biaya_spidi',
        'biaya_listrik',
        'biaya_air_minum',
        'gaji_karyawan',
        'modal_perusahaan',
        'biaya_telepon',
        'is_deleted',
    ];
}
