<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekstrakurikulers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ekstrakurikuler', 100);
            $table->enum('jenis', ['wajib', 'pilihan'])->default('pilihan');
            $table->string('pembina', 100)->nullable();
            $table->string('jadwal', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikulers')->cascadeOnDelete();
            $table->string('semester');
            $table->enum('nilai', ['A', 'B', 'C', 'D', 'E']);
            $table->enum('predikat', ['Sangat Baik', 'Baik', 'Cukup', 'Kurang']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'ekstrakurikuler_id', 'semester'], 'nilai_ekskul_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_ekstrakurikulers');
        Schema::dropIfExists('ekstrakurikulers');
    }
};
