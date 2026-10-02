<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $mapelId = $request->input('mata_pelajaran_id');
        $kelasId = $request->input('kelas_id');

        $gurus = Guru::with('mataPelajaran')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            // Filter mata pelajaran
            ->when($mapelId, fn ($query, $id) => $query->where('mata_pelajaran_id', $id))
            // Filter kelas: guru yang pernah/sedang mengajar siswa di kelas tsb (via nilai_akademik)
            ->when($kelasId, function ($query, $id) {
                $query->whereIn('id', function ($sub) use ($id) {
                    $sub->select('nilai_akademik.guru_id')
                        ->from('nilai_akademik')
                        ->join('siswa', 'siswa.id', '=', 'nilai_akademik.siswa_id')
                        ->where('siswa.kelas_id', $id)
                        ->whereNotNull('nilai_akademik.guru_id');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $mataPelajarans = MataPelajaran::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        $kelasList      = DB::table('kelas')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        // Judul hasil filter, contoh: "Guru Bahasa Indonesia - X AKA"
        $namaMapel = $mapelId ? $mataPelajarans->firstWhere('id', (int) $mapelId)?->nama_mapel : null;
        $namaKelas = $kelasId ? $kelasList->firstWhere('id', (int) $kelasId)?->nama_kelas : null;

        $judulFilter = null;
        if ($namaMapel || $namaKelas) {
            $judulFilter = 'Guru ' . collect([$namaMapel, $namaKelas])->filter()->implode(' - ');
        }

        return view('guru.index', compact(
            'gurus', 'mataPelajarans', 'kelasList', 'judulFilter'
        ));
    }

    public function create()
    {
        $mataPelajarans = MataPelajaran::orderBy('nama_mapel')->get();

        return view('guru.create', compact('mataPelajarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'               => 'required|string|max:30|unique:guru,nip',
            'nama'              => 'required|string|max:255',
            'no_hp'             => 'nullable|string|max:20',
            'alamat'            => 'nullable|string',
            'mata_pelajaran_id' => 'exists:mata_pelajaran,id',
        ]);

        Guru::create($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru)
    {
        $user = auth()->user();
        if ($user->isGuru() && $user->guru_id !== $guru->id) {
            abort(403);
        }

        $guru->load('mataPelajaran', 'nilaiAkademik');

        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        $mataPelajarans = MataPelajaran::orderBy('nama_mapel')->get();

        return view('guru.edit', compact('guru', 'mataPelajarans'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip'               => 'required|string|max:30|unique:guru,nip,' . $guru->id,
            'nama'              => 'required|string|max:255',
            'no_hp'             => 'nullable|string|max:20',
            'alamat'            => 'nullable|string',
            'mata_pelajaran_id' => 'exists:mata_pelajaran,id',
        ]);

        $guru->update($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    public function buatAkun(Guru $guru)
    {
        if ($guru->user) {
            return back()->with('error', 'Guru ini sudah memiliki akun login.');
        }

        $password = str()->random(10);

        $user = \App\Models\User::create([
            'name' => $guru->nama,
            'email' => $guru->email ?? strtolower(str_replace(' ', '.', $guru->nama)) . '@siapalla.my.id',
            'password' => bcrypt($password),
            'role' => \App\Models\User::ROLE_GURU,
            'guru_id' => $guru->id,
        ]);

        return back()->with('success', "Akun dibuat. Email: {$user->email}, Password sementara: {$password}");
    }
}
