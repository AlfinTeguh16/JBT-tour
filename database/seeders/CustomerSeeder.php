<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            [
                'name'    => 'John Doe',
                'email'   => 'john@example.com',
                'phone'   => '081234567890',
                'address' => 'Jl. Raya Kuta No. 1, Bali',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'    => 'Jane Smith',
                'email'   => 'jane@example.com',
                'phone'   => '081298765432',
                'address' => 'Jl. Ubud No. 2, Gianyar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'    => 'Michael Lee',
                'email'   => 'michael@example.com',
                'phone'   => '082134567890',
                'address' => 'Jl. Sunset Road No. 99, Denpasar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'    => 'Siti Aminah',
                'email'   => 'siti@example.com',
                'phone'   => '083212345678',
                'address' => 'Jl. Legian No. 45, Kuta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
