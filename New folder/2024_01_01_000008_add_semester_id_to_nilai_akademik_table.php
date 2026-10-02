<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai_akademik', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->after('guru_id')
                ->constrained('semester')->nullOnDelete();
        });

        // Backfill: nilai lama hanya punya string "Ganjil"/"Genap".
        // Tahun ajaran diturunkan dari created_at (tahun ajaran mulai bulan Juli).
        $semesterIds = [];

        foreach (DB::table('nilai_akademik')->select('id', 'semester', 'created_at')->get() as $row) {
            $tanggal = $row->created_at ? Carbon::parse($row->created_at) : now();
            $jenis = in_array($row->semester, ['Ganjil', 'Genap'], true) ? $row->semester : 'Ganjil';
            $mulai = $tanggal->month >= 7 ? $tanggal->year : $tanggal->year - 1;
            $tahunAjaran = $mulai . '/' . ($mulai + 1);
            $key = $tahunAjaran . '|' . $jenis;

            if (!isset($semesterIds[$key])) {
                $semesterIds[$key] = DB::table('semester')->insertGetId([
                    'tahun_ajaran' => $tahunAjaran,
                    'jenis' => $jenis,
                    'status' => 'nonaktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('nilai_akademik')->where('id', $row->id)->update(['semester_id' => $semesterIds[$key]]);
        }

        // Semester terbaru dijadikan aktif. Silakan koreksi manual bila tidak sesuai.
        $terbaru = DB::table('semester')->orderByDesc('tahun_ajaran')->orderByDesc('jenis')->first();
        if ($terbaru) {
            DB::table('semester')->where('id', $terbaru->id)->update(['status' => 'aktif']);
        }
    }

    public function down(): void
    {
        Schema::table('nilai_akademik', function (Blueprint $table) {
            $table->dropConstrainedForeignId('semester_id');
        });
    }
};
