<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKeuangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LaporanKeuanganController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporanKeuangan = LaporanKeuangan::where('is_deleted', 0)->paginate(10);

        return view('activities.laporan-keuangan.index', compact('laporanKeuangan'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'file_laporan_keuangan' => 'required|file|mimes:pdf,doc,docx|max:5120', // max 5MB
            ]);

            if ($request->hasFile('file_laporan_keuangan')) {
                $file = $request->file('file_laporan_keuangan');

                // Simpan file ke storage
                $path = $file->storeAs(
                    'file_laporan_keuangan',
                    $file->getClientOriginalName(), // pakai nama asli
                    'public'
                );

                $validated['file_laporan_keuangan'] = $path;
            } else {
                $validated['file_laporan_keuangan'] = null;
            }

            // Simpan data ke database
            LaporanKeuangan::create($validated);

            return redirect()->back()->with('success', 'Laporan Keuangan berhasil diunggah.');
        } catch (ValidationException $e) {
            if ($e->validator->errors()->has('file_laporan_keuangan')) {
                $message = $e->validator->errors()->first('file_laporan_keuangan');
                return redirect()->back()->with('error', 'Upload gagal: ' . $message)->withInput();
            }

            return redirect()->back()->with('error', 'Validasi gagal.')->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan laporan keuangan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan laporan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);

        return view('activities.laporan-keuangan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'file_laporan_keuangan' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);

            $laporan = LaporanKeuangan::findOrFail($id);

            // Jika file baru diupload, ganti yang lama
            if ($request->hasFile('file_laporan_keuangan')) {
                // Hapus file lama jika ada
                if ($laporan->file_laporan_keuangan && Storage::disk('public')->exists($laporan->file_laporan_keuangan)) {
                    Storage::disk('public')->delete($laporan->file_laporan_keuangan);
                }

                // Simpan file baru dengan nama aslinya
                $file = $request->file('file_laporan_keuangan');
                $path = $file->storeAs('file_laporan_keuangan', $file->getClientOriginalName(), 'public');

                $laporan->file_laporan_keuangan = $path;
            }

            $laporan->save();

            return redirect()->back()->with('success', 'Laporan Keuangan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update laporan keuangan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui laporan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari data berdasarkan ID
            $laporanKeuangan = LaporanKeuangan::findOrFail($id);
    
            // Update kolom is_deleted menjadi true (1)
            $laporanKeuangan->update(['is_deleted' => true]);
    
            // Pastikan route ini benar-benar ada
            return redirect()->route('laporan-keuangan.index')->with('success', 'Data Laporan Keuangan berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus Data LaporanKeuangan: ' . $e->getMessage());
    
            // Debug cepat:
            return back()->with('failed', 'Terjadi kesalahan saat menonaktifkan Data Laporan Keuangan. Pesan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, string $id){
        try {
            $request->validate([
                'status_laporan' => 'required|in:tervalidasi,belum tervalidasi',
            ]);
    
            $laporan = LaporanKeuangan::findOrFail($id);
            $laporan->status_laporan = $request->status_laporan;
            $laporan->save();
    
            return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui status laporan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status laporan.');
        }
    }
}
