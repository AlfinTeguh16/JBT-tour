<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JurnalUmum;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JurnalUmumSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk jurnal umum.
     */
    public function run(): void
    {
        $akunDebet = ['Kas', 'Piutang Usaha', 'Perlengkapan', 'Peralatan'];
        $akunKredit = ['Pendapatan Jasa', 'Utang Usaha', 'Modal', 'Penjualan'];

        for ($i = 1; $i <= 15; $i++) {
            JurnalUmum::create([
                'tanggal' => Carbon::now()->subDays(rand(1, 100))->format('Y-m-d'),
                'keterangan' => 'Transaksi Jurnal ke-' . $i,
                'akun_debet' => $akunDebet[array_rand($akunDebet)],
                'akun_kredit' => $akunKredit[array_rand($akunKredit)],
                'jumlah' => rand(500000, 5000000),
                'is_deleted' => 0,
            ]);
        }
    }
}
