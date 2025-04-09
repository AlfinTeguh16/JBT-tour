<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiDraftPekerjaan;
use App\Models\DraftPekerjaan;
use Illuminate\Support\Facades\DB;

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
            'draft_pekerjaan_id' => 'required|exists:tb_draft_pekerjaan,id|unique:tb_tansaksi_draft_pekerjaan,draft_pekerjaan_id',
            'nilai_pekerjaan' => 'required|numeric|min:0',
            'nilai_dpp' => 'required|numeric|min:0',
            'nilai_ppn' => 'required|numeric|min:0',
            'nilai_pph_final' => 'required|numeric|min:0',
            'nilai_bersih_pekerjaan' => 'required|numeric|min:0',
        ]);

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
        $transaksi = TransaksiDraftPekerjaan::findOrFail($id);

        $request->validate([
            'draft_pekerjaan_id' => 'required|exists:tb_draft_pekerjaan,id|unique:tb_tansaksi_draft_pekerjaan,draft_pekerjaan_id,' . $id,
            'nilai_pekerjaan' => 'required|numeric|min:0',
            'nilai_dpp' => 'required|numeric|min:0',
            'nilai_ppn' => 'required|numeric|min:0',
            'nilai_pph_final' => 'required|numeric|min:0',
            'nilai_bersih_pekerjaan' => 'required|numeric|min:0',
        ]);

        $transaksi->update($request->all());

        return redirect()->route('transaksi-draft-pekerjaan.index')->with('success', 'Transaksi berhasil diperbarui!');
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
