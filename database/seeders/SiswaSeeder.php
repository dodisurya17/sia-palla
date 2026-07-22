<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $namaSiswa = [
            ['nama' => 'Ahmad Fauzi Ramadhan', 'jk' => 'L'],
            ['nama' => 'Siti Nur Aisyah', 'jk' => 'P'],
            ['nama' => 'Muhammad Rizky Pratama', 'jk' => 'L'],
            ['nama' => 'Dewi Anggraini', 'jk' => 'P'],
            ['nama' => 'Bagas Setiawan', 'jk' => 'L'],
            ['nama' => 'Putri Wulandari', 'jk' => 'P'],
            ['nama' => 'Fajar Nugroho', 'jk' => 'L'],
            ['nama' => 'Anisa Rahmawati', 'jk' => 'P'],
            ['nama' => 'Rizky Aditya Saputra', 'jk' => 'L'],
            ['nama' => 'Nadia Kusuma Wardani', 'jk' => 'P'],
            ['nama' => 'Dimas Prasetyo', 'jk' => 'L'],
            ['nama' => 'Yuni Lestari', 'jk' => 'P'],
            ['nama' => 'Andi Kurniawan', 'jk' => 'L'],
            ['nama' => 'Rina Marlina', 'jk' => 'P'],
            ['nama' => 'Doni Setiadi', 'jk' => 'L'],
            ['nama' => 'Intan Permatasari', 'jk' => 'P'],
            ['nama' => 'Agus Salim', 'jk' => 'L'],
            ['nama' => 'Melati Sukma', 'jk' => 'P'],
            ['nama' => 'Hendra Gunawan', 'jk' => 'L'],
            ['nama' => 'Fitriani Azzahra', 'jk' => 'P'],
            ['nama' => 'Ilham Maulana', 'jk' => 'L'],
            ['nama' => 'Sari Indah Sari', 'jk' => 'P'],
            ['nama' => 'Yusuf Al Farizi', 'jk' => 'L'],
            ['nama' => 'Wulan Sari', 'jk' => 'P'],
            ['nama' => 'Bayu Firmansyah', 'jk' => 'L'],
        ];

        $tempatLahir = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Waikabubak',
            'Wewewa Utara',
            'Puu Potto',
            'Sumba Barat Daya',
            'Kupang',
            'Medan',
            'Semarang',
        ];

        $kelasIds = Kelas::pluck('id')->all();
        $orangTuaIds = OrangTua::pluck('id')->all();

        foreach ($namaSiswa as $index => $data) {
            $urut = $index + 1;

            Siswa::create([
                'nisn' => '00' . str_pad($urut, 3, '0', STR_PAD_LEFT) . rand(100, 999),
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jk'],
                'tempat_lahir' => $tempatLahir[array_rand($tempatLahir)],
                'tanggal_lahir' => now()->subYears(rand(12, 15))->subDays(rand(0, 365))->format('Y-m-d'),
                'alamat' => 'Jl. Contoh No. ' . rand(1, 99) . ', Desa Puu Potto',
                'kelas_id' => count($kelasIds) ? $kelasIds[array_rand($kelasIds)] : null,
                'orang_tua_id' => count($orangTuaIds) ? $orangTuaIds[array_rand($orangTuaIds)] : null,
            ]);
        }
    }
}
