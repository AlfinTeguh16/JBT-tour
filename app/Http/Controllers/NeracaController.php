<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Neraca;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NeracaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $neraca = Neraca::where('is_deleted', 0)->paginate(10);

        return view('activities.data-neraca.index', compact('neraca'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.data-neraca.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->merge([
            'biaya_spidi'      => str_replace('.', '', $request->input('biaya_spidi')),
            'biaya_listrik'    => str_replace('.', '', $request->input('biaya_listrik')),
            'biaya_air_minum'  => str_replace('.', '', $request->input('biaya_air_minum')),
            'gaji_karyawan'    => str_replace('.', '', $request->input('gaji_karyawan')),
            'modal_perusahaan' => str_replace('.', '', $request->input('modal_perusahaan')),
            'biaya_telepon'    => str_replace('.', '', $request->input('biaya_telepon')),
        ]);

        try {
            $validated = $request->validate([
                'bulan'              => 'required|string|max:255',
                'biaya_spidi'        => 'required|numeric',
                'biaya_listrik'      => 'required|numeric',
                'biaya_air_minum'    => 'required|numeric',
                'gaji_karyawan'      => 'required|numeric',
                'modal_perusahaan'   => 'required|numeric',
                'biaya_telepon'      => 'required|numeric',
            ]);

            // ✅ Cek duplikat berdasarkan semua field kecuali is_deleted
            $isDuplicate = Neraca::where('bulan', $validated['bulan'])
                ->where('biaya_spidi', $validated['biaya_spidi'])
                ->where('biaya_listrik', $validated['biaya_listrik'])
                ->where('biaya_air_minum', $validated['biaya_air_minum'])
                ->where('gaji_karyawan', $validated['gaji_karyawan'])
                ->where('modal_perusahaan', $validated['modal_perusahaan'])
                ->where('biaya_telepon', $validated['biaya_telepon'])
                ->where('is_deleted', 0)
                ->exists();

            if ($isDuplicate) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['duplicate' => 'Data Neraca dengan nilai yang sama sudah ada.']);
            }

            // Simpan data
            $neraca = new Neraca($validated);
            $neraca->save();


            return redirect()->route('data-neraca.index')->with('success', 'Data Neraca berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Data Neraca: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['failed' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi!'])
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $neraca = Neraca::findOrFail($id);
        return view('activities.data-neraca.show', compact('neraca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $neraca = Neraca::findOrFail($id);
        return view('activities.data-neraca.edit', compact('neraca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validasi data input
            $validated = $request->validate([
                'bulan'    => 'required|string|max:255',
                'biaya_spidi'          => 'required|float',
                'biaya_listril'       => 'required|float',
                'biaya_air_minum'    => 'required|float',
                'gaji_karyawan' => 'required|float',
                'modal_perusahaan' => 'required|float',
                'biaya_telepon'     => 'required|float',
            ]);

            // Simpan data ke database
            $neraca = Neraca::findOrFail($id);

            // Update data
            $neraca->update($validated);
            $neraca->save();

            return redirect()->route('data-neraca.index')->with('success', 'Data Neraca berhasil diperbarui!');

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal memperbarui Data Neraca: ' . $e->getMessage());

            // Redirect kembali dengan pesan error
            return redirect()->back()->withErrors(['failed' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi!'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari data berdasarkan ID
            $neraca = Neraca::findOrFail($id);

            // Update kolom is_deleted menjadi true (1)
            $neraca->update(['is_deleted' => true]);

            return redirect()->route('data-neraca.index')->with('success', 'Data Neraca berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus Data Neraca: ' . $e->getMessage());
            return back()->with('failed', 'Terjadi kesalahan saat menonaktifkan Data Neraca.');
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // Ambil data sesuai pencarian
        $neraca = Neraca::where('is_deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('bulan', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return response()->json($neraca);
    }

    public function updateCheckbox(Request $request, $id)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        // Cek apakah field tersebut termasuk kolom yang valid
        $allowedFields = ['data_karyawan', 'draft_pekerjaan', 'transaksi_draft_pekerjaan', 'status_transaksi', 'status_draft_pekerjaan'];

        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Field tidak diizinkan']);
        }

        try {
            $neracas = Neraca::findOrFail($id);
            $neracas->$field = $value;
            $neracas->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Gagal update checkbox: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal update']);
        }
    }
}
