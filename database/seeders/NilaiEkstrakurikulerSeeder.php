<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NilaiEkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::all();
        $semesters = DB::table('semester')->orderBy('id')->get();

        if ($siswa->isEmpty() || Ekstrakurikuler::count() === 0) {
            $this->command?->warn('Siswa atau Ekstrakurikuler belum ada. Jalankan seeder terkait terlebih dahulu.');

            return;
        }

        if ($semesters->isEmpty()) {
            $this->command?->warn('Tabel semester masih kosong, nilai ekstrakurikuler tidak dibuat. Isi data semester terlebih dahulu.');

            return;
        }

        // Satu ekskul per nama (tidak peka huruf besar/kecil). Bila ada nama kembar,
        // pilih yang berjenis "wajib" lebih dulu, lalu id terkecil.
        $semuaEkskul = Ekstrakurikuler::orderBy('id')->get();

        $ekstrakurikuler = $semuaEkskul
            ->sortBy(fn($e) => ($e->jenis === 'wajib' ? '0' : '1') . '-' . str_pad((string) $e->id, 10, '0', STR_PAD_LEFT))
            ->groupBy(fn($e) => Str::lower(trim($e->nama_ekstrakurikuler)))
            ->map(fn($group) => $group->first())
            ->values();

        // Bersihkan nilai ganda yang sudah telanjur ada di database.
        $this->bersihkanDuplikat($ekstrakurikuler->pluck('id')->all());

        $wajib = $ekstrakurikuler->where('jenis', 'wajib');
        $pilihan = $ekstrakurikuler->where('jenis', 'pilihan');

        // Nilai (A-E) menentukan predikat secara otomatis
        $nilaiToPredikat = [
            'A' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            'D' => 'Kurang',
            'E' => 'Kurang',
        ];
        $nilaiList = array_keys($nilaiToPredikat);

        // Kombinasi siswa + ekskul + semester yang sudah ada -> dilewati (seeder aman dijalankan ulang).
        $sudahAda = NilaiEkstrakurikuler::query()
            ->get(['siswa_id', 'ekstrakurikuler_id', 'semester_id'])
            ->mapWithKeys(fn($n) => [$n->siswa_id . '-' . $n->ekstrakurikuler_id . '-' . $n->semester_id => true])
            ->all();

        $rows = [];

        foreach ($siswa as $s) {
            // Semua siswa wajib ikut ekstrakurikuler jenis "wajib" (misal Pramuka)
            $ekskulSiswa = $wajib->values();

            // Ditambah 1-2 ekstrakurikuler pilihan secara acak
            if ($pilihan->isNotEmpty()) {
                $jumlahPilihan = min($pilihan->count(), rand(1, 2));
                $ekskulSiswa = $ekskulSiswa->merge($pilihan->random($jumlahPilihan));
            }

            foreach ($ekskulSiswa as $ekskul) {
                foreach ($semesters as $semester) {
                    $kunci = $s->id . '-' . $ekskul->id . '-' . $semester->id;

                    if (isset($sudahAda[$kunci])) {
                        continue;
                    }
                    $sudahAda[$kunci] = true;

                    $nilai = $nilaiList[array_rand($nilaiList)];

                    $rows[] = [
                        'siswa_id' => $s->id,
                        'ekstrakurikuler_id' => $ekskul->id,
                        'semester' => $semester->jenis, // kolom lama (wajib diisi), Ganjil/Genap
                        'semester_id' => $semester->id,
                        'nilai' => $nilai,
                        'predikat' => $nilaiToPredikat[$nilai],
                        'keterangan' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            NilaiEkstrakurikuler::insert($chunk);
        }

        $this->command?->info(count($rows) . ' data nilai ekstrakurikuler berhasil dibuat.');
    }

    /**
     * Hapus nilai ekskul ganda:
     * 1. nilai yang menunjuk ke baris ekskul duplikat (nama sama, bukan yang dipakai seeder);
     * 2. nilai kembar untuk siswa + ekskul + semester yang sama (sisakan yang pertama).
     */
    private function bersihkanDuplikat(array $idDipakai): void
    {
        $hapusDuplikatNama = NilaiEkstrakurikuler::whereNotIn('ekstrakurikuler_id', $idDipakai)->delete();

        $kembar = NilaiEkstrakurikuler::query()
            ->orderBy('id')
            ->get(['id', 'siswa_id', 'ekstrakurikuler_id', 'semester_id'])
            ->groupBy(fn($n) => $n->siswa_id . '-' . $n->ekstrakurikuler_id . '-' . $n->semester_id)
            ->flatMap(fn($group) => $group->skip(1)->pluck('id'))
            ->all();

        foreach (array_chunk($kembar, 500) as $chunk) {
            NilaiEkstrakurikuler::whereIn('id', $chunk)->delete();
        }

        $total = $hapusDuplikatNama + count($kembar);
        if ($total > 0) {
            $this->command?->info("{$total} nilai ekstrakurikuler ganda dibersihkan.");
        }
    }
}
