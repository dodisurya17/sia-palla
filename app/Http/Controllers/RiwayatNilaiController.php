<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\NilaiAkademik;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatNilaiController extends Controller
{
    /**
     * Riwayat Nilai: daftar semester sebelumnya (non-aktif). Setelah user memilih
     * semester, tampilkan nilai akademik atau ekstrakurikuler pada semester tersebut.
     *
     * Data ditampilkan per siswa (1 siswa = 1 baris), sama seperti halaman Nilai Akademik.
     * Filter yang tersedia: kelas dan mata pelajaran (atau ekstrakurikuler pada tab ekskul).
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

        $kelasId = $request->filled('kelas_id') ? (int) $request->query('kelas_id') : null;
        $itemId = $request->filled('item_id') ? (int) $request->query('item_id') : null; // mapel / ekskul

        $nilai = null;
        $ekskulPerSiswa = collect();
        $kelasOptions = collect();
        $itemOptions = collect();
        $mapelTerpilih = null;

        if ($semesterDipilih) {
            $siswaTable = (new Siswa)->getTable();

            if ($tab === 'ekstrakurikuler') {
                $nilaiTable = (new NilaiEkstrakurikuler)->getTable();

                // Scope dasar (semester + hak akses user), dipakai ulang untuk opsi filter.
                $base = fn () => NilaiEkstrakurikuler::visibleTo($user)->where('semester_id', $semesterDipilih->id);

                $filter = function ($query) use ($search, $kelasId, $itemId) {
                    $query
                        ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $kelasId)))
                        ->when($itemId, fn ($q) => $q->where('ekstrakurikuler_id', $itemId))
                        ->when($search, function ($q, $search) {
                            $q->where(function ($group) use ($search) {
                                $group->whereHas('siswa', function ($s) use ($search) {
                                    $s->where('nama', 'like', "%{$search}%")
                                        ->orWhere('nisn', 'like', "%{$search}%")
                                        ->orWhereHas('kelas', fn ($k) => $k->where('nama_kelas', 'like', "%{$search}%"));
                                })->orWhereHas('ekstrakurikuler', function ($e) use ($search) {
                                    $e->where('nama_ekstrakurikuler', 'like', "%{$search}%");
                                });
                            });
                        });
                };

                $nilai = $base()
                    ->tap($filter)
                    ->select('siswa_id', DB::raw('COUNT(*) as jumlah_item'))
                    ->groupBy('siswa_id')
                    ->orderBy(Siswa::select('nama')->whereColumn("{$siswaTable}.id", "{$nilaiTable}.siswa_id"))
                    ->with('siswa.kelas')
                    ->paginate(15)
                    ->withQueryString();

                // Ambil detail ekskul untuk siswa yang tampil di halaman ini.
                $ekskulPerSiswa = $base()
                    ->tap($filter)
                    ->with('ekstrakurikuler')
                    ->whereIn('siswa_id', $nilai->pluck('siswa_id'))
                    ->get()
                    ->groupBy('siswa_id');

                $itemOptions = Ekstrakurikuler::whereIn('id', $base()->select('ekstrakurikuler_id'))
                    ->orderBy('nama_ekstrakurikuler')
                    ->get(['id', 'nama_ekstrakurikuler as label']);

                $kelasOptions = Kelas::whereIn('id', Siswa::whereIn('id', $base()->select('siswa_id'))->select('kelas_id'))
                    ->orderBy('nama_kelas')
                    ->get();
            } else {
                $nilaiTable = (new NilaiAkademik)->getTable();

                $base = fn () => NilaiAkademik::visibleTo($user)->where('semester_id', $semesterDipilih->id);

                $nilai = $base()
                    ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $kelasId)))
                    ->when($itemId, fn ($q) => $q->where('mata_pelajaran_id', $itemId))
                    ->when($search, function ($query, $search) {
                        $query->where(function ($group) use ($search) {
                            $group->whereHas('siswa', function ($s) use ($search) {
                                $s->where('nama', 'like', "%{$search}%")
                                    ->orWhere('nisn', 'like', "%{$search}%")
                                    ->orWhereHas('kelas', fn ($k) => $k->where('nama_kelas', 'like', "%{$search}%"));
                            })->orWhereHas('mataPelajaran', function ($m) use ($search) {
                                $m->where('nama_mapel', 'like', "%{$search}%");
                            });
                        });
                    })
                    ->select(
                        'siswa_id',
                        DB::raw('ROUND(AVG(nilai_akhir), 2) as rata_rata'),
                        DB::raw('COUNT(*) as jumlah_mapel')
                    )
                    ->groupBy('siswa_id')
                    ->orderBy(Siswa::select('nama')->whereColumn("{$siswaTable}.id", "{$nilaiTable}.siswa_id"))
                    ->with('siswa.kelas')
                    ->paginate(15)
                    ->withQueryString();

                $itemOptions = MataPelajaran::whereIn('id', $base()->select('mata_pelajaran_id'))
                    ->orderBy('nama_mapel')
                    ->get(['id', 'nama_mapel as label']);

                $kelasOptions = Kelas::whereIn('id', Siswa::whereIn('id', $base()->select('siswa_id'))->select('kelas_id'))
                    ->orderBy('nama_kelas')
                    ->get();

                if ($itemId) {
                    $mapelTerpilih = MataPelajaran::find($itemId);
                }
            }
        }

        $filterAktif = filled($search) || $kelasId || $itemId;

        return view('riwayat-nilai.index', compact(
            'semesterList',
            'semesterDipilih',
            'nilai',
            'tab',
            'ekskulPerSiswa',
            'kelasOptions',
            'itemOptions',
            'kelasId',
            'itemId',
            'mapelTerpilih',
            'filterAktif'
        ));
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
