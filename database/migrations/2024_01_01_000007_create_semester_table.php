<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 9);            // contoh: 2025/2026
            $table->enum('jenis', ['Ganjil', 'Genap']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->timestamps();

            $table->unique(['tahun_ajaran', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semester');
    }
};
