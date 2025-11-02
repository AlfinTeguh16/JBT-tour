<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PasswordResetTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $faker = Faker::create();


    DB::table('password_reset_tokens')->truncate();


    foreach (range(1, 3) as $i) {
    DB::table('password_reset_tokens')->insert([
    'email' => "user{$i}@example.com",
    'token' => bin2hex(random_bytes(16)),
    'created_at' => now()->subMinutes(rand(1, 60)),
    ]);
    }
    }
}
