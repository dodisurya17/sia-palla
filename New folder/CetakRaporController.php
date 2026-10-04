<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CetakRaporController extends Controller
{
    /**
     * Urutan mata pelajaran di rapor (berdasarkan kode_mapel).
     * Mapel di luar daftar ini ditaruh setelahnya, urut nama.
     */
    private const URUTAN_MAPEL = [
        'PAK', // Pendidikan Agama Katolik
        'PKN', // Pendidikan Kewarganegaraan
        'BIN', // Bahasa Indonesia
        'MTK', // Matematika
        'IPA', // Ilmu Pengetahuan Alam
        'IPS', // Ilmu Pengetahuan Sosial
        'BIG', // Bahasa Inggris
        'PJK', // Penjaskes
        'SBD', // Seni Budaya
        'INF', // Informatika
    ];

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

        $urutan = array_flip(self::URUTAN_MAPEL);

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
            ->sort(function ($a, $b) use ($urutan) {
                $pa = $urutan[strtoupper((string) $a->kode_mapel)] ?? 999;
                $pb = $urutan[strtoupper((string) $b->kode_mapel)] ?? 999;

                return $pa <=> $pb ?: strnatcasecmp($a->nama_mapel, $b->nama_mapel);
            })
            ->values();

        $namaSiswa = Str::title(Str::lower($siswa->nama));

        $rows = $nilai->map(function ($n, $i) use ($namaSiswa) {
            [$paham, $bimbingan] = $this->capaian($namaSiswa, $n);

            return [
                'no'         => $i + 1,
                'kode'       => $n->kode_mapel,
                'nama'       => $n->nama_mapel,
                'kkm'        => (int) $n->kkm,
                'tugas'      => $this->fmt($n->nilai_tugas),
                'uts'        => $this->fmt($n->nilai_uts),
                'uas'        => $this->fmt($n->nilai_uas),
                'akhir'      => $this->fmt($n->nilai_akhir),
                'tuntas'     => (float) $n->nilai_akhir >= (int) $n->kkm,
                'paham'      => $paham,
                'bimbingan'  => $bimbingan,
            ];
        })->all();

        $jumlahNilai = (float) $nilai->sum('nilai_akhir');
        $rataRata    = $nilai->isNotEmpty() ? $jumlahNilai / $nilai->count() : 0;
        $tuntas      = collect($rows)->where('tuntas', true)->count();

        $ekskul = DB::table('nilai_ekstrakurikulers as ne')
            ->join('ekstrakurikulers as e', 'e.id', '=', 'ne.ekstrakurikuler_id')
            ->where('ne.siswa_id', $siswa->id)
            ->where('ne.semester_id', $semester->id)
            ->orderBy('ne.id')
            ->get(['ne.ekstrakurikuler_id', 'e.nama_ekstrakurikuler as nama', 'ne.nilai', 'ne.predikat', 'ne.keterangan'])
            // Bila satu ekskul punya lebih dari satu nilai di semester yang sama, ambil yang terakhir diinput.
            ->keyBy('ekstrakurikuler_id')
            ->sortBy('nama', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->map(fn ($e) => (array) $e)
            ->all();

        $orangTua = $siswa->orang_tua_id
            ? DB::table('orang_tua')->where('id', $siswa->orang_tua_id)->value('nama')
            : null;

        $tanggalLahir = $siswa->tanggal_lahir
            ? Carbon::parse($siswa->tanggal_lahir)->locale('id')->translatedFormat('d F Y')
            : null;

        $ttl = trim(implode(', ', array_filter([$siswa->tempat_lahir, $tanggalLahir])));

        // Semester genap (II) -> rapor memuat kotak keputusan kenaikan kelas.
        $genap = (bool) preg_match('/genap|^ii$|^2$/i', trim((string) $semester->jenis));

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
                'fase'       => $this->fase($siswa->kelas?->tingkat ?? $siswa->kelas?->nama_kelas),
                'wali_kelas' => $siswa->kelas?->wali_kelas,
            ],
            'semester' => [
                'id'           => $semester->id,
                'tahun_ajaran' => $semester->tahun_ajaran,
                'jenis'        => $semester->jenis,
                'genap'        => $genap,
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

    /**
     * Susun kalimat capaian kompetensi dari nilai komponen (Tugas, UTS, UAS) terhadap KKM.
     * Mengembalikan [kalimat pemahaman, kalimat bimbingan].
     */
    private function capaian(string $nama, object $n): array
    {
        $kkm   = (int) $n->kkm;
        $mapel = $n->nama_mapel;

        $komponen = array_filter([
            'Tugas' => $n->nilai_tugas,
            'UTS'   => $n->nilai_uts,
            'UAS'   => $n->nilai_uas,
        ], fn ($v) => !is_null($v));

        $atas  = array_keys(array_filter($komponen, fn ($v) => (float) $v >= $kkm));
        $bawah = array_keys(array_filter($komponen, fn ($v) => (float) $v < $kkm));

        if ((float) $n->nilai_akhir >= $kkm) {
            $paham = "{$nama} menunjukkan pemahaman dalam seluruh capaian pembelajaran {$mapel}.";
        } elseif ($atas) {
            $paham = "{$nama} menunjukkan pemahaman dalam {$mapel} pada komponen " . $this->gabung($atas) . '.';
        } else {
            $paham = "{$nama} belum menunjukkan pemahaman yang memadai dalam {$mapel}.";
        }

        $bimbingan = $bawah
            ? "{$nama} membutuhkan bimbingan dalam {$mapel} pada komponen " . $this->gabung($bawah) . '.'
            : "{$nama} tidak membutuhkan bimbingan khusus dalam {$mapel}.";

        return [$paham, $bimbingan];
    }

    /** ['Tugas','UTS','UAS'] -> "Tugas, UTS dan UAS" */
    private function gabung(array $items): string
    {
        if (count($items) <= 1) {
            return (string) ($items[0] ?? '');
        }

        $last = array_pop($items);

        return implode(', ', $items) . ' dan ' . $last;
    }

    /** Fase Kurikulum Merdeka dari tingkat kelas (angka atau romawi): 7-9 -> D, 10 -> E, 11-12 -> F. */
    private function fase(?string $tingkat): string
    {
        if (!$tingkat || !preg_match('/^\s*(\d+|[IVX]+)/i', $tingkat, $m)) {
            return '-';
        }

        $romawi = ['I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5, 'VI' => 6,
                   'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];

        $angka = ctype_digit($m[1]) ? (int) $m[1] : ($romawi[strtoupper($m[1])] ?? 0);

        return match (true) {
            $angka >= 1 && $angka <= 2   => 'A',
            $angka >= 3 && $angka <= 4   => 'B',
            $angka >= 5 && $angka <= 6   => 'C',
            $angka >= 7 && $angka <= 9   => 'D',
            $angka === 10                => 'E',
            $angka >= 11 && $angka <= 12 => 'F',
            default                      => '-',
        };
    }

    /** 85.00 -> "85", 82.50 -> "82,5", 78.25 -> "78,25" */
    private function fmt($value): string
    {
        $text = number_format((float) $value, 2, ',', '.');

        return rtrim(rtrim($text, '0'), ',');
    }
}
