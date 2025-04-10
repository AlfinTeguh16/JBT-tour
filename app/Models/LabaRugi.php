<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabaRugi extends Model
{
    use HasFactory;

    protected $table = 'tb_laba_rugi';

    protected $fillable = [
        'tanggal',
        'jenis',
        'keterangan',
        'jumlah',
        'is_deleted'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
}
