<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DraftPekerjaan;
use Illuminate\Support\Str;

class DraftPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            DraftPekerjaan::create([
                'code_draft' => 'DRAFT-' . Str::upper(Str::random(8)),
                'nama_pekerjaan' => 'Proyek ' . Str::random(5),
                'instansi' => 'Instansi ' . Str::random(5),
                'no_instansi' => rand(1000000000, 9999999999), // Nomor acak 10 digit
                'email_instansi' => 'instansi' . rand(1, 99) . '@example.com',
                'tanggal_pengawasan' => now()->subDays(rand(0, 365))->format('Y-m-d'),
                'dokumen_penawaran' => '', // Pastikan string kosong, bukan NULL
                'alamat_proyek' => 'Jl. Random No. ' . rand(1, 100),
                'dokumen_pengawasan' => null,
                'dokumen_perencanaan' => null,
                'laporan_teknis' => null,
                'termin' => null,
                'pajak' => null,
                'status_pekerjaan' => null,
                'is_deleted' => false,
            ]);
        }
    }
}
