<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::insert([
            [
                'plate_no' => 'DK1234AA',
                'brand'    => 'Toyota',
                'model'    => 'Avanza',
                'capacity' => 6,
                'status'   => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_no' => 'DK5678BB',
                'brand'    => 'Mitsubishi',
                'model'    => 'Xpander',
                'capacity' => 7,
                'status'   => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_no' => 'DK9012CC',
                'brand'    => 'Suzuki',
                'model'    => 'Ertiga',
                'capacity' => 6,
                'status'   => 'maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate_no' => 'DK3456DD',
                'brand'    => 'Hiace',
                'model'    => 'Commuter',
                'capacity' => 12,
                'status'   => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
