<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DraftPekerjaan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DraftPekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $draftPekerjaan = DraftPekerjaan::where('is_deleted', 0)->paginate(10);

        return view('activities.draft-pekerjaan.index', compact('draftPekerjaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.draft-pekerjaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validated = $request->validate([
                'nama_pekerjaan'    => 'required|string|max:255',
                'instansi'          => 'required|string|max:255',
                'no_instansi'       => 'required|string|max:20',
                'email_instansi'    => 'nullable|email|max:255',
                'tanggal_pengawasan' => 'nullable|date',
                'dokumen_penawaran' => 'nullable|file|mimes:pdf,doc,docx,xls|max:2048',
                'alamat_proyek'     => 'required|string',
            ]);
    
            // Buat code_draft unik
            $validated['code_draft'] = 'DRAFT-' . Str::upper(Str::random(8));
    
            // Upload dokumen penawaran jika ada
            if ($request->hasFile('dokumen_penawaran')) {
                $validated['dokumen_penawaran'] = $request->file('dokumen_penawaran')->store('dokumen_penawaran', 'public');
            } else {
                $validated['dokumen_penawaran'] = null;
            }
    
            // Simpan data ke database
            DraftPekerjaan::create($validated);
    
            return redirect()->route('draft-pekerjaan.index')->with('success', 'Draft Pekerjaan berhasil dibuat!');
        
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal menyimpan Draft Pekerjaan: ' . $e->getMessage());
    
            // Redirect kembali dengan pesan error
            return redirect()->back()->withErrors(['failed' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi!'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $draftPekerjaan = DraftPekerjaan::findOrFail($id);
        return view('activities.draft-pekerjaan.show', compact('draftPekerjaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $draftPekerjaan = DraftPekerjaan::findOrFail($id);
        return view('activities.draft-pekerjaan.edit', compact('draftPekerjaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama_pekerjaan'    => 'required|string|max:255',
                'instansi'          => 'required|string|max:255',
                'no_instansi'       => 'required|string|max:20',
                'email_instansi'    => 'nullable|email|max:255',
                'tanggal_pengawasan' => 'nullable|date',
                'dokumen_penawaran' => 'nullable|file|mimes:pdf,doc,docx,xls|max:2048',
                'alamat_proyek'     => 'required|string',
            ]);
    
            // Ambil data yang akan diperbarui
            $draftPekerjaan = DraftPekerjaan::findOrFail($id);
    
            // Update data
            $draftPekerjaan->update($validated);
    
            // Jika ada file baru, simpan & hapus file lama
            if ($request->hasFile('dokumen_penawaran')) {
                // Hapus file lama jika ada
                if ($draftPekerjaan->dokumen_penawaran) {
                    Storage::disk('public')->delete($draftPekerjaan->dokumen_penawaran);
                }
                // Simpan file baru
                $validated['dokumen_penawaran'] = $request->file('dokumen_penawaran')->store('dokumen_penawaran', 'public');
                $draftPekerjaan->dokumen_penawaran = $validated['dokumen_penawaran'];
            }
    
            // Simpan perubahan ke database
            $draftPekerjaan->save();
    
            return redirect()->route('draft-pekerjaan.index')->with('success', 'Draft Pekerjaan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui Draft Pekerjaan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui Draft Pekerjaan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari data berdasarkan ID
            $draftPekerjaan = DraftPekerjaan::findOrFail($id);
    
            // Update kolom is_deleted menjadi true (1)
            $draftPekerjaan->update(['is_deleted' => true]);
    
            return redirect()->route('draft-pekerjaan.index')->with('success', 'Draft Pekerjaan berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus Draft Pekerjaan: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan saat menonaktifkan Draft Pekerjaan.');
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // Ambil data sesuai pencarian
        $draftPekerjaan = DraftPekerjaan::where('is_deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('nama_pekerjaan', 'LIKE', "%{$keyword}%")
                    ->orWhere('code_draft', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($draftPekerjaan);
    }

    public function updateCheckbox(Request $request, $id)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        // Cek apakah field tersebut termasuk kolom yang valid
        $allowedFields = ['dokumen_pengawasan', 'dokumen_perencanaan', 'laporan_teknis', 'termin', 'pajak', 'status_pekerjaan'];

        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Field tidak diizinkan']);
        }

        try {
            $draft = DraftPekerjaan::findOrFail($id);
            $draft->$field = $value;
            $draft->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Gagal update checkbox: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal update']);
        }
    }

}
