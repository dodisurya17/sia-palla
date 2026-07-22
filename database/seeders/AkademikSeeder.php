<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\OrangTua;
use Illuminate\Database\Seeder;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::insert([
            ['nama_kelas' => 'X IPA 1', 'tingkat' => 'X', 'wali_kelas' => 'Bpk. Yohanes', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'X IPA 2', 'tingkat' => 'X', 'wali_kelas' => 'Ibu Ratna', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'X IPS 1', 'tingkat' => 'X', 'wali_kelas' => 'Bpk. Slamet', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XI IPA 1', 'tingkat' => 'XI', 'wali_kelas' => 'Ibu Maria', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XI IPA 2', 'tingkat' => 'XI', 'wali_kelas' => 'Bpk. Hendra', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XI IPS 1', 'tingkat' => 'XI', 'wali_kelas' => 'Ibu Dewi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XII IPA 1', 'tingkat' => 'XII', 'wali_kelas' => 'Bpk. Anton', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XII IPA 2', 'tingkat' => 'XII', 'wali_kelas' => 'Ibu Siti', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kelas' => 'XII IPS 1', 'tingkat' => 'XII', 'wali_kelas' => 'Bpk. Budi', 'created_at' => now(), 'updated_at' => now()],
        ]);

        MataPelajaran::insert([
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'BIG', 'nama_mapel' => 'Bahasa Inggris', 'kkm' => 72, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'FIS', 'nama_mapel' => 'Fisika', 'kkm' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'KIM', 'nama_mapel' => 'Kimia', 'kkm' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'BIO', 'nama_mapel' => 'Biologi', 'kkm' => 72, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'EKO', 'nama_mapel' => 'Ekonomi', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'SOS', 'nama_mapel' => 'Sosiologi', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'SEJ', 'nama_mapel' => 'Sejarah', 'kkm' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'PKN', 'nama_mapel' => 'Pendidikan Kewarganegaraan', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'PJK', 'nama_mapel' => 'Pendidikan Jasmani', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
            ['kode_mapel' => 'SBD', 'nama_mapel' => 'Seni Budaya', 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()],
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
