<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class NilaiEkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::all();
        $ekstrakurikuler = Ekstrakurikuler::all();

        if ($siswa->isEmpty() || $ekstrakurikuler->isEmpty()) {
            $this->command?->warn('Siswa atau Ekstrakurikuler belum ada. Jalankan seeder terkait terlebih dahulu.');

            return;
        }

        $wajib = $ekstrakurikuler->where('jenis', 'wajib');
        $pilihan = $ekstrakurikuler->where('jenis', 'pilihan');

        $predikatList = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'];
        $semesterList = ['Ganjil', 'Genap'];
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
                foreach ($semesterList as $semester) {
                    $rows[] = [
                        'siswa_id' => $s->id,
                        'ekstrakurikuler_id' => $ekskul->id,
                        'semester' => $semester,
                        'predikat' => $predikatList[array_rand($predikatList)],
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
}
