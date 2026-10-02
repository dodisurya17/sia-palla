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
        'siswa_id', 'mata_pelajaran_id', 'guru_id', 'semester_id', 'semester',
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

    /**
     * Relasi ke tabel semester. Dinamai `periode` karena kolom string lama
     * `semester` (Ganjil/Genap) masih ada dan akan bentrok jika memakai nama yang sama.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    /**
     * Batasi data sesuai role: guru hanya nilai miliknya, orang tua hanya nilai anaknya.
     */
    public function scopeVisibleTo($query, $user)
    {
        if ($user->isGuru()) {
            return $query->where('guru_id', $user->guru_id);
        }

        if ($user->isOrangTua()) {
            return $query->whereIn('siswa_id', $user->orangTua->siswa()->pluck('id'));
        }

        return $query;
    }
}
