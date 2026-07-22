<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';

    protected $fillable = ['nama', 'no_hp', 'alamat', 'pekerjaan'];

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }
}
