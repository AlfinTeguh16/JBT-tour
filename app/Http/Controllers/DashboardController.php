<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\DraftPekerjaan;
use App\Models\Neraca;
use App\Models\LaporanKeuangan;
use App\Models\TransaksiDraftPekerjaan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::where('is_deleted', 0)->count();
        $totalDraftPekerjaan = DraftPekerjaan::where('is_deleted', 0)->count();
        $totalNeraca = Neraca::where('is_deleted', 0)->count();
        $totalLaporanKeuangan = LaporanKeuangan::where('is_deleted', 0)->count();
        $totalTransaksiDraft = TransaksiDraftPekerjaan::where('is_deleted', 0)->count();

        // dd($totalKaryawan);

        return view('dashboard.index', compact(
            'totalKaryawan',
            'totalDraftPekerjaan',
            'totalNeraca',
            'totalLaporanKeuangan',
            'totalTransaksiDraft'
        ));
    }

    public function direktur(){
         return $this->index();
        return view('dashboard.index');
    }
    public function akuntan(){
         return $this->index();
        return view('dashboard.index');
    }
    public function admin(){
         return $this->index();
        return view('dashboard.index');
    }
    public function pengawas(){
         return $this->index();
        return view('dashboard.index');
    }
}
