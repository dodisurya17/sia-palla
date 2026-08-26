<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orangTuas = OrangTua::withCount('siswa')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('orang-tua.index', compact('orangTuas'));
    }

    public function create()
    {
        $availableSiswa = Siswa::whereNull('orang_tua_id')->orderBy('nama')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $assignedIds = [];

        return view('orang-tua.create', compact('availableSiswa', 'kelasList', 'assignedIds'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        DB::transaction(function () use ($validated) {
            $orangTua = OrangTua::create([
                'nama'      => $validated['nama'],
                'no_hp'     => $validated['no_hp'] ?? null,
                'alamat'    => $validated['alamat'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
            ]);

            $this->syncAnak($orangTua, $validated);
        });

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    public function show(OrangTua $orang_tua)
    {
        $user = auth()->user();
        if ($user->isOrangTua() && $user->orang_tua_id !== $orang_tua->id) {
            abort(403);
        }

        $orang_tua->load('siswa.kelas');

        return view('orang-tua.show', ['orangTua' => $orang_tua]);
    }

    public function edit(OrangTua $orang_tua)
    {
        $availableSiswa = Siswa::where(function ($q) use ($orang_tua) {
            $q->whereNull('orang_tua_id')
                ->orWhere('orang_tua_id', $orang_tua->id);
        })
            ->orderBy('nama')
            ->get();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $assignedIds = $orang_tua->siswa()->pluck('id')->toArray();

        return view('orang-tua.edit', [
            'orangTua'       => $orang_tua,
            'availableSiswa' => $availableSiswa,
            'kelasList'      => $kelasList,
            'assignedIds'    => $assignedIds,
        ]);
    }

    public function update(Request $request, OrangTua $orang_tua)
    {
        $validated = $this->validateData($request, $orang_tua->id);

        DB::transaction(function () use ($validated, $orang_tua) {
            $orang_tua->update([
                'nama'      => $validated['nama'],
                'no_hp'     => $validated['no_hp'] ?? null,
                'alamat'    => $validated['alamat'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
            ]);

            // Lepas siswa yang sebelumnya di-assign tapi sekarang tidak dicentang lagi
            $previousIds = $orang_tua->siswa()->pluck('id')->toArray();
            $selectedIds = $validated['existing_siswa_ids'] ?? [];
            $toUnassign  = array_diff($previousIds, $selectedIds);

            if (!empty($toUnassign)) {
                Siswa::whereIn('id', $toUnassign)->update(['orang_tua_id' => null]);
            }

            $this->syncAnak($orang_tua, $validated);
        });

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(OrangTua $orang_tua)
    {
        $orang_tua->delete();

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua berhasil dihapus.');
    }

    /**
     * Validasi data OrangTua sekaligus data anak (existing & baru).
     */
    private function validateData(Request $request, ?int $orangTuaId = null): array
    {
        return $request->validate([
            'nama'      => 'required|string|max:255',
            'no_hp'     => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',

            'existing_siswa_ids'   => 'nullable|array',
            'existing_siswa_ids.*'  => 'exists:siswa,id',

            'new_siswa'                     => 'nullable|array',
            'new_siswa.*.nama'              => 'required_with:new_siswa|string|max:255',
            'new_siswa.*.nisn'              => 'required_with:new_siswa|string|max:20|distinct|unique:siswa,nisn',
            'new_siswa.*.jenis_kelamin'     => 'required_with:new_siswa|in:L,P',
            'new_siswa.*.tempat_lahir'      => 'nullable|string|max:255',
            'new_siswa.*.tanggal_lahir'     => 'nullable|date',
            'new_siswa.*.kelas_id'          => 'nullable|exists:kelas,id',
        ]);
    }

    /**
     * Assign siswa existing yang dicentang + buat siswa baru, semuanya di-link ke $orangTua.
     */
    private function syncAnak(OrangTua $orangTua, array $validated): void
    {
        if (!empty($validated['existing_siswa_ids'])) {
            Siswa::whereIn('id', $validated['existing_siswa_ids'])
                ->update(['orang_tua_id' => $orangTua->id]);
        }

        if (!empty($validated['new_siswa'])) {
            foreach ($validated['new_siswa'] as $anak) {
                Siswa::create([
                    'nisn'           => $anak['nisn'],
                    'nama'           => $anak['nama'],
                    'jenis_kelamin'  => $anak['jenis_kelamin'],
                    'tempat_lahir'   => $anak['tempat_lahir'] ?? null,
                    'tanggal_lahir'  => $anak['tanggal_lahir'] ?? null,
                    'kelas_id'       => $anak['kelas_id'] ?? null,
                    'orang_tua_id'   => $orangTua->id,
                ]);
            }
        }
    }
}
