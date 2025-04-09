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
        Schema::create('tb_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan')->unique();
            $table->string('nama');
            $table->string('no_telepon');
            $table->string('email');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->boolean('jenis_kelamin');
            $table->text('alamat');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_karyawan');
    }
};
