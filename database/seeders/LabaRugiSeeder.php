<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LabaRugi;
use Carbon\Carbon;

class LabaRugiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['tanggal' => now(), 'jenis' => 'pendapatan', 'keterangan' => 'Penjualan Produk', 'jumlah' => 12500000],
            ['tanggal' => now(), 'jenis' => 'beban', 'keterangan' => 'Gaji Karyawan', 'jumlah' => 3000000],
            ['tanggal' => now(), 'jenis' => 'beban', 'keterangan' => 'Listrik dan Air', 'jumlah' => 1000000],
        ];

        foreach ($data as $item) {
            LabaRugi::create($item);
        }
    }
}
