<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftPekerjaan extends Model
{
    protected $table = 'tb_draft_pekerjaan';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'code_draft',
        'nama_pekerjaan',
        'dokumen_pengawasan',
        'dokumen_perencanaan',
        'laporan_teknis',
        'termin',
        'pajak',
        'status_pekerjaan',
        'instansi',
        'no_instansi',
        'email_instansi',
        'tanggal_pengawasan',
        'dokumen_penawaran',
        'alamat_proyek',
        'is_deleted',
    ];

    // Tipe data kolom yang perlu dikonversi
    protected $casts = [
        'is_deleted' => 'boolean',
        'tanggal_pengawasan' => 'date',
    ];

    /**
     * Scope untuk hanya mengambil data yang belum dihapus (soft delete manual)
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
}
