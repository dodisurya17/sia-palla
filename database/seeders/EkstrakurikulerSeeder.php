<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use Illuminate\Database\Seeder;

class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        Ekstrakurikuler::insert([
            [
                'nama_ekstrakurikuler' => 'Pramuka',
                'jenis' => 'wajib',
                'pembina' => 'Bpk. Yohanes',
                'jadwal' => 'Jumat, 14.00 - 16.00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ekstrakurikuler' => 'Bola Voli',
                'jenis' => 'pilihan',
                'pembina' => 'Bpk. Hendra',
                'jadwal' => 'Sabtu, 08.00 - 10.00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ekstrakurikuler' => 'Bulutangkis',
                'jenis' => 'pilihan',
                'pembina' => 'Ibu Ratna',
                'jadwal' => 'Sabtu, 10.00 - 12.00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ekstrakurikuler' => 'Sepak Bola',
                'jenis' => 'pilihan',
                'pembina' => 'Bpk. Slamet',
                'jadwal' => 'Sabtu, 07.00 - 09.00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command?->info('4 data ekstrakurikuler berhasil dibuat.');
    }
}
