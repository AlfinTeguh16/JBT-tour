<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiclesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vehicles')->truncate();

        DB::table('vehicles')->insert([
            [
                'plate_no' => 'B 1234 XY',
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'capacity' => 7,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_no' => 'B 5678 ZY',
                'brand' => 'Isuzu',
                'model' => 'Elf',
                'capacity' => 15,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}