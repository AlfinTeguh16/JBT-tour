<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Non-aktifkan FK checks DB (MySQL). Gunakan try/finally supaya selalu di-enable kembali.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } else {
            // fallback portable untuk driver lain (SQLite)
            Schema::disableForeignKeyConstraints();
        }

        try {
            // Panggil semua seeder (urutkan jika perlu)
            $this->call([
                // PasswordResetTokensSeeder::class,
                // SessionsSeeder::class,
                UsersTableSeeder::class,
                // CustomersTableSeeder::class,
                VehiclesTableSeeder::class,
                // OrdersTableSeeder::class,
                // AssignmentsTableSeeder::class,
                // WorkSessionsTableSeeder::class,
                // DriverLocationsTableSeeder::class,
                // NotificationsTableSeeder::class,
            ]);
        } finally {
            // Aktifkan kembali FK checks
            if (DB::getDriverName() === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } else {
                Schema::enableForeignKeyConstraints();
            }
        }
    }
}
