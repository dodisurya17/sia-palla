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
}
