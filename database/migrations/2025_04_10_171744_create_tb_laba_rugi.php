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
        Schema::create('tb_laba_rugi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis', ['pendapatan', 'beban']);
            $table->string('keterangan')->nullable();
            $table->decimal('harga_pokok_jasa', 15, 2);
            $table->decimal('laba_kotor', 15, 2);
            $table->decimal('biaya_gaji', 15, 2);
            $table->decimal('beban_meeting', 15, 2);
            $table->decimal('beban_lain_lain', 15, 2);
            $table->decimal('jumlah_beban_operasi', 15, 2);
            $table->decimal('laba_bersih_operasional', 15, 2);
            $table->decimal('laba_bersih', 15, 2);
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
        Schema::dropIfExists('tb_laba_rugi');
    }
};
