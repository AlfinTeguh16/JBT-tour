<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArusKas extends Model
{
    use HasFactory;

    protected $table = 'tb_arus_kas';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'jenis',
        'kategori',
        'jumlah',
        'is_deleted'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
}
