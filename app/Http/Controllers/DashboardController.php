<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\OrangTua;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalOrangTua = OrangTua::count();
        $totalKelas = Kelas::count();
        $totalMapel = MataPelajaran::count();

        return view('dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalOrangTua', 'totalKelas', 'totalMapel'
        ));
    }
}
