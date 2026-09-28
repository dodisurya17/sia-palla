<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $table = 'semester';

    protected $fillable = ['tahun_ajaran', 'jenis', 'status'];

    protected static function booted(): void
    {
        // Hanya boleh ada satu semester aktif.
        static::saved(function (Semester $semester) {
            if ($semester->status === 'aktif') {
                static::where('id', '!=', $semester->id)
                    ->where('status', 'aktif')
                    ->update(['status' => 'nonaktif']);
            }
        });
    }

    public function nilaiAkademik(): HasMany
    {
        return $this->hasMany(NilaiAkademik::class);
    }

    public function nilaiEkstrakurikuler(): HasMany
    {
        return $this->hasMany(NilaiEkstrakurikuler::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeUrutTerbaru($query)
    {
        return $query->orderByDesc('tahun_ajaran')->orderByDesc('jenis');
    }

    public static function current(): ?self
    {
        return static::aktif()->first();
    }

    public function getLabelAttribute(): string
    {
        return "{$this->jenis} {$this->tahun_ajaran}";
    }
}
