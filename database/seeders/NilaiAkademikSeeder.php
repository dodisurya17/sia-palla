<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\NilaiAkademik;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class NilaiAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::all();
        $mataPelajaran = MataPelajaran::all();
        $guru = Guru::all();

        if ($siswa->isEmpty() || $mataPelajaran->isEmpty() || $guru->isEmpty()) {
            $this->command?->warn('Siswa, Mata Pelajaran, atau Guru belum ada. Jalankan seeder terkait terlebih dahulu.');

            return;
        }

        // Kelompokkan guru berdasarkan mata pelajaran yang diampu,
        // supaya nilai dicatat oleh guru yang relevan dengan mapelnya.
        $guruByMapel = $guru->groupBy('mata_pelajaran_id');

        $semesterList = ['Ganjil', 'Genap'];
        $rows = [];

        foreach ($siswa as $s) {
            foreach ($mataPelajaran as $mapel) {
                $guruMapel = $guruByMapel->get($mapel->id);
                $guruTerpilih = ($guruMapel && $guruMapel->isNotEmpty())
                    ? $guruMapel->random()
                    : $guru->random();

                foreach ($semesterList as $semester) {
                    $tugas = rand(65, 100);
                    $uts = rand(60, 100);
                    $uas = rand(60, 100);
                    $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);

                    $rows[] = [
                        'siswa_id' => $s->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'guru_id' => $guruTerpilih->id,
                        'semester' => $semester,
                        'nilai_tugas' => $tugas,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_akhir' => $akhir,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert per-chunk agar aman untuk jumlah data yang besar
        foreach (array_chunk($rows, 500) as $chunk) {
            NilaiAkademik::insert($chunk);
        }

        $this->command?->info(count($rows) . ' data nilai akademik berhasil dibuat.');
    }
}
