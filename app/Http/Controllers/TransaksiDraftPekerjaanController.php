<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiDraftPekerjaan;
use App\Models\DraftPekerjaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiDraftPekerjaanController extends Controller
{
    public function index()
    {
        $transaksiDP = DB::table('tb_tansaksi_draft_pekerjaan as t')
            ->join('tb_draft_pekerjaan as d', 't.draft_pekerjaan_id', '=', 'd.id')
            ->where('t.is_deleted', 0)
            ->where('d.is_deleted', 0)
            ->select(
                't.id',
                't.nilai_pekerjaan',
                't.nilai_dpp',
                't.nilai_ppn',
                't.nilai_pph_final',
                't.nilai_bersih_pekerjaan',
                'd.code_draft',
                'd.nama_pekerjaan',
                'd.instansi'
            )
            ->paginate(10);

        return view('activities.transaksi-draft-pekerjaan.index', compact('transaksiDP'));
    }


    public function create()
    {
        $drafts = DraftPekerjaan::whereDoesntHave('transaksi')->get();
        return view('activities.transaksi-draft-pekerjaan.create', compact('drafts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'draft_pekerjaan_id' => 'required|exists:tb_draft_pekerjaan,id',
            'nilai_pekerjaan' => 'required|numeric|min:0',
            'nilai_dpp' => 'required|numeric|min:0',
            'nilai_ppn' => 'required|numeric|min:0',
            'nilai_pph_final' => 'required|numeric|min:0',
            'nilai_bersih_pekerjaan' => 'required|numeric|min:0',
        ]);

        // Cek duplikasi manual dengan pengecualian jika is_deleted = 1
        $isDuplicate = TransaksiDraftPekerjaan::where('draft_pekerjaan_id', $request->draft_pekerjaan_id)
            ->where('nilai_pekerjaan', $request->nilai_pekerjaan)
            ->where('nilai_dpp', $request->nilai_dpp)
            ->where('nilai_ppn', $request->nilai_ppn)
            ->where('nilai_pph_final', $request->nilai_pph_final)
            ->where('nilai_bersih_pekerjaan', $request->nilai_bersih_pekerjaan)
            ->where('is_deleted', 0)
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'duplicate' => 'Data transaksi dengan nilai dan draft pekerjaan yang sama sudah pernah diinput.',
            ]);
        }

        // Simpan data
        TransaksiDraftPekerjaan::create($request->all());

        return redirect()->route('transaksi-draft-pekerjaan.index')->with('success', 'Transaksi berhasil disimpan!');
    }


    public function edit($id)
    {
        $transaksi = TransaksiDraftPekerjaan::findOrFail($id);
        $drafts = DraftPekerjaan::all();
        return view('activities.transaksi-draft-pekerjaan.edit', compact('transaksi', 'drafts'));
    }



        public function update(Request $request, $id)
        {
            try {
                $transaksi = TransaksiDraftPekerjaan::findOrFail($id);
                Log::info('Mulai proses update TransaksiDraftPekerjaan', ['id' => $id]);

                $request->validate([
                    'draft_pekerjaan_id' => 'required|exists:tb_draft_pekerjaan,id|unique:tb_tansaksi_draft_pekerjaan,draft_pekerjaan_id,' . $id,
                    'nilai_pekerjaan' => 'required|numeric|min:0',
                    'nilai_dpp' => 'required|numeric|min:0',
                    'nilai_ppn' => 'required|numeric|min:0',
                    'nilai_pph_final' => 'required|numeric|min:0',
                    'nilai_bersih_pekerjaan' => 'required|numeric|min:0',
                ]);
                // dd($request->all());

                $transaksi->update($request->all());

                Log::info('Berhasil update TransaksiDraftPekerjaan', [
                    'id' => $id,
                    'data' => $request->all()
                ]);

                return redirect()->route('transaksi-draft-pekerjaan.index')
                    ->with('success', 'Transaksi berhasil diperbarui!');
            } catch (\Exception $e) {
                Log::error('Gagal update TransaksiDraftPekerjaan', [
                    'id' => $id,
                    'error' => $e->getMessage()
                ]);

                return redirect()->back()
                    ->with('failed', 'Terjadi kesalahan saat mengupdate data.')
                    ->withInput();
            }
        }


    public function show($id)
    {
        $transaksi = TransaksiDraftPekerjaan::with('draft')->findOrFail($id);
        return view('activities.transaksi-draft-pekerjaan.show', compact('transaksi'));
    }

    // public function destroy($id)
    // {
    //     $transaksi = TransaksiDraftPekerjaan::findOrFail($id);
    //     $transaksi->delete();

    //     return redirect()->route('transaksi-draft-pekerjaan.index')->with('success', 'Transaksi berhasil dihapus!');
    // }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $results = TransaksiDraftPekerjaan::with('draft')
            ->whereHas('draft', function ($query) use ($keyword) {
                $query->where('nama_pekerjaan', 'like', "%{$keyword}%")
                      ->orWhere('instansi', 'like', "%{$keyword}%")
                      ->orWhere('code_draft', 'like', "%{$keyword}%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nilai_pekerjaan' => $item->nilai_pekerjaan,
                    'nilai_dpp' => $item->nilai_dpp,
                    'nilai_ppn' => $item->nilai_ppn,
                    'nilai_pph_final' => $item->nilai_pph_final,
                    'nilai_bersih_pekerjaan' => $item->nilai_bersih_pekerjaan,
                    'draft' => [
                        'code_draft' => $item->draft->code_draft ?? '-',
                        'nama_pekerjaan' => $item->draft->nama_pekerjaan ?? '-',
                        'instansi' => $item->draft->instansi ?? '-',
                    ]
                ];
            });

        return response()->json($results);
    }
}
