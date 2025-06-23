<?php

namespace App\Exports;

use App\Models\LaporanKeuangan;
use App\Models\DraftPekerjaan;
use App\Models\TransaksiDraftPekerjaan;
use App\Models\ArusKas;
use App\Models\LabaRugi;
use App\Models\PerubahanModal;
use App\Models\Neraca;
use App\Models\JurnalUmum;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanKeuanganExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $laporan = LaporanKeuangan::findOrFail($this->id);

        $bulan = $laporan->created_at->format('m');
        $tahun = $laporan->created_at->format('Y');

        $draftPekerjaan = DraftPekerjaan::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->get();
        $transaksiDraft = TransaksiDraftPekerjaan::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->get();
        $arusKas = ArusKas::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $labaRugi = LabaRugi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $perubahanModal = PerubahanModal::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $neraca = Neraca::whereMonth('bulan', $bulan)->whereYear('bulan', $tahun)->get();
        $jurnalUmum = JurnalUmum::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('is_deleted', false)->get();

        return view('activities.laporan-keuangan.export-excel', compact(
            'laporan',
            'draftPekerjaan',
            'transaksiDraft',
            'arusKas',
            'labaRugi',
            'perubahanModal',
            'neraca',
            'jurnalUmum'
        ));
    }
}


