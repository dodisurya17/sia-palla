<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nisn', 'nama', 'jenis_kelamin', 'tempat_lahir',
        'tanggal_lahir', 'alamat', 'kelas_id', 'orang_tua_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function nilaiAkademik(): HasMany
    {
        return $this->hasMany(NilaiAkademik::class);
    }
}
