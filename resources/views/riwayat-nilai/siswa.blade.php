<x-app-layout>
    <x-slot name="title">Profil Nilai Siswa</x-slot>

    @php
    $kembali = (!$semester || $semester->status === 'aktif')
        ? route('nilai-akademik.index')
        : route('riwayat-nilai.index', ['semester_id' => $semester->id]);
    @endphp

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- Header: nama siswa hanya muncul sekali --}}
        <div class="p-6 border-b border-gray-100 flex items-center gap-4">
            <a href="{{ $kembali }}"
                class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl border border-gray-200 text-slate-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-slate-800 truncate">{{ $siswa->nama }}</h2>
                <p class="text-sm text-slate-500">
                    NISN: {{ $siswa->nisn ?? '-' }} &middot; Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Info semester + pilih semester --}}
        <div class="px-6 py-4 border-b border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Tahun Ajaran</p>
                <p class="text-sm font-medium text-slate-700">{{ $semester->tahun_ajaran ?? '-' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Semester</p>
                <p class="text-sm font-medium text-slate-700">
                    {{ $semester->jenis ?? '-' }}
                    @if ($semester && $semester->status === 'aktif')
                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-lg bg-green-50 text-green-700 text-xs font-semibold">Aktif</span>
                    @endif
                </p>
            </div>
            <form method="GET" action="{{ route('nilai-siswa.show', $siswa) }}" class="bg-gray-50 rounded-xl p-4">
                <label for="semester_id" class="block text-xs uppercase tracking-wider text-slate-400 mb-1">Lihat Semester</label>
                <select id="semester_id" name="semester_id" onchange="this.form.submit()"
                    class="w-full h-9 px-3 text-sm bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition">
                    @if ($semester && !$semesterList->contains('id', $semester->id))
                    <option value="{{ $semester->id }}" selected>{{ $semester->label }}</option>
                    @endif
                    @foreach ($semesterList as $s)
                    <option value="{{ $s->id }}" {{ $semester && $semester->id === $s->id ? 'selected' : '' }}>{{ $s->label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Nilai per mata pelajaran --}}
        <div class="px-6 pt-5 pb-2">
            <h3 class="text-sm font-semibold text-slate-700">Nilai Akademik</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-medium">Tugas</th>
                        <th class="px-6 py-4 font-medium">UTS</th>
                        <th class="px-6 py-4 font-medium">UAS</th>
                        <th class="px-6 py-4 font-medium">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($nilai as $n)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 font-mono text-xs font-semibold">
                                {{ $n->mataPelajaran->kode_mapel ?? '-' }}
                            </span>
                            <span class="text-slate-700 font-medium">{{ $n->mataPelajaran->nama_mapel ?? '-' }}</span>
                            <span class="block text-xs text-slate-400 mt-0.5">{{ $n->guru->nama ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->nilai_tugas }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->nilai_uts }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->nilai_uas }}</td>
                        <td class="px-6 py-4">
                            @php
                            $badgeColor = $n->nilai_akhir >= 75
                            ? 'bg-green-50 text-green-700'
                            : ($n->nilai_akhir >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $badgeColor }} text-xs font-semibold">
                                {{ $n->nilai_akhir }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Belum ada nilai pada semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if ($rataRata !== null)
                <tfoot>
                    <tr class="bg-gray-50 border-t">
                        <td colspan="4" class="px-6 py-4 text-right text-xs uppercase tracking-wider text-slate-500 font-medium">Rata-rata Nilai Akhir</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $rataRata }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Nilai ekstrakurikuler --}}
        <div class="px-6 pt-5 pb-2 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-slate-700">Nilai Ekstrakurikuler</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                        <th class="px-6 py-4 font-medium">Nilai</th>
                        <th class="px-6 py-4 font-medium">Predikat</th>
                        <th class="px-6 py-4 font-medium">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($nilaiEkskul as $e)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 text-slate-700 font-medium">{{ $e->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-xs font-semibold">
                                {{ $e->nilai }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $predikatColor = match ($e->predikat) {
                                'Sangat Baik' => 'bg-green-50 text-green-700',
                                'Baik' => 'bg-blue-50 text-blue-700',
                                'Cukup' => 'bg-amber-50 text-amber-700',
                                default => 'bg-red-50 text-red-700',
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $predikatColor }} text-xs font-semibold">
                                {{ $e->predikat }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $e->keterangan ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                            Belum ada nilai ekstrakurikuler pada semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
