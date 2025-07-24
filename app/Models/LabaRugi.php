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
        'harga_pokok_jasa',
        'laba_kotor',
        'biaya_gaji',
        'beban_meeting',
        'beban_lain_lain',
        'jumlah_beban_operasi',
        'laba_bersih_operasional',
        'laba_bersih',
        'jumlah',
        'is_deleted'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_pokok_jasa' => 'decimal:2',
        'laba_kotor' => 'decimal:2',
        'biaya_gaji' => 'decimal:2',
        'beban_meeting' => 'decimal:2',
        'beban_lain_lain' => 'decimal:2',
        'jumlah_beban_operasi' => 'decimal:2',
        'laba_bersih_operasional' => 'decimal:2',
        'laba_bersih' => 'decimal:2',
        'jumlah' => 'decimal:2',
        'is_deleted' => 'boolean',
    ];
}

