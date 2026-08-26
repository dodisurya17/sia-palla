<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\NilaiAkademik;
use App\Models\OrangTua;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isGuru()) {
            return $this->guru($user);
        }

        if ($user->isOrangTua()) {
            return $this->orangTua($user);
        }

        return $this->admin();
    }

    private function admin()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalOrangTua = OrangTua::count();
        $totalKelas = Kelas::count();
        $totalMapel = MataPelajaran::count();

        return view('dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalOrangTua',
            'totalKelas',
            'totalMapel'
        ));
    }

    private function guru($user)
    {
        $guru = $user->guru;

        abort_if(!$guru, 403, 'Akun ini belum terhubung ke data guru.');

        $totalNilaiDiinput = NilaiAkademik::where('guru_id', $guru->id)->count();

        return view('dashboard-guru', compact('guru', 'totalNilaiDiinput'));
    }

    private function orangTua($user)
    {
        $orangTua = $user->orangTua;

        abort_if(!$orangTua, 403, 'Akun ini belum terhubung ke data orang tua.');

        $orangTua->load('siswa.kelas');

        return view('dashboard-orang-tua', compact('orangTua'));
    }
}
