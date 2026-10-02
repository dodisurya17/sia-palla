<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\NilaiAkademik;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiAkademikController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->query('search');

        $semesterAktif = Semester::current();

        // Tampilan utama: 1 siswa = 1 baris (nilai per mapel diringkas, rinciannya ada di Profil Nilai Siswa).
        // visibleTo() sudah membatasi data: guru hanya nilai miliknya, orang tua hanya nilai anaknya.
        $nilaiAkademik = NilaiAkademik::query()
            ->visibleTo($user)
            ->join('siswa', 'siswa.id', '=', 'nilai_akademik.siswa_id')
            ->leftJoin('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->where('nilai_akademik.semester_id', $semesterAktif?->id ?? 0)
            ->when($search, function ($query, $search) use ($semesterAktif) {
                // Dibungkus satu grup agar OR tidak melepas filter semester & role.
                $query->where(function ($group) use ($search, $semesterAktif) {
                    $group->where('siswa.nama', 'like', "%{$search}%")
                        ->orWhere('siswa.nisn', 'like', "%{$search}%")
                        ->orWhere('kelas.nama_kelas', 'like', "%{$search}%")
                        // Siswa yang punya nilai pada mata pelajaran yang dicari.
                        ->orWhereIn('nilai_akademik.siswa_id', function ($sub) use ($search, $semesterAktif) {
                            $sub->select('na.siswa_id')
                                ->from('nilai_akademik as na')
                                ->join('mata_pelajaran as mp', 'mp.id', '=', 'na.mata_pelajaran_id')
                                ->where('na.semester_id', $semesterAktif?->id ?? 0)
                                ->where('mp.nama_mapel', 'like', "%{$search}%");
                        });
                });
            })
            ->select(
                'nilai_akademik.siswa_id',
                'siswa.nama as siswa_nama',
                'siswa.nisn as siswa_nisn',
                'kelas.nama_kelas as kelas_nama',
                DB::raw('COUNT(nilai_akademik.id) as jumlah_mapel'),
                DB::raw('ROUND(AVG(nilai_akademik.nilai_akhir), 2) as rata_rata')
            )
            ->groupBy('nilai_akademik.siswa_id', 'siswa.nama', 'siswa.nisn', 'kelas.nama_kelas')
            ->orderBy('siswa.nama')
            ->paginate(10, ['*'], 'page_akademik')
            ->withQueryString();

        // Tampilan Ekstrakurikuler: 1 siswa = 1 baris (rinciannya ada di Profil Ekstrakurikuler Siswa).
        // Hanya semester aktif; visibleTo() membatasi data sesuai role.
        $nilaiEkstrakurikuler = NilaiEkstrakurikuler::query()
            ->visibleTo($user)
            ->join('siswa', 'siswa.id', '=', 'nilai_ekstrakurikulers.siswa_id')
            ->leftJoin('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->where('nilai_ekstrakurikulers.semester_id', $semesterAktif?->id ?? 0)
            ->when($search, function ($query, $search) use ($semesterAktif) {
                // Dibungkus satu grup agar OR tidak melepas filter semester & role.
                $query->where(function ($group) use ($search, $semesterAktif) {
                    $group->where('siswa.nama', 'like', "%{$search}%")
                        ->orWhere('siswa.nisn', 'like', "%{$search}%")
                        ->orWhere('kelas.nama_kelas', 'like', "%{$search}%")
                        // Siswa yang mengikuti ekstrakurikuler yang dicari.
                        ->orWhereIn('nilai_ekstrakurikulers.siswa_id', function ($sub) use ($search, $semesterAktif) {
                            $sub->select('ne.siswa_id')
                                ->from('nilai_ekstrakurikulers as ne')
                                ->join('ekstrakurikulers as e', 'e.id', '=', 'ne.ekstrakurikuler_id')
                                ->where('ne.semester_id', $semesterAktif?->id ?? 0)
                                ->where('e.nama_ekstrakurikuler', 'like', "%{$search}%");
                        });
                });
            })
            ->select(
                'nilai_ekstrakurikulers.siswa_id',
                'siswa.nama as siswa_nama',
                'siswa.nisn as siswa_nisn',
                'kelas.nama_kelas as kelas_nama',
                DB::raw('COUNT(nilai_ekstrakurikulers.id) as jumlah_ekskul')
            )
            ->groupBy('nilai_ekstrakurikulers.siswa_id', 'siswa.nama', 'siswa.nisn', 'kelas.nama_kelas')
            ->orderBy('siswa.nama')
            ->paginate(10, ['*'], 'page_ekskul')
            ->withQueryString();

        // Ringkasan kegiatan per siswa (nama ekskul + nilai) untuk halaman yang sedang tampil saja.
        // Dua query terpisah (bukan GROUP_CONCAT) supaya aman di MySQL maupun SQLite.
        $ekskulPerSiswa = NilaiEkstrakurikuler::with('ekstrakurikuler')
            ->visibleTo($user)
            ->where('semester_id', $semesterAktif?->id ?? 0)
            ->whereIn('siswa_id', $nilaiEkstrakurikuler->pluck('siswa_id'))
            ->get()
            ->sortBy(fn ($n) => $n->ekstrakurikuler->nama_ekstrakurikuler ?? '')
            ->groupBy('siswa_id');

        return view('nilai-akademik.index', compact('nilaiAkademik', 'nilaiEkstrakurikuler', 'ekskulPerSiswa', 'semesterAktif'));
    }

    public function create()
    {
        $user = auth()->user();

        return view('nilai-akademik.create', [
            'siswa' => Siswa::orderBy('nama')->get(),
            'guru' => $user->isGuru() ? $user->guru()->get() : Guru::orderBy('nama')->get(),
            'mataPelajaran' => MataPelajaran::orderBy('nama_mapel')->get(),
            'semester' => Semester::urutTerbaru()->get(),
            'nilai' => new NilaiAkademik(['semester_id' => Semester::current()?->id]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $this->assertGuruBoundary($validated['guru_id']);
        $validated['nilai_akhir'] = $this->hitungNilaiAkhir($validated);
        $validated['semester'] = Semester::findOrFail($validated['semester_id'])->jenis;

        NilaiAkademik::create($validated);

        return redirect()->route('nilai-akademik.index')
            ->with('success', 'Data nilai akademik berhasil ditambahkan.');
    }

    /**
     * Profil Nilai Siswa: seluruh nilai mata pelajaran satu siswa pada semester aktif.
     * Semester sebelumnya dilihat lewat menu Riwayat Nilai.
     */
    public function siswa(Siswa $siswa)
    {
        $user = auth()->user();
        $semesterAktif = Semester::current();

        if ($user->isOrangTua() && !$user->orangTua->siswa()->where('id', $siswa->id)->exists()) {
            abort(403);
        }

        // visibleTo(): guru hanya melihat nilai yang ia input sendiri.
        $nilaiList = NilaiAkademik::with(['mataPelajaran', 'guru'])
            ->visibleTo($user)
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semesterAktif?->id ?? 0)
            ->get()
            ->sortBy(fn ($n) => $n->mataPelajaran->nama_mapel ?? '')
            ->values();

        if ($user->isGuru() && $nilaiList->isEmpty()) {
            abort(403);
        }

        $tertinggi = $nilaiList->sortByDesc('nilai_akhir')->first();
        $terendah = $nilaiList->sortBy('nilai_akhir')->first();

        $ringkasan = [
            'jumlah' => $nilaiList->count(),
            'rata_rata' => $nilaiList->isNotEmpty() ? round($nilaiList->avg('nilai_akhir'), 2) : null,
            'tertinggi' => $tertinggi,
            'terendah' => $terendah,
        ];

        $kelasNama = $siswa->kelas_id
            ? DB::table('kelas')->where('id', $siswa->kelas_id)->value('nama_kelas')
            : null;

        return view('nilai-akademik.siswa', compact('siswa', 'kelasNama', 'nilaiList', 'ringkasan', 'semesterAktif'));
    }

    /**
     * Profil Ekstrakurikuler Siswa: seluruh nilai ekstrakurikuler satu siswa pada semester aktif.
     * Semester sebelumnya dilihat lewat menu Riwayat Nilai.
     */
    public function siswaEkskul(Siswa $siswa)
    {
        $user = auth()->user();
        $semesterAktif = Semester::current();

        if ($user->isOrangTua() && !$user->orangTua->siswa()->where('id', $siswa->id)->exists()) {
            abort(403);
        }

        $nilaiList = NilaiEkstrakurikuler::with('ekstrakurikuler')
            ->visibleTo($user)
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semesterAktif?->id ?? 0)
            ->get()
            ->sortBy(fn ($n) => $n->ekstrakurikuler->nama_ekstrakurikuler ?? '')
            ->values();

        if ($user->isGuru() && $nilaiList->isEmpty()) {
            abort(403);
        }

        // Nilai huruf: A terbaik, E terendah. Urutan abjad = urutan kualitas.
        $ringkasan = [
            'jumlah' => $nilaiList->count(),
            'wajib' => $nilaiList->filter(fn ($n) => ($n->ekstrakurikuler->jenis ?? null) === 'wajib')->count(),
            'pilihan' => $nilaiList->filter(fn ($n) => ($n->ekstrakurikuler->jenis ?? null) === 'pilihan')->count(),
            'tertinggi' => $nilaiList->sortBy('nilai')->first(),
            'terendah' => $nilaiList->sortByDesc('nilai')->first(),
        ];

        // Edit/hapus nilai ekstrakurikuler hanya tersedia untuk admin (lihat web.php).
        $bisaKelola = !$user->isGuru() && !$user->isOrangTua();

        $kelasNama = $siswa->kelas_id
            ? DB::table('kelas')->where('id', $siswa->kelas_id)->value('nama_kelas')
            : null;

        return view('nilai-akademik.siswa-ekskul', compact('siswa', 'kelasNama', 'nilaiList', 'ringkasan', 'semesterAktif', 'bisaKelola'));
    }

    public function show(Request $request, NilaiAkademik $nilai_akademik)
    {
        $this->assertViewBoundary($nilai_akademik);

        $nilai_akademik->load(['siswa', 'mataPelajaran', 'guru', 'periode']);

        if ($request->ajax() || $request->wantsJson()) {
            return view('nilai-akademik.partials.detail', ['nilai' => $nilai_akademik]);
        }

        return view('nilai-akademik.show', ['nilai' => $nilai_akademik]);
    }

    public function edit(NilaiAkademik $nilai_akademik)
    {
        $this->assertGuruBoundary($nilai_akademik->guru_id);

        $user = auth()->user();

        return view('nilai-akademik.edit', [
            'nilai' => $nilai_akademik,
            'siswa' => Siswa::orderBy('nama')->get(),
            'guru' => $user->isGuru() ? $user->guru()->get() : Guru::orderBy('nama')->get(),
            'mataPelajaran' => MataPelajaran::orderBy('nama_mapel')->get(),
            'semester' => Semester::urutTerbaru()->get(),
        ]);
    }

    public function update(Request $request, NilaiAkademik $nilai_akademik)
    {
        $this->assertGuruBoundary($nilai_akademik->guru_id);

        $validated = $this->validateData($request);
        $this->assertGuruBoundary($validated['guru_id']);
        $validated['nilai_akhir'] = $this->hitungNilaiAkhir($validated);
        $validated['semester'] = Semester::findOrFail($validated['semester_id'])->jenis;

        $nilai_akademik->update($validated);

        return redirect()->route('nilai-akademik.index')
            ->with('success', 'Data nilai akademik berhasil diperbarui.');
    }

    public function destroy(NilaiAkademik $nilai_akademik)
    {
        $this->assertGuruBoundary($nilai_akademik->guru_id);

        $nilai_akademik->delete();

        return redirect()->route('nilai-akademik.index')
            ->with('success', 'Data nilai akademik berhasil dihapus.');
    }

    /**
     * Validasi input form nilai akademik.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'guru_id' => ['required', 'exists:guru,id'],
            'semester_id' => ['required', 'exists:semester,id'],
            'nilai_tugas' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_uts' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_uas' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
    }

    /**
     * Hitung nilai akhir: 30% tugas, 30% UTS, 40% UAS.
     */
    private function hitungNilaiAkhir(array $data): float
    {
        return round(
            ($data['nilai_tugas'] * 0.3) + ($data['nilai_uts'] * 0.3) + ($data['nilai_uas'] * 0.4),
            2
        );
    }

    /**
     * Guru hanya boleh input/ubah/hapus nilai atas namanya sendiri.
     */
    private function assertGuruBoundary(int $guruId): void
    {
        $user = auth()->user();

        if ($user->isGuru() && $user->guru_id !== $guruId) {
            abort(403, 'Anda hanya bisa mengelola nilai yang Anda input sendiri.');
        }
    }

    /**
     * Batasi akses show(): guru hanya nilai miliknya, orang tua hanya nilai anaknya.
     */
    private function assertViewBoundary(NilaiAkademik $nilai): void
    {
        $user = auth()->user();

        if ($user->isGuru() && $user->guru_id !== $nilai->guru_id) {
            abort(403);
        }

        if ($user->isOrangTua() && !$user->orangTua->siswa()->where('id', $nilai->siswa_id)->exists()) {
            abort(403);
        }
    }
}
