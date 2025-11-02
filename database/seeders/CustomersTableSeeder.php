<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class CustomersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Non-aktifkan FK constraints sementara supaya truncate tidak gagal
        Schema::disableForeignKeyConstraints();
        DB::table('customers')->truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Faker::create();

        // Some explicit customers
        DB::table('customers')->insert([
            [
                'name' => 'PT. Contoh Klien',
                'email' => 'client@example.com',
                'phone' => '081298765432',
                'address' => 'Jl. Merdeka No.1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        foreach (range(1, 8) as $i) {
            DB::table('customers')->insert([
                'name' => $faker->name,
                'email' => $faker->optional()->safeEmail,
                'phone' => $faker->optional()->phoneNumber,
                'address' => $faker->optional()->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
