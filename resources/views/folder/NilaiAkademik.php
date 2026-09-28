<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiAkademik extends Model
{
    use HasFactory;

    protected $table = 'nilai_akademik';

    protected $fillable = [
        'siswa_id', 'mata_pelajaran_id', 'guru_id', 'semester',
        'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
