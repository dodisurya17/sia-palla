<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\NilaiAkademikController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('orang-tua', OrangTuaController::class)->parameters(['orang-tua' => 'orang_tua']);
    Route::resource('mata-pelajaran', MataPelajaranController::class)->parameters(['mata-pelajaran' => 'mata_pelajaran']);
    Route::resource('kelas', KelasController::class);
    Route::resource('nilai-akademik', NilaiAkademikController::class)->parameters(['nilai-akademik' => 'nilai_akademik']);

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
