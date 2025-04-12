<?php

namespace App\Http\Controllers;

use App\Models\JurnalUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'akun_debet' => 'required|string|max:100',
            'akun_kredit' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
        ]);

        try {
            $cleanJumlah = str_replace('.', '', $request->jumlah);
            JurnalUmum::create([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'akun_debet' => $request->akun_debet,
                'akun_kredit' => $request->akun_kredit,
                'jumlah' => $cleanJumlah,
            ]);

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
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'akun_debet' => 'required|string|max:100',
            'akun_kredit' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
        ]);

        try {
            $jurnal = JurnalUmum::findOrFail($id);
            $cleanJumlah = str_replace('.', '', $request->jumlah);
            $jurnal->update([
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
                    ->orWhere('akun_kredit', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($jurnal);
    }
}
