<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArusKasController extends Controller
{
    public function index()
    {
        $arusKas = ArusKas::where('is_deleted', 0)->paginate(10);
        return view('activities.arus-kas.index', compact('arusKas'));
    }

    public function create()
    {
        return view('activities.arus-kas.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'jumlah' => str_replace('.', '', $request->input('jumlah'))
        ]);

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jenis'      => 'required|in:masuk,keluar',
            'kategori'   => 'required|in:operasional,investasi,pendanaan',
            'jumlah'     => 'required|numeric|min:0',
        ]);

        // Cek apakah data yang sama sudah ada (untuk menghindari duplikasi)
        $exists = ArusKas::where('is_deleted', 0)
            ->where('tanggal', $validated['tanggal'])
            ->where('jenis', $validated['jenis'])
            ->where('jumlah', $validated['jumlah'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('failed', 'Data dengan tanggal, jenis, dan jumlah yang sama sudah ada.');
        }

        try {
            ArusKas::create($validated);
            return redirect()->route('arus-kas.index')->with('success', 'Data Arus Kas berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan Arus Kas: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }



    public function show(string $id)
    {
        $arusKas = ArusKas::findOrFail($id);
        return view('activities.arus-kas.show', compact('arusKas'));
    }

    public function edit($id)
    {
        $arusKas = ArusKas::findOrFail($id);
        return view('activities.arus-kas.edit', compact('arusKas'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'jumlah' => str_replace('.', '', $request->input('jumlah'))
        ]);

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jenis'      => 'required|in:masuk,keluar',
            'kategori'   => 'required|in:operasional,investasi,pendanaan',
            'jumlah'     => 'required|numeric|min:0',
        ]);

        try {
            $arusKas = ArusKas::findOrFail($id);

            // Cek apakah ada duplikasi di record lain
            $duplicate = ArusKas::where('id', '!=', $id)
                ->where('is_deleted', 0)
                ->where('tanggal', $validated['tanggal'])
                ->where('jenis', $validated['jenis'])
                ->where('jumlah', $validated['jumlah'])
                ->exists();

            if ($duplicate) {
                return back()->withInput()->with('failed', 'Data serupa sudah ada dalam sistem.');
            }

            $arusKas->update($validated);
            return redirect()->route('arus-kas.index')->with('success', 'Data Arus Kas berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate Arus Kas: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }



    public function destroy($id)
    {
        try {
            // Cari data berdasarkan ID
            $arusKas = ArusKas::findOrFail($id);

            // Update kolom is_deleted menjadi true (1)
            $arusKas->update(['is_deleted' => true]);

            // Pastikan route ini benar-benar ada
            return redirect()->route('arus-kas.index')->with('success', 'Data Arus Kas berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus Data LaporanKeuangan: ' . $e->getMessage());

            // Debug cepat:
            return back()->with('failed', 'Terjadi kesalahan saat menonaktifkan Data Arus Kas. Pesan: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $arusKas = ArusKas::where('is_deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('keterangan', 'LIKE', "%{$keyword}%")
                    ->orWhere('jenis', 'LIKE', "%{$keyword}%")
                    ->orWhere('kategori', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($arusKas);
    }

}
