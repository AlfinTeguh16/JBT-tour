<?php

namespace App\Http\Controllers;

use App\Models\JurnalUmum;
use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Rules\UniqueWithoutDeleted;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index()
    {
        $jurnal = JurnalUmum::where('is_deleted', 0)
                    ->orderByDesc('tanggal')
                    ->paginate(10);

        return view('activities.jurnal-umum.index', compact('jurnal'));
    }

    public function create()
    {
        return view('activities.jurnal-umum.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'jumlah' => str_replace('.', '', $request->jumlah)
        ]);

        $validated = $request->validate([
            'transaksi'  => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'keterangan'  => 'nullable|string|max:255',
            'akun_debet'  => 'nullable|string|max:100',
            'akun_kredit' => 'nullable|string|max:100',
            'jumlah'      => 'required|numeric|min:0',
        ]);

        // Cek apakah data dengan kombinasi ini sudah ada dan belum dihapus
        $exists = JurnalUmum::where('is_deleted', 0)
            ->where('transaksi', $validated['transaksi'])
            ->where('tanggal', $validated['tanggal'])
            ->where('akun_debet', $validated['akun_debet'])
            ->where('akun_kredit', $validated['akun_kredit'])
            ->where('jumlah', $validated['jumlah'])
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors(['duplicate' => 'Data jurnal dengan kombinasi ini sudah ada dan masih aktif.']);
        }

        DB::beginTransaction();
        try {
            // Simpan jurnal
            $jurnal = JurnalUmum::create($validated);

            // Buat entri laporan keuangan terkait (nama bisa disesuaikan dengan logika Anda)
            LaporanKeuangan::create([
                'laporan_keuangan' => 'Jurnal tanggal ' . $validated['tanggal'],
                'status_laporan'   => 'belum tervalidasi',
                'is_deleted'       => false,
            ]);

            DB::commit();

            return redirect()->route('jurnal-umum.index')->with('success', 'Data jurnal umum berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan jurnal umum: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }



    public function edit($id)
    {
        $jurnal = JurnalUmum::findOrFail($id);
        return view('activities.jurnal-umum.edit', compact('jurnal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'transaksi'  => 'required|string|max:255',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'akun_debet' => 'nullable|string|max:100',
            'akun_kredit' => 'nullable|string|max:100',
            'jumlah' => 'required|numeric|min:0',
        ]);

        try {
            $jurnal = JurnalUmum::findOrFail($id);
            $cleanJumlah = str_replace('.', '', $request->jumlah);
            $jurnal->update([
                'transaksi'  => $request->transaksi,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'akun_debet' => $request->akun_debet,
                'akun_kredit' => $request->akun_kredit,
                'jumlah' => $cleanJumlah,
            ]);

            return redirect()->route('jurnal-umum.index')->with('success', 'Data jurnal umum berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate jurnal umum: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $jurnal = JurnalUmum::findOrFail($id);
            $jurnal->update(['is_deleted' => 1]);

            return redirect()->route('jurnal-umum.index')->with('success', 'Data jurnal umum berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus jurnal umum: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.');
        }
    }

    public function show($id)
    {
        $jurnal = JurnalUmum::findOrFail($id);
        return view('activities.jurnal-umum.show', compact('jurnal'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $jurnal = JurnalUmum::where('is_deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('keterangan', 'LIKE', "%{$keyword}%")
                    ->orWhere('akun_debet', 'LIKE', "%{$keyword}%")
                    ->orWhere('akun_kredit', 'LIKE', "%{$keyword}%")
                    ->orWhere('transaksi', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($jurnal);
    }
}
