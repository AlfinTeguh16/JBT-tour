<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Rules\UniqueWithoutDeleted;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = Karyawan::where('is_deleted', 0)->paginate(10);

        return view('activities.data_karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.data_karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'email'         => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir'  => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'jabatan'       => 'required|string|max:100',
        ]);

        // Cek duplikasi berdasarkan kombinasi nama + no_telepon dan/atau email, hanya pada data aktif
        $userExists = Karyawan::where('is_deleted', 0)
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('nama', $validated['nama'])
                    ->where('no_telepon', $validated['no_telepon']);
                })
                ->orWhere('email', $validated['email']);
            })
            ->exists();

        if ($userExists) {
            return back()
                ->withInput()
                ->with('failed', 'Data karyawan dengan nama dan kontak/email yang sama sudah ada.');
        }

        // Generate ID Karyawan: format KR0001, KR0002, dst.
        $last = Karyawan::selectRaw("MAX(CAST(SUBSTRING(id_karyawan, 3) AS UNSIGNED)) as max_id")
                        ->first()->max_id ?? 0;

        $validated['id_karyawan'] = 'KR' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        $validated['jenis_kelamin'] = $validated['jenis_kelamin'] === 'L' ? 1 : 0;
        $validated['is_deleted'] = 0;

        try {
            Karyawan::create($validated);
            return redirect()->route('karyawan.index')
                            ->with('success', 'Karyawan berhasil ditambahkan!');
        } catch (\Throwable $th) {
            \Log::error('Error saving karyawan: ' . $th->getMessage());
            return back()
                ->withInput()
                ->with('failed', 'Gagal menyimpan data karyawan. Silakan coba lagi.');
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        return view('activities.data_karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        return view('activities.data_karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'email'         => 'required|email|max:255',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir'  => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'jabatan'       => 'required|string|max:100',
        ]);

        // Cek apakah ada karyawan lain yang duplikat nama + no_telepon atau email
        $isDuplicate = Karyawan::where('id_karyawan', '!=', $karyawan->id_karyawan)
            ->where('is_deleted', 0)
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('nama', $validated['nama'])
                    ->where('no_telepon', $validated['no_telepon']);
                })->orWhere('email', $validated['email'])
                ->orWhere(function ($q) use ($validated) {
                    $q->where('nama', $validated['nama'])
                        ->where('email', $validated['email']);
                });
            })
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->with('failed', 'Data serupa sudah terdaftar.');
        }

        $validated['jenis_kelamin'] = $validated['jenis_kelamin'] === 'L' ? 1 : 0;

        try {
            $karyawan->update($validated);
            return redirect()
                ->route('karyawan.show', $karyawan)
                ->with('success', 'Data karyawan berhasil diperbarui!');
        } catch (\Throwable $e) {
            \Log::error('Error updating karyawan: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('failed', 'Gagal memperbarui data karyawan. Silakan coba lagi.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->update(['is_deleted' => 1]);

        return redirect()->route('karyawan.index')
                        ->with('success', 'Data karyawan berhasil dihapus.');
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

    // Ambil data sesuai pencarian
    $karyawan = Karyawan::where('is_deleted', 0)
        ->where(function ($query) use ($keyword) {
            $query->where('nama', 'LIKE', "%{$keyword}%")
                ->orWhere('id_karyawan', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%")
                ->orWhere('jabatan', 'LIKE', "%{$keyword}%");
        })
        ->get();

    return response()->json($karyawan);
    }

}
