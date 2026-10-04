<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporCatatan extends Model
{
    protected $table = 'rapor_catatan';

    protected $fillable = [
        'siswa_id',
        'semester_id',
        'catatan_wali_kelas',
        'sakit',
        'izin',
        'alpa',
        'capaian',
        'keterangan_ekskul',
    ];

    protected $casts = [
        'capaian'           => 'array',
        'keterangan_ekskul' => 'array',
    ];
}
