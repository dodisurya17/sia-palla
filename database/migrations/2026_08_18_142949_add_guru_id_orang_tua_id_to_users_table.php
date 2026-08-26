<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('guru_id')->nullable()->unique()->after('role')
                ->constrained('guru')->nullOnDelete();
            $table->foreignId('orang_tua_id')->nullable()->unique()->after('guru_id')
                ->constrained('orang_tua')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('guru_id');
            $table->dropConstrainedForeignId('orang_tua_id');
        });
    }
};
