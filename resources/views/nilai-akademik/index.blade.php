<x-app-layout>
    <x-slot name="title">Nilai Akademik</x-slot>

    <div x-data="{ activeTab: '{{ request('tab', 'akademik') }}' }">

        {{-- Single merged container: header, tabs, search, table --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Data Nilai Akademik</h2>
                        <p class="text-sm text-slate-500">
                            @if ($semesterAktif)
                            Semester aktif: <span class="font-medium text-slate-700">{{ $semesterAktif->label }}</span>
                            @else
                            Belum ada semester yang berstatus aktif
                            @endif
                        </p>
                    </div>
                </div>

                <div x-show="activeTab === 'akademik'" class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('riwayat-nilai.index') }}"
                        class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-white border border-gray-200 text-slate-600 shadow-sm hover:bg-gray-50 text-sm font-medium">
                        Riwayat Nilai
                    </a>
                    <a href="{{ route('nilai-akademik.create') }}"
                        class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium">
                        + Tambah Nilai
                    </a>
                </div>
                <a x-show="activeTab === 'ekstrakurikuler'" href="{{ route('nilai-ekstrakurikuler.create') }}"
                    class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium">
                    + Tambah Nilai Ekstrakurikuler
                </a>
            </div>

            {{-- Tabs --}}
            <div class="px-6 pt-4 flex gap-1 border-b border-gray-100">
                <button type="button" @click="activeTab = 'akademik'"
                    :class="activeTab === 'akademik' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition">
                    Akademik
                </button>
                <button type="button" @click="activeTab = 'ekstrakurikuler'"
                    :class="activeTab === 'ekstrakurikuler' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition">
                    Ekstrakurikuler
                </button>
            </div>

            {{-- Search --}}
            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" action="{{ route('nilai-akademik.index') }}" class="max-w-md">
                    <input type="hidden" name="tab" :value="activeTab">
                    <div class="flex items-center h-11 gap-2 pl-4 pr-2 bg-gray-100 rounded-full overflow-hidden transition focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:shadow-sm">

                        <input type="text" name="search" value="{{ request('search') }}"
                            x-bind:placeholder="activeTab === 'akademik' ? 'Cari siswa, NISN, kelas, atau mata pelajaran...' : 'Cari siswa, NISN, kelas, atau ekstrakurikuler...'"
                            class="w-full min-w-0 h-full text-sm bg-transparent border-none outline-none ring-0 focus:ring-0 focus:outline-none placeholder:text-slate-400">

                        @if (request('search'))
                        <a :href="'{{ route('nilai-akademik.index') }}?tab=' + activeTab"
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

            {{-- Table Akademik: 1 siswa = 1 baris --}}
            <div x-show="activeTab === 'akademik'" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-6 py-4 font-medium w-16">No</th>
                            <th class="px-6 py-4 font-medium">Siswa</th>
                            <th class="px-6 py-4 font-medium">Kelas</th>
                            <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                            <th class="px-6 py-4 font-medium">Rata-rata Nilai Akhir</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($nilaiAkademik as $row)
                        @php
                        $profilUrl = route('nilai-akademik.siswa', $row->siswa_id);
                        $rata = (float) $row->rata_rata;
                        $badgeColor = $rata >= 75
                        ? 'bg-green-50 text-green-700'
                        : ($rata >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700');
                        $inisial = \Illuminate\Support\Str::upper(collect(explode(' ', trim($row->siswa_nama)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-slate-400">{{ $nilaiAkademik->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ $profilUrl }}" class="flex items-center gap-3 group">
                                    <span class="w-9 h-9 shrink-0 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold flex items-center justify-center">
                                        {{ $inisial }}
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block font-medium text-slate-700 group-hover:text-indigo-600 transition truncate">{{ $row->siswa_nama }}</span>
                                        <span class="block text-xs text-slate-400">NISN {{ $row->siswa_nisn }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if ($row->kelas_nama)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">
                                    {{ $row->kelas_nama }}
                                </span>
                                @else
                                <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $row->jumlah_mapel }} mapel</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $badgeColor }} text-xs font-semibold">
                                    {{ rtrim(rtrim(number_format($rata, 2, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ $profilUrl }}"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Lihat Profil Nilai">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Belum ada data nilai akademik pada semester aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($nilaiAkademik->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $nilaiAkademik->links() }}
                </div>
                @endif
            </div>

            {{-- Table Ekstrakurikuler: 1 siswa = 1 baris --}}
            <div x-show="activeTab === 'ekstrakurikuler'" x-cloak class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-6 py-4 font-medium w-16">No</th>
                            <th class="px-6 py-4 font-medium">Siswa</th>
                            <th class="px-6 py-4 font-medium">Kelas</th>
                            <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                            <th class="px-6 py-4 font-medium">Nilai</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($nilaiEkstrakurikuler as $row)
                        @php
                        $profilEkskulUrl = route('nilai-akademik.siswa-ekskul', $row->siswa_id);
                        $daftar = $ekskulPerSiswa->get($row->siswa_id, collect());
                        $inisial = \Illuminate\Support\Str::upper(collect(explode(' ', trim($row->siswa_nama)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-slate-400">{{ $nilaiEkstrakurikuler->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ $profilEkskulUrl }}" class="flex items-center gap-3 group">
                                    <span class="w-9 h-9 shrink-0 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold flex items-center justify-center">
                                        {{ $inisial }}
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block font-medium text-slate-700 group-hover:text-indigo-600 transition truncate">{{ $row->siswa_nama }}</span>
                                        <span class="block text-xs text-slate-400">NISN {{ $row->siswa_nisn }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if ($row->kelas_nama)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">
                                    {{ $row->kelas_nama }}
                                </span>
                                @else
                                <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="block text-slate-600">{{ $row->jumlah_ekskul }} kegiatan</span>
                                <span class="block text-xs text-slate-400 truncate max-w-[16rem]">
                                    {{ $daftar->take(2)->map(fn ($n) => $n->ekstrakurikuler->nama_ekstrakurikuler ?? '-')->implode(', ') }}@if ($daftar->count() > 2), +{{ $daftar->count() - 2 }} lainnya @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{-- Chip nilai huruf per kegiatan, diwarnai sesuai predikat --}}
                                <div class="flex items-center flex-wrap gap-1.5">
                                    @foreach ($daftar as $n)
                                    @php
                                    $predikatColor = match ($n->predikat) {
                                    'Sangat Baik' => 'bg-green-50 text-green-700',
                                    'Baik' => 'bg-blue-50 text-blue-700',
                                    'Cukup' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-red-50 text-red-700',
                                    };
                                    @endphp
                                    <span class="inline-flex items-center justify-center min-w-[1.75rem] px-2 py-1 rounded-lg {{ $predikatColor }} font-mono text-xs font-semibold"
                                        title="{{ $n->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}: {{ $n->predikat }}">
                                        {{ $n->nilai }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ $profilEkskulUrl }}"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Lihat Profil Ekstrakurikuler">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728l1.414 1.414M16.95 16.95l1.414 1.414" />
                                </svg>
                                Belum ada data nilai ekstrakurikuler pada semester aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($nilaiEkstrakurikuler->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $nilaiEkstrakurikuler->links() }}
                </div>
                @endif
            </div>

        </div>
        {{-- /Single merged container --}}
    </div>
</x-app-layout>