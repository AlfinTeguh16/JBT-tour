<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_neraca', function (Blueprint $table) {
            $table->id();
            $table->enum('bulan', [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ]);
            $table->string('data_karyawan')->nuallable();
            $table->string('draft_pekerjaan')->nuallable();
            $table->string('transaksi_draft_pekerjaan')->nuallable();
            $table->string('status_transaksi')->nuallable();
            $table->string('status_draft_pekerjaan')->nuallable();
            $table->float('biaya_spidi')->default(0);
            $table->float('biaya_listrik')->default(0);
            $table->float('biaya_air_minum')->default(0);
            $table->float('gaji_karyawan')->default(0);
            $table->float('modal_perusahaan')->default(0);
            $table->float('biaya_telepon')->default(0);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_neraca');
    }
};
