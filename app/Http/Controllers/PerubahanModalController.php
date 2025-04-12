<?php

namespace App\Http\Controllers;

use App\Models\PerubahanModal;
use App\Enums\PerubahanModalJenisEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerubahanModalController extends Controller
{
    public function index()
    {
        $data = PerubahanModal::where('is_deleted', 0)
                ->orderByDesc('tanggal')
                ->paginate(10);

        return view('activities.perubahan-modal.index', compact('data'));
    }

    public function create()
    {
        $jenisOptions = PerubahanModalJenisEnum::cases();
        return view('activities.perubahan-modal.create', compact('jenisOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jenis' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        try {
            $cleanJumlah = str_replace('.', '', $request->jumlah);

            PerubahanModal::create([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'jenis' => $request->jenis,
                'jumlah' => $cleanJumlah,
            ]);

            return redirect()->route('perubahan-modal.index')->with('success', 'Data perubahan modal berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan perubahan modal: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }

    public function edit($id)
    {
        $data = PerubahanModal::findOrFail($id);
        $jenisOptions = PerubahanModalJenisEnum::cases();
        return view('activities.perubahan-modal.edit', compact('data', 'jenisOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jenis' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        try {
            $data = PerubahanModal::findOrFail($id);
            $cleanJumlah = str_replace('.', '', $request->jumlah);

            $data->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'jenis' => $request->jenis,
                'jumlah' => $cleanJumlah,
            ]);

            return redirect()->route('perubahan-modal.index')->with('success', 'Data perubahan modal berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate perubahan modal: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $data = PerubahanModal::findOrFail($id);
            $data->update(['is_deleted' => 1]);

            return redirect()->route('perubahan-modal.index')->with('success', 'Data perubahan modal berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus perubahan modal: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan.');
        }
    }

    public function show($id)
    {
        $modal = PerubahanModal::findOrFail($id);
        return view('activities.perubahan-modal.show', compact('modal'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $data = PerubahanModal::where('is_deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('keterangan', 'LIKE', "%{$keyword}%")
                    ->orWhere('jenis', 'LIKE', "%{$keyword}%")
                    ->orWhere('jumlah', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($data);
    }
}
