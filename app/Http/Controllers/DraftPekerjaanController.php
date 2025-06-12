<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DraftPekerjaan;
use App\Models\TransaksiDraftPekerjaan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Rules\UniqueWithoutDeleted;

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
        // dd($request->all());
        try {
            // Validasi data input
            $validated = $request->validate([
                'nama_pekerjaan'     => 'required|string|max:255',
                'instansi'           => 'required|string|max:255',
                'no_instansi'        => 'required|string|max:20',
                'email_instansi'     => 'nullable|email|max:255',
                'tanggal_pengawasan' => 'nullable|date',
                'dokumen_penawaran'  => 'nullable|file|mimes:pdf,doc,docx,xls|max:2048',
                'alamat_proyek'      => 'required|string',
            ]);

            // Cek apakah data dengan kombinasi yang sama dan is_deleted = 0 sudah ada
            $exists = DraftPekerjaan::where('is_deleted', 0)
                ->where('nama_pekerjaan', $validated['nama_pekerjaan'])
                ->where('instansi', $validated['instansi'])
                ->where('no_instansi', $validated['no_instansi'])
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['duplicate' => 'Data dengan nama pekerjaan, instansi, dan nomor instansi yang sama sudah ada.']);
            }

            // Buat code_draft unik
            $validated['code_draft'] = 'DRAFT-' . Str::upper(Str::random(8));

            // Upload dokumen penawaran jika ada
            if ($request->hasFile('dokumen_penawaran')) {
                $validated['dokumen_penawaran'] = $request->file('dokumen_penawaran')->store('dokumen_penawaran', 'public');
            } else {
                $validated['dokumen_penawaran'] = null;
            }

            // Simpan draft pekerjaan ke DB
            $draft = DraftPekerjaan::create($validated);

            // Buat transaksi otomatis terkait draft tersebut
            TransaksiDraftPekerjaan::create([
                'draft_pekerjaan_id' => $draft->id,
                'nilai_pekerjaan' => rand(100000000, 500000000),
                'nilai_dpp' => rand(80000000, 490000000),
                'nilai_ppn' => rand(5000000, 10000000),
                'nilai_pph_final' => rand(2000000, 8000000),
                'nilai_bersih_pekerjaan' => rand(85000000, 495000000),
            ]);

            Log::debug('Redirecting to index');
            return redirect()->route('draft-pekerjaan.index')->with('success', 'Draft Pekerjaan berhasil dibuat!');

            // return redirect()->route('draft-pekerjaan.index')->with('success', 'Draft Pekerjaan berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Draft Pekerjaan: ' . $e->getMessage());

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
            // Cari draft pekerjaan
            $draftPekerjaan = DraftPekerjaan::findOrFail($id);

            // Soft delete draft
            $draftPekerjaan->update(['is_deleted' => true]);

            // Update transaksi yang terkait
            TransaksiDraftPekerjaan::where('draft_pekerjaan_id', $id)
                ->update(['is_deleted' => true]);

            return redirect()->route('draft-pekerjaan.index')->with('success', 'Draft Pekerjaan & Transaksi terkait berhasil dinonaktifkan!');
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
