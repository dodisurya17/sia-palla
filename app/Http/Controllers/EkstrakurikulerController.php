<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Siswa;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $query = Ekstrakurikuler::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_ekstrakurikuler', 'like', "%{$search}%")
                    ->orWhere('pembina', 'like', "%{$search}%");
            });
        }

        $ekstrakurikuler = $query->orderBy('nama_ekstrakurikuler')->paginate(10)->withQueryString();

        return view('ekstrakurikuler.index', compact('ekstrakurikuler'));
    }

    public function create()
    {
        return view('ekstrakurikuler.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ekstrakurikuler' => 'required|string|max:100',
            'jenis' => 'required|in:wajib,pilihan',
            'pembina' => 'nullable|string|max:100',
            'jadwal' => 'nullable|string|max:100',
        ]);

        Ekstrakurikuler::create($validated);

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function show(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return view('ekstrakurikuler.partials.detail', [
                'ekstrakurikuler' => $ekstrakurikuler,
                'modal' => true,
            ]);
        }

        return view('ekstrakurikuler.show', compact('ekstrakurikuler'));
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler)
    {
        return view('ekstrakurikuler.edit', compact('ekstrakurikuler'));
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $validated = $request->validate([
            'nama_ekstrakurikuler' => 'required|string|max:100',
            'jenis' => 'required|in:wajib,pilihan',
            'pembina' => 'nullable|string|max:100',
            'jadwal' => 'nullable|string|max:100',
        ]);

        $ekstrakurikuler->update($validated);

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function nilaiCreate()
    {
        $siswa = Siswa::orderBy('nama')->get();
        $ekstrakurikuler = Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();
        $nilai = new NilaiEkstrakurikuler();

        return view('ekstrakurikuler.nilai.create', compact('siswa', 'ekstrakurikuler', 'nilai'));
    }

    public function nilaiStore(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'semester' => 'required|in:Ganjil,Genap',
            'predikat' => 'required|in:Sangat Baik,Baik,Cukup,Kurang',
            'keterangan' => 'nullable|string|max:255',
        ]);

        NilaiEkstrakurikuler::create($validated);

        return redirect()->route('nilai-akademik.index', ['tab' => 'ekstrakurikuler'])
            ->with('success', 'Nilai ekstrakurikuler berhasil ditambahkan.');
    }

    public function nilaiShow(Request $request, NilaiEkstrakurikuler $nilai)
    {
        $nilai->load('siswa', 'ekstrakurikuler');

        if ($request->ajax() || $request->wantsJson()) {
            return view('ekstrakurikuler.nilai.partials.detail', compact('nilai'));
        }

        return view('ekstrakurikuler.nilai.show', compact('nilai'));
    }

    public function nilaiEdit(NilaiEkstrakurikuler $nilai)
    {
        $siswa = Siswa::orderBy('nama')->get();
        $ekstrakurikuler = Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();

        return view('ekstrakurikuler.nilai.edit', compact('siswa', 'ekstrakurikuler', 'nilai'));
    }

    public function nilaiUpdate(Request $request, NilaiEkstrakurikuler $nilai)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'semester' => 'required|in:Ganjil,Genap',
            'predikat' => 'required|in:Sangat Baik,Baik,Cukup,Kurang',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $nilai->update($validated);

        return redirect()->route('nilai-akademik.index', ['tab' => 'ekstrakurikuler'])
            ->with('success', 'Nilai ekstrakurikuler berhasil diperbarui.');
    }

    public function nilaiDestroy(NilaiEkstrakurikuler $nilai)
    {
        $nilai->delete();

        return redirect()->route('nilai-akademik.index', ['tab' => 'ekstrakurikuler'])
            ->with('success', 'Nilai ekstrakurikuler berhasil dihapus.');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->delete();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
