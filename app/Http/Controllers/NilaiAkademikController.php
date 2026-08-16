<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\NilaiAkademik;
use App\Models\Siswa;
use Illuminate\Http\Request;

class NilaiAkademikController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $nilaiAkademik = NilaiAkademik::with(['siswa', 'mataPelajaran', 'guru'])
            ->when($search, function ($query, $search) {
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('mataPelajaran', function ($q) use ($search) {
                    $q->where('nama_mapel', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('nilai-akademik.index', compact('nilaiAkademik'));
    }

    public function create()
    {
        return view('nilai-akademik.create', [
            'siswa' => Siswa::orderBy('nama')->get(),
            'guru' => Guru::orderBy('nama')->get(),
            'mataPelajaran' => MataPelajaran::orderBy('nama_mapel')->get(),
            'nilai' => new NilaiAkademik(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $validated['nilai_akhir'] = $this->hitungNilaiAkhir($validated);

        NilaiAkademik::create($validated);

        return redirect()->route('nilai-akademik.index')
            ->with('success', 'Data nilai akademik berhasil ditambahkan.');
    }

    public function show(NilaiAkademik $nilai_akademik)
    {
        $nilai_akademik->load(['siswa', 'mataPelajaran', 'guru']);

        return view('nilai-akademik.show', ['nilai' => $nilai_akademik]);
    }

    public function edit(NilaiAkademik $nilai_akademik)
    {
        return view('nilai-akademik.edit', [
            'nilai' => $nilai_akademik,
            'siswa' => Siswa::orderBy('nama')->get(),
            'guru' => Guru::orderBy('nama')->get(),
            'mataPelajaran' => MataPelajaran::orderBy('nama_mapel')->get(),
        ]);
    }

    public function update(Request $request, NilaiAkademik $nilai_akademik)
    {
        $validated = $this->validateData($request);
        $validated['nilai_akhir'] = $this->hitungNilaiAkhir($validated);

        $nilai_akademik->update($validated);

        return redirect()->route('nilai-akademik.index')
            ->with('success', 'Data nilai akademik berhasil diperbarui.');
    }

    public function destroy(NilaiAkademik $nilai_akademik)
    {
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
            'semester' => ['required', 'in:Ganjil,Genap'],
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
}
