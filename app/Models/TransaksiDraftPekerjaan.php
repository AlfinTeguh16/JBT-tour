<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDraftPekerjaan extends Model
{
    protected $table ='tb_tansaksi_draft_pekerjaan';

    protected $fillable =[
        'draft_pekerjaan_id',
        'nama_pekerjaan',
        'instansi',
        'nilai_pekerjaan',
        'nilai_dpp',
        'nilai_ppn',
        'nilai_pph_final',
        'nilai_bersih_pekerjaan'
    ];

    public function draft()
    {
        return $this->belongsTo(DraftPekerjaan::class, 'draft_pekerjaan_id');
    }

}
