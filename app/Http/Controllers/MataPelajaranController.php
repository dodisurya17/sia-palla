<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::withCount('guru');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mapel', 'like', "%{$search}%")
                    ->orWhere('nama_mapel', 'like', "%{$search}%");
            });
        }

        $mataPelajaran = $query->orderBy('nama_mapel')->paginate(10)->withQueryString();

        return view('mata-pelajaran.index', compact('mataPelajaran'));
    }

    public function create()
    {
        return view('mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function show(Request $request, MataPelajaran $mata_pelajaran)
    {
        $mata_pelajaran->load('guru');

        if ($request->ajax() || $request->wantsJson()) {
            return view('mata-pelajaran.partials.detail', [
                'mata_pelajaran' => $mata_pelajaran,
                'modal' => true,
            ]);
        }

        return view('mata-pelajaran.show', compact('mata_pelajaran'));
    }

    public function edit(MataPelajaran $mata_pelajaran)
    {
        return view('mata-pelajaran.edit', compact('mata_pelajaran'));
    }

    public function update(Request $request, MataPelajaran $mata_pelajaran)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel,' . $mata_pelajaran->id,
            'nama_mapel' => 'required|string|max:100',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        $mata_pelajaran->update($validated);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mata_pelajaran)
    {
        if ($mata_pelajaran->guru()->exists() || $mata_pelajaran->nilaiAkademik()->exists()) {
            return redirect()->route('mata-pelajaran.index')
                ->with('error', 'Mata pelajaran tidak dapat dihapus karena masih memiliki data guru atau nilai terkait.');
        }

        $mata_pelajaran->delete();

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
