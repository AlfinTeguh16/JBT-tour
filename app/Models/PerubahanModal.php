<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerubahanModal extends Model
{
    protected $table = 'tb_perubahan_modal';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'jenis',
        'jumlah',
        'is_deleted',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
}
