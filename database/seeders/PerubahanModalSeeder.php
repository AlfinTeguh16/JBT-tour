<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PerubahanModalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tanggal' => Carbon::now()->subDays(10),
                'keterangan' => 'Investasi awal pemilik',
                'jenis' => 'investasi_pemilik',
                'jumlah' => 15000000.00,
                'is_deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => Carbon::now()->subDays(5),
                'keterangan' => 'Penarikan modal oleh pemilik',
                'jenis' => 'penarikan_modal',
                'jumlah' => 5000000.00,
                'is_deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => Carbon::now()->subDays(3),
                'keterangan' => 'Laba ditahan akhir periode',
                'jenis' => 'laba_ditahan',
                'jumlah' => 2500000.00,
                'is_deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tb_perubahan_modal')->insert($data);
    }
}
