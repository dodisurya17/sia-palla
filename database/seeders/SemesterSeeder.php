<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::firstOrCreate(['tahun_ajaran' => '2025/2026', 'jenis' => 'Ganjil'], ['status' => 'nonaktif']);
        Semester::firstOrCreate(['tahun_ajaran' => '2025/2026', 'jenis' => 'Genap'], ['status' => 'nonaktif']);
        Semester::firstOrCreate(['tahun_ajaran' => '2026/2027', 'jenis' => 'Ganjil'], ['status' => 'aktif']);
    }
}
