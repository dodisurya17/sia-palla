<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakRaporController extends Controller
{
    /**
     * Halaman utama: pilih siswa -> pilih semester -> preview rapor.
     */
    public function index(Request $request)
    {
        $request->validate([
            'siswa_id'    => ['nullable', 'integer'],
            'semester_id' => ['nullable', 'integer'],
        ]);

        $siswaList = $this->siswaQuery()->with('kelas')->orderBy('nama')->get();

        $siswaOptions = $siswaList->map(fn ($s) => [
            'id'       => $s->id,
            'nama'     => $s->nama,
            'nisn'     => $s->nisn,
            'kelas_id' => $s->kelas_id,
            'kelas'    => $s->kelas?->nama_kelas ?? 'Tanpa kelas',
        ])->values();

        $kelasOptions = $siswaList->pluck('kelas')->filter()->unique('id')
            ->sortBy('nama_kelas', SORT_NATURAL | SORT_FLAG_CASE)
            ->map(fn ($k) => ['id' => $k->id, 'nama' => $k->nama_kelas])
            ->values();

        $semesterOptions = DB::table('semester')
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('jenis')
            ->get()
            ->map(fn ($s) => [
                'id'    => $s->id,
                'label' => $s->tahun_ajaran . ' · ' . $s->jenis,
                'aktif' => $s->status === 'aktif',
            ])->values();

        // Semester mana saja yang punya nilai untuk tiap siswa (supaya dropdown semester relevan).
        $semesterPerSiswa = DB::table('nilai_akademik')
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->whereNotNull('semester_id')
            ->select('siswa_id', 'semester_id')
            ->distinct()
            ->get()
            ->groupBy('siswa_id')
            ->map(fn ($rows) => $rows->pluck('semester_id')->values())
            ->all();

        $rapor = null;
        if ($request->filled('siswa_id') && $request->filled('semester_id')) {
            $rapor = $this->buildRapor((int) $request->siswa_id, (int) $request->semester_id);
        }

        return view('cetak-rapor.index', [
            'siswaOptions'     => $siswaOptions,
            'kelasOptions'     => $kelasOptions,
            'semesterOptions'  => $semesterOptions,
            'semesterPerSiswa' => $semesterPerSiswa,
            'selectedSiswa'    => $request->filled('siswa_id') ? (string) $request->siswa_id : '',
            'selectedSemester' => $request->filled('semester_id') ? (string) $request->semester_id : '',
            'rapor'            => $rapor,
        ]);
    }

    /**
     * Halaman cetak (A4, tanpa sidebar). Dialog print terbuka otomatis;
     * pilih "Save as PDF" di dialog tersebut untuk mengunduh PDF.
     */
    public function cetak(Request $request)
    {
        $data = $request->validate([
            'siswa_id'    => ['required', 'integer'],
            'semester_id' => ['required', 'integer'],
        ]);

        $rapor = $this->buildRapor((int) $data['siswa_id'], (int) $data['semester_id']);

        if ($rapor['kosong']) {
            return redirect()
                ->route('cetak-rapor.index', $data)
                ->with('error', 'Belum ada nilai akademik untuk siswa dan semester ini, rapor tidak bisa dicetak.');
        }

        return view('cetak-rapor.print', compact('rapor'));
    }

    /**
     * Admin & guru: semua siswa. Orang tua: hanya anaknya sendiri.
     */
    private function siswaQuery()
    {
        $user  = auth()->user();
        $query = Siswa::query();

        if ($user->isOrangTua()) {
            abort_if(!$user->orang_tua_id, 403, 'Akun ini belum terhubung ke data orang tua.');
            $query->where('orang_tua_id', $user->orang_tua_id);
        }

        return $query;
    }

    private function buildRapor(int $siswaId, int $semesterId): array
    {
        $siswa    = $this->siswaQuery()->with('kelas')->findOrFail($siswaId);
        $semester = DB::table('semester')->where('id', $semesterId)->first();
        abort_if(!$semester, 404);

        // Bila ada duplikat nilai untuk mapel yang sama, ambil yang terakhir diinput.
        $nilai = DB::table('nilai_akademik as n')
            ->join('mata_pelajaran as m', 'm.id', '=', 'n.mata_pelajaran_id')
            ->where('n.siswa_id', $siswa->id)
            ->where('n.semester_id', $semester->id)
            ->orderBy('n.id')
            ->get([
                'n.mata_pelajaran_id', 'm.kode_mapel', 'm.nama_mapel', 'm.kkm',
                'n.nilai_tugas', 'n.nilai_uts', 'n.nilai_uas', 'n.nilai_akhir',
            ])
            ->keyBy('mata_pelajaran_id')
            ->sortBy('nama_mapel', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $rows = $nilai->map(fn ($n, $i) => [
            'no'     => $i + 1,
            'kode'   => $n->kode_mapel,
            'nama'   => $n->nama_mapel,
            'kkm'    => (int) $n->kkm,
            'tugas'  => $this->fmt($n->nilai_tugas),
            'uts'    => $this->fmt($n->nilai_uts),
            'uas'    => $this->fmt($n->nilai_uas),
            'akhir'  => $this->fmt($n->nilai_akhir),
            'tuntas' => (float) $n->nilai_akhir >= (int) $n->kkm,
        ])->all();

        $jumlahNilai = (float) $nilai->sum('nilai_akhir');
        $rataRata    = $nilai->isNotEmpty() ? $jumlahNilai / $nilai->count() : 0;
        $tuntas      = collect($rows)->where('tuntas', true)->count();

        $ekskul = DB::table('nilai_ekstrakurikulers as ne')
            ->join('ekstrakurikulers as e', 'e.id', '=', 'ne.ekstrakurikuler_id')
            ->where('ne.siswa_id', $siswa->id)
            ->where('ne.semester_id', $semester->id)
            ->orderBy('e.nama_ekstrakurikuler')
            ->get(['e.nama_ekstrakurikuler as nama', 'ne.nilai', 'ne.predikat', 'ne.keterangan'])
            ->map(fn ($e) => (array) $e)
            ->all();

        $orangTua = $siswa->orang_tua_id
            ? DB::table('orang_tua')->where('id', $siswa->orang_tua_id)->value('nama')
            : null;

        $tanggalLahir = $siswa->tanggal_lahir
            ? Carbon::parse($siswa->tanggal_lahir)->locale('id')->translatedFormat('d F Y')
            : null;

        $ttl = trim(implode(', ', array_filter([$siswa->tempat_lahir, $tanggalLahir])));

        return [
            'kosong'  => count($rows) === 0,
            'sekolah' => [
                'nama'           => config('rapor.nama_sekolah'),
                'alamat'         => config('rapor.alamat'),
                'kota'           => config('rapor.kota'),
                'kepala_sekolah' => config('rapor.kepala_sekolah'),
                'nip_kepsek'     => config('rapor.nip_kepala_sekolah'),
            ],
            'siswa' => [
                'id'            => $siswa->id,
                'nama'          => $siswa->nama,
                'nisn'          => $siswa->nisn,
                'jenis_kelamin' => $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'ttl'           => $ttl !== '' ? $ttl : null,
                'alamat'        => $siswa->alamat,
                'orang_tua'     => $orangTua,
            ],
            'kelas' => [
                'nama'       => $siswa->kelas?->nama_kelas,
                'tingkat'    => $siswa->kelas?->tingkat,
                'wali_kelas' => $siswa->kelas?->wali_kelas,
            ],
            'semester' => [
                'id'           => $semester->id,
                'tahun_ajaran' => $semester->tahun_ajaran,
                'jenis'        => $semester->jenis,
            ],
            'nilai'     => $rows,
            'ringkasan' => [
                'jumlah_mapel' => count($rows),
                'jumlah_nilai' => $this->fmt($jumlahNilai),
                'rata_rata'    => $this->fmt(round($rataRata, 2)),
                'tuntas'       => $tuntas,
                'belum_tuntas' => count($rows) - $tuntas,
            ],
            'ekskul'        => $ekskul,
            'tanggal_cetak' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];
    }

    /** 85.00 -> "85", 82.50 -> "82,5", 78.25 -> "78,25" */
    private function fmt($value): string
    {
        $text = number_format((float) $value, 2, ',', '.');

        return rtrim(rtrim($text, '0'), ',');
    }
}
