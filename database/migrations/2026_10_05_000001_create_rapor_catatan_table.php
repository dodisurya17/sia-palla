<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapor_catatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('semester_id');
            $table->text('catatan_wali_kelas')->nullable();
            $table->unsignedSmallInteger('sakit')->nullable();
            $table->unsignedSmallInteger('izin')->nullable();
            $table->unsignedSmallInteger('alpa')->nullable();
            // { "<mata_pelajaran_id>": { "paham": "...", "bimbingan": "..." } }
            $table->json('capaian')->nullable();
            // { "<ekstrakurikuler_id>": "keterangan" }
            $table->json('keterangan_ekskul')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapor_catatan');
    }
};
