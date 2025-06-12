<?php

namespace App\Http\Controllers;

use App\Models\LabaRugi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LabaRugiController extends Controller
{
    public function index()
    {
        $labaRugi = LabaRugi::where('is_deleted', 0)
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('activities.laba-rugi.index', compact('labaRugi'));
    }

    public function create()
    {
        return view('activities.laba-rugi.create');
    }

    public function store(Request $request)
    {
        // Hapus pemisah ribuan jika ada
        $request->merge(['jumlah' => str_replace('.', '', $request->jumlah)]);

        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'jenis'       => 'required|in:pendapatan,beban',
            'keterangan'  => 'nullable|string|max:255',
            'jumlah'      => 'required|numeric|min:0',
        ]);

        // Cek apakah data dengan kombinasi ini sudah ada dan masih aktif
        $exists = LabaRugi::where('is_deleted', 0)
            ->where('tanggal', $validated['tanggal'])
            ->where('jenis', $validated['jenis'])
            ->where('jumlah', $validated['jumlah'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'duplicate' => 'Data dengan tanggal, jenis, dan jumlah yang sama sudah ada.'
            ]);
        }

        try {
            LabaRugi::create($validated);
            return redirect()->route('laba-rugi.index')->with('success', 'Data Laba Rugi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan Laba Rugi: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }

    public function edit($id)
    {
        $labaRugi = LabaRugi::findOrFail($id);
        return view('activities.laba-rugi.edit', compact('labaRugi'));
    }

    public function update(Request $request, $id)
    {
        $request->merge(['jumlah' => str_replace('.', '', $request->jumlah)]);

        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pendapatan,beban',
            'keterangan' => 'nullable|string|max:255',
            'jumlah' => 'required|numeric|min:0',
        ]);

        try {
            $labaRugi = LabaRugi::findOrFail($id);
            $labaRugi->update($request->all());
            return redirect()->route('laba-rugi.index')->with('success', 'Data Laba Rugi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update Laba Rugi: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $labaRugi = LabaRugi::findOrFail($id);
            $labaRugi->update(['is_deleted' => 1]);

            return redirect()->route('laba-rugi.index')->with('success', 'Data berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus Laba Rugi: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function show($id)
    {
        $labaRugi = LabaRugi::findOrFail($id);
        return view('activities.laba-rugi.show', compact('labaRugi'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $labaRugi = LabaRugi::where('is_deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('keterangan', 'LIKE', "%{$keyword}%")
                    ->orWhere('jenis', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($labaRugi);
    }
}
