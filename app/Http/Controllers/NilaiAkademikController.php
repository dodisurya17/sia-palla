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
        $user = auth()->user();
        $search = $request->query('search');

        $query = NilaiAkademik::with(['siswa', 'mataPelajaran', 'guru']);

        if ($user->isGuru()) {
            $query->where('guru_id', $user->guru_id);
        } elseif ($user->isOrangTua()) {
            $anakIds = $user->orangTua->siswa()->pluck('id');
            $query->whereIn('siswa_id', $anakIds);
        }

        $nilaiAkademik = $query
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
        $user = auth()->user();

        return view('nilai-akademik.create', [
            'siswa' => Siswa::orderBy('nama')->get(),
            'guru' => $user->isGuru() ? $user->guru()->get() : Guru::orderBy('nama')->get(),
            'mataPelajaran' => MataPelajaran::orderBy('nama_mapel')->get(),
            'nilai' => new NilaiAkademik(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $this->assertGuruBoundary($validated['guru_id']);
        $validated['nilai_akhir'] = $this->hitungNilaiAkhir($validated);

        NilaiAkademik::create($validated);

        return redirect()->route('nilai-akademik.index')
            ->with('success', 'Data nilai akademik berhasil ditambahkan.');
    }

    public function show(NilaiAkademik $nilai_akademik)
    {
        $this->assertViewBoundary($nilai_akademik);

        $nilai_akademik->load(['siswa', 'mataPelajaran', 'guru']);

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
        ]);
    }

    public function update(Request $request, NilaiAkademik $nilai_akademik)
    {
        $this->assertGuruBoundary($nilai_akademik->guru_id);

        $validated = $this->validateData($request);
        $this->assertGuruBoundary($validated['guru_id']);
        $validated['nilai_akhir'] = $this->hitungNilaiAkhir($validated);

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
