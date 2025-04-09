<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Neraca;
use App\Enums\Bulan;
use Illuminate\Support\Arr;

class NeracaSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            Neraca::create([
                'bulan' => Arr::random(Bulan::values()),

                'data_karyawan' => rand(0, 1),
                'draft_pekerjaan' => rand(0, 1),
                'transaksi_draft_pekerjaan' => rand(0, 1),
                'status_transaksi' => rand(0, 1),
                'status_draft_pekerjaan' => rand(0, 1),

                'biaya_spidi' => fake()->numberBetween(1000000, 5000000),
                'biaya_listrik' => fake()->numberBetween(300000, 2000000),
                'biaya_air_minum' => fake()->numberBetween(50000, 500000),
                'gaji_karyawan' => fake()->numberBetween(3000000, 15000000),
                'modal_perusahaan' => fake()->numberBetween(10000000, 50000000),
                'biaya_telepon' => fake()->numberBetween(50000, 1000000),
                'is_deleted' => false,
            ]);
        }
    }
}
