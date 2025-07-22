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
        Schema::create('tb_jurnal_umum', function (Blueprint $table) {
            $table->id();
            $table->string('transaksi');
            $table->date('tanggal');
            $table->string('keterangan', 255)->nullable();
            $table->string('akun_debet', 100)->nullable();
            $table->string('akun_kredit', 100)->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_jurnal_umum');
    }
};
