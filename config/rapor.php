<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Cetak Rapor
|--------------------------------------------------------------------------
| Nilai bisa diubah langsung di sini atau lewat file .env, contoh:
|   RAPOR_KOTA="Waikabubak"
|   RAPOR_KEPALA_SEKOLAH="Nama Kepala Sekolah, S.Pd."
|   RAPOR_NIP_KEPALA_SEKOLAH="1980xxxxxxxx"
|
| Kolom yang dikosongkan akan dicetak sebagai garis titik-titik
| sehingga bisa diisi manual setelah dicetak.
*/

return [
    'nama_sekolah'       => env('RAPOR_NAMA_SEKOLAH', 'SMA Katolik Palla'),
    'alamat'             => env('RAPOR_ALAMAT', ''),
    'kota'               => env('RAPOR_KOTA', ''),
    'kepala_sekolah'     => env('RAPOR_KEPALA_SEKOLAH', ''),
    'nip_kepala_sekolah' => env('RAPOR_NIP_KEPALA_SEKOLAH', ''),
];
