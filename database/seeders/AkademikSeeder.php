<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ekstrakurikuler;
use App\Models\OrangTua;
use Illuminate\Database\Seeder;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::insert([
            ['nama_kelas' => 'VII A', 'tingkat' => 'VII', 'wali_kelas' => 'Bpk. Yohanes', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'VII B', 'tingkat' => 'VII', 'wali_kelas' => 'Ibu Ratna', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'VII C', 'tingkat' => 'VII', 'wali_kelas' => 'Bpk. Slamet', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'VIII A', 'tingkat' => 'VIII', 'wali_kelas' => 'Ibu Maria', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'VIII B', 'tingkat' => 'VIII', 'wali_kelas' => 'Bpk. Hendra', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'VIII C', 'tingkat' => 'VIII', 'wali_kelas' => 'Ibu Dewi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'IX A', 'tingkat' => 'IX', 'wali_kelas' => 'Bpk. Anton', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'IX B', 'tingkat' => 'IX', 'wali_kelas' => 'Ibu Siti', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'IX C', 'tingkat' => 'IX', 'wali_kelas' => 'Bpk. Budi', 'created_at' => now(), 'updated_at' => now()],
        ]);

        MataPelajaran::insert([
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'BIG', 'nama_mapel' => 'Bahasa Inggris', 'kkm' => 72, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'IPA', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'kkm' => 72, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'IPS', 'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'PKN', 'nama_mapel' => 'Pendidikan Kewarganegaraan', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'PJK', 'nama_mapel' => 'Pendidikan Jasmani', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'SBD', 'nama_mapel' => 'Seni Budaya', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'PKY', 'nama_mapel' => 'Prakarya', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Ekstrakurikuler::insert([
            ['nama_ekstrakurikuler' => 'Pramuka', 'jenis' => 'wajib', 'pembina' => 'Bpk. Yohanes', 'jadwal' => 'Jumat, 14.00 - 16.00', 'created_at' => now(), 'updated_at' => now()],
            ['nama_ekstrakurikuler' => 'Bola Voli', 'jenis' => 'pilihan', 'pembina' => 'Bpk. Hendra', 'jadwal' => 'Sabtu, 08.00 - 10.00', 'created_at' => now(), 'updated_at' => now()],
            ['nama_ekstrakurikuler' => 'Bulutangkis', 'jenis' => 'pilihan', 'pembina' => 'Ibu Ratna', 'jadwal' => 'Sabtu, 10.00 - 12.00', 'created_at' => now(), 'updated_at' => now()],
            ['nama_ekstrakurikuler' => 'Sepak Bola', 'jenis' => 'pilihan', 'pembina' => 'Bpk. Slamet', 'jadwal' => 'Sabtu, 07.00 - 09.00', 'created_at' => now(), 'updated_at' => now()],
        ]);

        OrangTua::insert([
            ['nama' => 'Bapak Agus Setiawan', 'no_hp' => '081234567890', 'alamat' => 'Jl. Mawar No. 1, Jakarta', 'pekerjaan' => 'Wiraswasta', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ibu Sri Wahyuni', 'no_hp' => '081298765432', 'alamat' => 'Jl. Melati No. 5, Jakarta', 'pekerjaan' => 'PNS', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bapak Dedi Kurniawan', 'no_hp' => '082112345678', 'alamat' => 'Jl. Anggrek No. 12, Bekasi', 'pekerjaan' => 'Karyawan Swasta', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ibu Rina Marlina', 'no_hp' => '085611223344', 'alamat' => 'Jl. Kenanga No. 8, Depok', 'pekerjaan' => 'Guru', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bapak Hendra Gunawan', 'no_hp' => '087755667788', 'alamat' => 'Jl. Dahlia No. 3, Tangerang', 'pekerjaan' => 'Dokter', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ibu Fitriani', 'no_hp' => '089933445566', 'alamat' => 'Jl. Flamboyan No. 7, Bogor', 'pekerjaan' => 'Ibu Rumah Tangga', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bapak Joko Santoso', 'no_hp' => '081277889900', 'alamat' => 'Jl. Cempaka No. 15, Jakarta', 'pekerjaan' => 'Pedagang', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ibu Ani Suryani', 'no_hp' => '085566778899', 'alamat' => 'Jl. Teratai No. 2, Bekasi', 'pekerjaan' => 'Wiraswasta', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
