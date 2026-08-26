<?php
// database/seeders/RoleUserSeeder.php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@siapalla.my.id',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        Guru::whereDoesntHave('user')->get()->each(function (Guru $guru) {
            User::create([
                'name' => $guru->nama,
                'email' => $this->generateEmail($guru->nama, $guru->id),
                'password' => bcrypt('password'),
                'role' => User::ROLE_GURU,
                'guru_id' => $guru->id,
            ]);
        });

        OrangTua::whereDoesntHave('user')->get()->each(function (OrangTua $orangTua) {
            User::create([
                'name' => $orangTua->nama,
                'email' => $this->generateEmail($orangTua->nama, $orangTua->id),
                'password' => bcrypt('password'),
                'role' => User::ROLE_ORANG_TUA,
                'orang_tua_id' => $orangTua->id,
            ]);
        });
    }

    private function generateEmail(string $nama, string $suffixFallback): string
    {
        $base = strtolower(str_replace(' ', '.', $nama));
        $email = "{$base}@siapalla.my.id";
        $i = 1;

        while (User::where('email', $email)->exists()) {
            $email = "{$base}{$i}@siapalla.my.id";
            $i++;
        }

        return $email;
    }
}
