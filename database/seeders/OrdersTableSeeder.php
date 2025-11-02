<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->truncate();

        $customers = DB::table('customers')->pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        foreach (range(1, 10) as $i) {
            DB::table('orders')->insert([
                'customer_id' => $faker->randomElement($customers),
                'requested_at' => now()->subDays(rand(0, 5)),
                'service_date' => now()->addDays(rand(0, 10)),
                'pickup_location' => $faker->address,
                'dropoff_location' => $faker->address,
                'notes' => $faker->optional()->sentence,
                'status' => $faker->randomElement(['pending','assigned','in_progress','completed','cancelled']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}