<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([
            [
                "name" => "direktur",
                "email" => "direktur@email.com",
                "password" => bcrypt("direktur"),
                "role" => "direktur",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "admin",
                "email" => "admin@email.com",
                "password" => bcrypt("admin"),
                "role" => "admin",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "pengawas",
                "email" => "pengawas@email.com",
                "password" => bcrypt("pengawas"),
                "role" => "pengawas",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "akuntan",
                "email" => "akuntan@email.com",
                "password" => bcrypt("akuntan"),
                "role" => "akuntan",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }
}
