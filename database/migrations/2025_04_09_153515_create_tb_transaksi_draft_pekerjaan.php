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
        Schema::create('tb_tansaksi_draft_pekerjaan', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tb_draft_pekerjaan
            $table->foreignId('draft_pekerjaan_id')->constrained('tb_draft_pekerjaan')->onDelete('cascade')->unique();

            $table->decimal('nilai_pekerjaan', 20, 2)->default(0);
            $table->decimal('nilai_dpp', 20, 2)->default(0);
            $table->decimal('nilai_ppn', 20, 2)->default(0);
            $table->decimal('nilai_pph_final', 20, 2)->default(0);
            $table->decimal('nilai_bersih_pekerjaan', 20, 2)->default(0);
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi_draft_pekerjaan');
    }
};
