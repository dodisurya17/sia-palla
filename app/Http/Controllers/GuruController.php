<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $gurus = Guru::with('mataPelajaran')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('guru.index', compact('gurus'));
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
