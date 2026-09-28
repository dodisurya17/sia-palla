<x-app-layout>
    <x-slot name="title">Riwayat Nilai</x-slot>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Riwayat Nilai</h2>
                    <p class="text-sm text-slate-500">Lihat rekam nilai dari semester-semester sebelumnya</p>
                </div>
            </div>

            <a href="{{ route('nilai-akademik.index') }}"
                class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium">
                Nilai Semester Aktif
            </a>
        </div>

        {{-- Pilih semester --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="text-xs uppercase tracking-wider text-slate-400 mb-3">Pilih Semester</p>

            @if ($semesterList->isEmpty())
            <p class="text-sm text-slate-400">Belum ada semester sebelumnya.</p>
            @else
            <div class="flex flex-wrap gap-2">
                @foreach ($semesterList as $s)
                @php $dipilih = $semesterDipilih && $semesterDipilih->id === $s->id; @endphp
                <a href="{{ route('riwayat-nilai.index', ['semester_id' => $s->id, 'tab' => $tab]) }}"
                    class="px-4 py-2 rounded-xl border text-sm transition
                        {{ $dipilih
                            ? 'bg-indigo-50 border-indigo-200 text-indigo-600 font-semibold'
                            : 'bg-white border-gray-200 text-slate-600 hover:bg-gray-50' }}">
                    <span class="block text-xs {{ $dipilih ? 'text-indigo-400' : 'text-slate-400' }}">{{ $s->tahun_ajaran }}</span>
                    {{ $s->jenis }}
                </a>
                @endforeach
            </div>
            @endif
        </div>

        @if (!$semesterDipilih)
        <div class="px-6 py-16 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Pilih semester di atas untuk melihat nilainya.
        </div>
        @else

        {{-- Info semester terpilih --}}
        <div class="px-6 py-4 border-b border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Tahun Ajaran</p>
                <p class="text-sm font-medium text-slate-700">{{ $semesterDipilih->tahun_ajaran }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Semester</p>
                <p class="text-sm font-medium text-slate-700">{{ $semesterDipilih->jenis }}</p>
            </div>
        </div>

        {{-- Tab jenis nilai --}}
        <div class="px-6 pt-4 flex gap-1 border-b border-gray-100">
            @foreach (['akademik' => 'Akademik', 'ekstrakurikuler' => 'Ekstrakurikuler'] as $key => $label)
            <a href="{{ route('riwayat-nilai.index', ['semester_id' => $semesterDipilih->id, 'tab' => $key]) }}"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition
                    {{ $tab === $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <form method="GET" action="{{ route('riwayat-nilai.index') }}" class="max-w-md">
                <input type="hidden" name="semester_id" value="{{ $semesterDipilih->id }}">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="flex items-center h-11 gap-2 pl-4 pr-2 bg-gray-100 rounded-full overflow-hidden transition focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ $tab === 'ekstrakurikuler' ? 'Cari nama siswa atau ekstrakurikuler...' : 'Cari nama siswa atau mata pelajaran...' }}"
                        class="w-full min-w-0 h-full text-sm bg-transparent border-none outline-none ring-0 focus:ring-0 focus:outline-none placeholder:text-slate-400">

                    @if (request('search'))
                    <a href="{{ route('riwayat-nilai.index', ['semester_id' => $semesterDipilih->id, 'tab' => $tab]) }}"
                        class="shrink-0 flex items-center justify-center w-6 h-6 text-slate-400 hover:text-slate-600 transition" title="Reset pencarian">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    @endif

                    <button type="submit"
                        class="shrink-0 flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        @if ($tab === 'ekstrakurikuler')
        {{-- Tabel nilai ekstrakurikuler --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-medium">Siswa</th>
                        <th class="px-6 py-4 font-medium">Kelas</th>
                        <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                        <th class="px-6 py-4 font-medium">Nilai</th>
                        <th class="px-6 py-4 font-medium">Predikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($nilai as $n)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-medium">
                            <a href="{{ route('nilai-siswa.show', ['siswa' => $n->siswa_id, 'semester_id' => $semesterDipilih->id]) }}"
                                class="text-slate-700 hover:text-indigo-600 transition">
                                {{ $n->siswa->nama ?? '-' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-xs font-semibold">
                                {{ $n->nilai }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $predikatColor = match ($n->predikat) {
                                'Sangat Baik' => 'bg-green-50 text-green-700',
                                'Baik' => 'bg-blue-50 text-blue-700',
                                'Cukup' => 'bg-amber-50 text-amber-700',
                                default => 'bg-red-50 text-red-700',
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $predikatColor }} text-xs font-semibold">
                                {{ $n->predikat }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Belum ada data nilai ekstrakurikuler pada semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($nilai->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $nilai->links() }}
            </div>
            @endif
        </div>
        @else
        {{-- Tabel nilai --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-medium">Siswa</th>
                        <th class="px-6 py-4 font-medium">Kelas</th>
                        <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-medium">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($nilai as $n)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-medium">
                            <a href="{{ route('nilai-siswa.show', ['siswa' => $n->siswa_id, 'semester_id' => $semesterDipilih->id]) }}"
                                class="text-slate-700 hover:text-indigo-600 transition">
                                {{ $n->siswa->nama ?? '-' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 font-mono text-xs font-semibold">
                                {{ $n->mataPelajaran->kode_mapel ?? '-' }}
                            </span>
                            <span class="text-slate-600">{{ $n->mataPelajaran->nama_mapel ?? '-' }}</span>
                        </td>
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
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Belum ada data nilai pada semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($nilai->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $nilai->links() }}
            </div>
            @endif
        </div>
        @endif

        @endif
    </div>
</x-app-layout>
