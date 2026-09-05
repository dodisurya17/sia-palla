<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NilaiEkstrakurikuler;

class Ekstrakurikuler extends Model
{
    protected $fillable = [
        'nama_ekstrakurikuler',
        'jenis',
        'pembina',
        'jadwal',
    ];

    public function nilaiEkstrakurikuler()
    {
        return $this->hasMany(NilaiEkstrakurikuler::class);
    }
}
