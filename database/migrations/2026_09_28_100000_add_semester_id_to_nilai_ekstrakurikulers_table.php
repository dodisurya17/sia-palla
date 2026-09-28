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
        Schema::table('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->after('ekstrakurikuler_id')
                ->constrained('semester')->nullOnDelete();
        });

        // Backfill: nilai lama hanya punya string "Ganjil"/"Genap".
        // Tahun ajaran diturunkan dari created_at (tahun ajaran mulai bulan Juli),
        // dengan aturan yang sama seperti nilai_akademik.
        $semesterIds = [];

        foreach (DB::table('nilai_ekstrakurikulers')->select('id', 'semester', 'created_at')->get() as $row) {
            $tanggal = $row->created_at ? Carbon::parse($row->created_at) : now();
            $jenis = in_array($row->semester, ['Ganjil', 'Genap'], true) ? $row->semester : 'Ganjil';
            $mulai = $tanggal->month >= 7 ? $tanggal->year : $tanggal->year - 1;
            $tahunAjaran = $mulai . '/' . ($mulai + 1);
            $key = $tahunAjaran . '|' . $jenis;

            if (!isset($semesterIds[$key])) {
                // Pakai semester yang sudah ada (mis. dibuat migrasi nilai_akademik) bila tersedia.
                $semesterIds[$key] = DB::table('semester')
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('jenis', $jenis)
                    ->value('id')
                    ?? DB::table('semester')->insertGetId([
                        'tahun_ajaran' => $tahunAjaran,
                        'jenis' => $jenis,
                        'status' => 'nonaktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            DB::table('nilai_ekstrakurikulers')->where('id', $row->id)->update(['semester_id' => $semesterIds[$key]]);
        }

        // Jika belum ada semester aktif sama sekali, jadikan semester terbaru aktif.
        // Semester aktif yang sudah ada (dari migrasi nilai_akademik) tidak diubah.
        if (!DB::table('semester')->where('status', 'aktif')->exists()) {
            $terbaru = DB::table('semester')->orderByDesc('tahun_ajaran')->orderByDesc('jenis')->first();
            if ($terbaru) {
                DB::table('semester')->where('id', $terbaru->id)->update(['status' => 'aktif']);
            }
        }

        // Unik lama (siswa, ekskul, "Ganjil/Genap") akan bentrok antar tahun ajaran.
        // Unik baru dibuat lebih dulu (kolom pertamanya siswa_id, sehingga tetap mendukung FK siswa_id),
        // baru kemudian yang lama dihapus.
        Schema::table('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->unique(['siswa_id', 'ekstrakurikuler_id', 'semester_id'], 'nilai_ekskul_semester_unique');
        });

        Schema::table('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->dropUnique('nilai_ekskul_unique');
        });
    }

    public function down(): void
    {
        Schema::table('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->unique(['siswa_id', 'ekstrakurikuler_id', 'semester'], 'nilai_ekskul_unique');
        });

        Schema::table('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->dropUnique('nilai_ekskul_semester_unique');
        });

        Schema::table('nilai_ekstrakurikulers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('semester_id');
        });
    }
};
