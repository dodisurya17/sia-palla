<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $namaDepan = [
            'Ahmad',
            'Budi',
            'Citra',
            'Dewi',
            'Eko',
            'Fitri',
            'Gunawan',
            'Hesti',
            'Indra',
            'Joko',
            'Kartika',
            'Lestari',
            'Muhammad',
            'Nurul',
            'Oktavia',
            'Putra',
            'Rina',
            'Siti',
            'Taufik',
            'Umar',
            'Vera',
            'Wahyu',
            'Yuni',
            'Zainal',
        ];

        $namaBelakang = [
            'Santoso',
            'Wijaya',
            'Kusuma',
            'Pratama',
            'Setiawan',
            'Nugroho',
            'Rahayu',
            'Hidayat',
            'Saputra',
            'Handayani',
            'Susanti',
            'Firmansyah',
            'Wibowo',
            'Permata',
            'Anggraini',
            'Kurniawan',
            'Ramadhan',
            'Purnomo',
        ];

        $kota = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Semarang',
            'Yogyakarta',
            'Malang',
            'Solo',
            'Bogor',
            'Depok',
            'Tangerang',
        ];

        $mataPelajaranIds = MataPelajaran::pluck('id')->all();

        $usedNip = [];

        for ($i = 1; $i <= 20; $i++) {
            $nama = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];

            // Format NIP: tahun(8 digit) + bulan + jenis kelamin(1/2) + nomor urut(4 digit)
            do {
                $nip = date('Y') - rand(20, 40) . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT)
                    . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT)
                    . rand(1, 2)
                    . str_pad($i, 4, '0', STR_PAD_LEFT);
            } while (in_array($nip, $usedNip));

            $usedNip[] = $nip;

            Guru::create([
                'nip'               => $nip,
                'nama'              => $nama,
                'no_hp'             => '08' . rand(11, 99) . rand(1000000, 9999999),
                'alamat'            => 'Jl. ' . $namaBelakang[array_rand($namaBelakang)] . ' No. ' . rand(1, 99) . ', ' . $kota[array_rand($kota)],
                'mata_pelajaran_id' => count($mataPelajaranIds) > 0
                    ? $mataPelajaranIds[array_rand($mataPelajaranIds)]
                    : null,
            ]);
        }
    }
}
