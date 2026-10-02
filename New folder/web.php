<?php

use App\Http\Controllers\CetakRaporController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\NilaiAkademikController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\RiwayatNilaiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Khusus admin: kelola data master
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('guru', GuruController::class)->except(['show']);
        Route::resource('orang-tua', OrangTuaController::class)->except(['show'])->parameters(['orang-tua' => 'orang_tua']);
        Route::resource('mata-pelajaran', MataPelajaranController::class)->parameters(['mata-pelajaran' => 'mata_pelajaran']);
        Route::resource('ekstrakurikuler', EkstrakurikulerController::class);
        Route::prefix('nilai-ekstrakurikuler')->name('nilai-ekstrakurikuler.')->group(function () {
            Route::get('/create', [EkstrakurikulerController::class, 'nilaiCreate'])->name('create');
            Route::post('/', [EkstrakurikulerController::class, 'nilaiStore'])->name('store');
            Route::get('/{nilai}', [EkstrakurikulerController::class, 'nilaiShow'])->name('show');
            Route::get('/{nilai}/edit', [EkstrakurikulerController::class, 'nilaiEdit'])->name('edit');
            Route::put('/{nilai}', [EkstrakurikulerController::class, 'nilaiUpdate'])->name('update');
            Route::delete('/{nilai}', [EkstrakurikulerController::class, 'nilaiDestroy'])->name('destroy');
        });
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::post('/guru/{guru}/buat-akun', [GuruController::class, 'buatAkun'])->name('guru.buat-akun');
    });

    // Admin, guru, orang tua: riwayat nilai & profil nilai siswa (discope di controller).
    // Didaftarkan sebelum resource nilai-akademik supaya tidak tertimpa wildcard.
    Route::middleware(['role:admin,guru,orang_tua'])->group(function () {
        Route::get('/riwayat-nilai', [RiwayatNilaiController::class, 'index'])->name('riwayat-nilai.index');
        Route::get('/nilai-siswa/{siswa}', [RiwayatNilaiController::class, 'siswa'])->name('nilai-siswa.show');

        // Profil Nilai Siswa (semester aktif): 1 siswa, seluruh nilai mata pelajaran. Discope di controller.
        Route::get('/nilai-akademik/siswa/{siswa}', [NilaiAkademikController::class, 'siswa'])->name('nilai-akademik.siswa');

        // Cetak rapor: pilih siswa -> pilih semester -> preview -> cetak / PDF (discope di controller).
        Route::get('/cetak-rapor', [CetakRaporController::class, 'index'])->name('cetak-rapor.index');
        Route::get('/cetak-rapor/cetak', [CetakRaporController::class, 'cetak'])->name('cetak-rapor.print');
    });

    // Admin + guru: input & lihat nilai
    Route::middleware(['role:admin,guru'])->group(function () {
        Route::resource('nilai-akademik', NilaiAkademikController::class)->parameters(['nilai-akademik' => 'nilai_akademik']);
    });

    // Admin, guru, orang tua: lihat nilai (masing-masing sudah discope di controller)
    Route::middleware(['role:admin,guru,orang_tua'])->group(function () {
        Route::get('/nilai-akademik', [NilaiAkademikController::class, 'index'])->name('nilai-akademik.index');
        Route::get('/nilai-akademik/{nilai_akademik}', [NilaiAkademikController::class, 'show'])->name('nilai-akademik.show');
    });

    // Admin & guru: kelola nilai
    Route::middleware(['role:admin,guru'])->group(function () {
        Route::get('/nilai-akademik/create', [NilaiAkademikController::class, 'create'])->name('nilai-akademik.create');
        Route::post('/nilai-akademik', [NilaiAkademikController::class, 'store'])->name('nilai-akademik.store');
        Route::get('/nilai-akademik/{nilai_akademik}/edit', [NilaiAkademikController::class, 'edit'])->name('nilai-akademik.edit');
        Route::put('/nilai-akademik/{nilai_akademik}', [NilaiAkademikController::class, 'update'])->name('nilai-akademik.update');
        Route::delete('/nilai-akademik/{nilai_akademik}', [NilaiAkademikController::class, 'destroy'])->name('nilai-akademik.destroy');
    });

    // show guru & orang-tua boleh diakses admin, guru sendiri, atau orang tua sendiri
    // (scoping-nya di controller, lihat bagian 6 & 7)
    Route::middleware(['role:admin,guru'])->get('/guru/{guru}', [GuruController::class, 'show'])->name('guru.show');
    Route::middleware(['role:admin,orang_tua'])->get('/orang-tua/{orang_tua}', [OrangTuaController::class, 'show'])->name('orang-tua.show');
});

require __DIR__ . '/auth.php';
