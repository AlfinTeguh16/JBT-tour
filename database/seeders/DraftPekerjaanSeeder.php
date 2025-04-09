<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DraftPekerjaan;
use App\Models\TransaksiDraftPekerjaan;
use Illuminate\Support\Str;

class DraftPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $draft = DraftPekerjaan::create([
                'code_draft' => 'DRAFT-' . Str::upper(Str::random(8)),
                'nama_pekerjaan' => 'Proyek ' . Str::random(5),
                'instansi' => 'Instansi ' . Str::random(5),
                'no_instansi' => rand(1000000000, 9999999999),
                'email_instansi' => 'instansi' . rand(1, 99) . '@example.com',
                'tanggal_pengawasan' => now()->subDays(rand(0, 365))->format('Y-m-d'),
                'dokumen_penawaran' => '',
                'alamat_proyek' => 'Jl. Random No. ' . rand(1, 100),
                'dokumen_pengawasan' => null,
                'dokumen_perencanaan' => null,
                'laporan_teknis' => null,
                'termin' => null,
                'pajak' => null,
                'status_pekerjaan' => null,
                'is_deleted' => false,
            ]);
    
            // Buat transaksi otomatis untuk draft ini
            TransaksiDraftPekerjaan::create([
                'draft_pekerjaan_id' => $draft->id,
                'nilai_pekerjaan' => rand(100000000, 500000000),
                'nilai_dpp' => rand(80000000, 490000000),
                'nilai_ppn' => rand(5000000, 10000000),
                'nilai_pph_final' => rand(2000000, 8000000),
                'nilai_bersih_pekerjaan' => rand(85000000, 495000000),
            ]);
        }
    }
    
}
