<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'tb_jurnal_umum';

    protected $fillable = [
        'transaksi',
        'tanggal',
        'keterangan',
        'akun_debet',
        'akun_kredit',
        'jumlah',
        'is_deleted',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
}
