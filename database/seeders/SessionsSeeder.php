<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SessionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sessions')->truncate();


        DB::table('sessions')->insert([
            [
                'id' => Str::random(40),
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'SeederAgent/1.0',
                'payload' => base64_encode('dummy'),
                'last_activity' => now()->timestamp,
            ],
        ]);
    }
}
