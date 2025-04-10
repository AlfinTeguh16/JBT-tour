<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArusKas;
use Illuminate\Support\Carbon;

class ArusKasSeeder extends Seeder
{
    public function run(): void
    {
        $jenisList = ['masuk', 'keluar'];
        $kategoriList = ['operasional', 'investasi', 'pendanaan'];

        for ($i = 0; $i < 20; $i++) {
            ArusKas::create([
                'tanggal'    => Carbon::now()->subDays(rand(0, 90)),
                'keterangan' => 'Transaksi contoh ke-' . ($i + 1),
                'jenis'      => $jenisList[array_rand($jenisList)],
                'kategori'   => $kategoriList[array_rand($kategoriList)],
                'jumlah'     => rand(100000, 5000000),
                'is_deleted'  => false,
            ]);
        }
    }
}
