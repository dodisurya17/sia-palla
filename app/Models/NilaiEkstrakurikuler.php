<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiEkstrakurikuler extends Model
{
    protected $fillable = [
        'siswa_id',
        'ekstrakurikuler_id',
        'semester_id',
        'semester',
        'nilai',
        'predikat',
        'keterangan',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(Ekstrakurikuler::class);
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
     * Orang tua hanya boleh melihat nilai anaknya. Admin & guru melihat semua.
     */
    public function scopeVisibleTo($query, $user)
    {
        if ($user->isOrangTua()) {
            return $query->whereIn('siswa_id', $user->orangTua->siswa()->pluck('id'));
        }

        return $query;
    }
}
