<?php

namespace App\Http\Controllers;

use App\Models\NilaiAkademik;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RiwayatNilaiController extends Controller
{
    /**
     * Riwayat Nilai: daftar semester sebelumnya (non-aktif). Setelah user memilih
     * semester, tampilkan nilai akademik atau ekstrakurikuler pada semester tersebut.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->query('search');

        $semesterList = Semester::where('status', '!=', 'aktif')->urutTerbaru()->get();

        $semesterDipilih = null;
        if ($request->filled('semester_id')) {
            $semesterDipilih = $semesterList->firstWhere('id', (int) $request->query('semester_id'));
            abort_if(!$semesterDipilih, 404);
        }

        // Tab: akademik (default) atau ekstrakurikuler.
        $tab = $request->query('tab') === 'ekstrakurikuler' ? 'ekstrakurikuler' : 'akademik';

        $nilai = null;
        if ($semesterDipilih) {
            if ($tab === 'ekstrakurikuler') {
                $nilai = NilaiEkstrakurikuler::with(['siswa.kelas', 'ekstrakurikuler'])
                    ->visibleTo($user)
                    ->where('semester_id', $semesterDipilih->id)
                    ->when($search, function ($query, $search) {
                        $query->where(function ($group) use ($search) {
                            $group->whereHas('siswa', function ($q) use ($search) {
                                $q->where('nama', 'like', "%{$search}%");
                            })->orWhereHas('ekstrakurikuler', function ($q) use ($search) {
                                $q->where('nama_ekstrakurikuler', 'like', "%{$search}%");
                            });
                        });
                    })
                    ->orderBy('siswa_id')
                    ->orderBy('ekstrakurikuler_id')
                    ->paginate(15)
                    ->withQueryString();
            } else {
                $nilai = NilaiAkademik::with(['siswa.kelas', 'mataPelajaran'])
                    ->visibleTo($user)
                    ->where('semester_id', $semesterDipilih->id)
                    ->when($search, function ($query, $search) {
                        $query->where(function ($group) use ($search) {
                            $group->whereHas('siswa', function ($q) use ($search) {
                                $q->where('nama', 'like', "%{$search}%");
                            })->orWhereHas('mataPelajaran', function ($q) use ($search) {
                                $q->where('nama_mapel', 'like', "%{$search}%");
                            });
                        });
                    })
                    ->orderBy('siswa_id')
                    ->orderBy('mata_pelajaran_id')
                    ->paginate(15)
                    ->withQueryString();
            }
        }

        return view('riwayat-nilai.index', compact('semesterList', 'semesterDipilih', 'nilai', 'tab'));
    }

    /**
     * Detail/Profil nilai satu siswa pada satu semester (default: semester aktif).
     */
    public function siswa(Request $request, Siswa $siswa)
    {
        $user = auth()->user();

        if ($user->isOrangTua() && !$user->orangTua->siswa()->where('id', $siswa->id)->exists()) {
            abort(403);
        }

        $siswa->load('kelas');

        // Semester yang punya nilai siswa ini (akademik atau ekstrakurikuler, dalam batas akses user).
        $semesterIds = NilaiAkademik::visibleTo($user)
            ->where('siswa_id', $siswa->id)
            ->pluck('semester_id')
            ->merge(
                NilaiEkstrakurikuler::visibleTo($user)
                    ->where('siswa_id', $siswa->id)
                    ->pluck('semester_id')
            )
            ->filter()
            ->unique()
            ->values();

        $semesterList = Semester::whereIn('id', $semesterIds)->urutTerbaru()->get();

        if ($request->filled('semester_id')) {
            $semester = $semesterList->firstWhere('id', (int) $request->query('semester_id'));
            abort_if(!$semester, 404);
        } else {
            $semester = Semester::current();
        }

        $nilai = collect();
        $nilaiEkskul = collect();
        if ($semester) {
            $nilai = NilaiAkademik::with(['mataPelajaran', 'guru'])
                ->visibleTo($user)
                ->where('siswa_id', $siswa->id)
                ->where('semester_id', $semester->id)
                ->get()
                ->sortBy(fn ($n) => $n->mataPelajaran->nama_mapel ?? '')
                ->values();

            $nilaiEkskul = NilaiEkstrakurikuler::with('ekstrakurikuler')
                ->visibleTo($user)
                ->where('siswa_id', $siswa->id)
                ->where('semester_id', $semester->id)
                ->get()
                ->sortBy(fn ($n) => $n->ekstrakurikuler->nama_ekstrakurikuler ?? '')
                ->values();
        }

        $rataRata = $nilai->isNotEmpty() ? round($nilai->avg('nilai_akhir'), 2) : null;

        return view('riwayat-nilai.siswa', compact('siswa', 'semester', 'semesterList', 'nilai', 'nilaiEkskul', 'rataRata'));
    }
}
