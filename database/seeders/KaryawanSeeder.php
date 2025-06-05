<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        $data = [];
        $now = Carbon::now();

        // Mulai dari index 1 untuk menghasilkan 15 data
        for ($i = 1; $i <= 15; $i++) {
            $data[] = [
                // Mulai kode karyawan dari KR0002, KR0003, dst.
                'id_karyawan'   => 'KR' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nama'          => "Karyawan " . $i,
                'no_telepon'    => "0812345" . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'email'         => "karyawan{$i}@mail.com",
                // Untuk contoh tanggal lahir, kita kurangi tahun berdasarkan index
                'tanggal_lahir' => $now->copy()->subYears(20 + $i)->format('Y-m-d'),
                'tempat_lahir'  => "Kota " . $i,
                // Misal, jika genap maka Laki-laki (1), ganjil Perempuan (0)
                'jenis_kelamin' => $i % 2 === 0 ? 1 : 0,
                'alamat'        => "Jl. Contoh Alamat No. " . $i,
                'nama'          => "Jabatan " . $i,
                // Sesuai JSON, gunakan field is_deleted (0 = aktif)
                'is_deleted'    => 0,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        DB::table('tb_karyawan')->insert($data);
    }
}
