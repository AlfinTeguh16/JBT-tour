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
        Schema::create('tb_draft_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('code_draft')->unique();
            $table->string('nama_pekerjaan');
            $table->text('dokumen_pengawasan')->nullable();
            $table->text('dokumen_perencanaan')->nullable();
            $table->text('laporan_teknis')->nullable();
            $table->text('termin')->nullable();
            $table->text('pajak')->nullable();
            $table->string('status_pekerjaan')->nullable();
            $table->string('instansi');
            $table->string('no_instansi');
            $table->string('email_instansi')->nullable();
            $table->date('tanggal_pengawasan')->nullable();
            $table->text('dokumen_penawaran')->nullable();
            $table->string('alamat_proyek');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_draft_pekerjaan');
    }
};
