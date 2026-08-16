<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas dengan pencarian & pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $kelas = Kelas::withCount('siswa')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kelas', 'like', "%{$search}%")
                        ->orWhere('tingkat', 'like', "%{$search}%")
                        ->orWhere('wali_kelas', 'like', "%{$search}%");
                });
            })
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate(10)
            ->withQueryString();

        return view('kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form tambah kelas.
     */
    public function create()
    {
        return view('kelas.create');
    }

    /**
     * Menyimpan kelas baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);

        try {
            Kelas::create($validated);

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Data kelas berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data kelas. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail kelas (dipanggil via fetch untuk modal detail).
     */
    public function show(Kelas $kelas)
    {
        $kelas->loadCount('siswa');
        $kelas->load(['siswa' => fn($q) => $q->orderBy('nama')]);

        return view('kelas.show', compact('kelas'));
    }

    /**
     * Menampilkan form edit kelas.
     */
    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    /**
     * Memperbarui data kelas.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $this->validated($request);

        try {
            $kelas->update($validated);

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data kelas. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus kelas.
     */
    public function destroy(Kelas $kelas)
    {
        try {
            if ($kelas->siswa()->exists()) {
                return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
            }

            $kelas->delete();

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data kelas. Silakan coba lagi.');
        }
    }

    /**
     * Aturan validasi bersama untuk store & update.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['nullable', 'string', 'max:255'],
            'wali_kelas' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
        ]);
    }
}
