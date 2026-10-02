@php
    $user = auth()->user();

    // Orang tua hanya melihat nilai. Tambah nilai akademik: admin & guru.
    // Tambah nilai ekstrakurikuler: admin saja (sesuai route nilai-ekstrakurikuler.*).
    $bisaTambahNilai = ! $user->isOrangTua();
    $bisaTambahEkskul = $user->isAdmin();

    $tabAwal = in_array(request('tab'), ['akademik', 'ekstrakurikuler'], true) ? request('tab') : 'akademik';

    $inisialDari = fn ($nama) => \Illuminate\Support\Str::upper(
        collect(explode(' ', trim($nama)))->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('')
    );

    $formatNilai = fn ($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');

    $iconEye = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
@endphp

<x-app-layout>
    <x-slot name="title">Nilai Akademik</x-slot>

    <div x-data="{ activeTab: '{{ $tabAwal }}' }">

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">

            {{-- Header --}}
            <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-slate-900">Data Nilai Akademik</h2>
                        <p class="text-sm text-slate-500">
                            @if ($semesterAktif)
                                Semester aktif: <span class="font-medium text-slate-700">{{ $semesterAktif->label }}</span>
                            @else
                                Belum ada semester yang berstatus aktif
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    {{-- Tab Akademik --}}
                    <template x-if="activeTab === 'akademik'">
                        <div class="flex w-full flex-wrap items-center gap-2 md:w-auto">
                            <a href="{{ route('riwayat-nilai.index') }}"
                                class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 md:flex-none">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Riwayat Nilai
                            </a>
                            @if ($bisaTambahNilai)
                                <a href="{{ route('nilai-akademik.create') }}"
                                    class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700 md:flex-none">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                                    Tambah Nilai
                                </a>
                            @endif
                        </div>
                    </template>

                    {{-- Tab Ekstrakurikuler --}}
                    @if ($bisaTambahEkskul)
                        <template x-if="activeTab === 'ekstrakurikuler'">
                            <a href="{{ route('nilai-ekstrakurikuler.create') }}"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700 md:w-auto">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                                Tambah Nilai Ekstrakurikuler
                            </a>
                        </template>
                    @endif
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-1 overflow-x-auto overflow-y-hidden border-t border-slate-100 px-4 sm:px-6">
                @foreach (['akademik' => 'Akademik', 'ekstrakurikuler' => 'Ekstrakurikuler'] as $key => $label)
                    <button type="button" @click="activeTab = '{{ $key }}'"
                        :class="activeTab === '{{ $key }}' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-medium transition">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Search --}}
            <div class="border-y border-slate-100 px-4 py-4 sm:px-6">
                <form method="GET" action="{{ route('nilai-akademik.index') }}" class="max-w-md">
                    <input type="hidden" name="tab" :value="activeTab">
                    <div class="flex h-11 items-center gap-2 overflow-hidden rounded-full bg-slate-100 pl-4 pr-1.5 transition focus-within:bg-white focus-within:shadow-sm focus-within:ring-2 focus-within:ring-blue-500/30">
                        <input type="text" name="search" value="{{ request('search') }}"
                            x-bind:placeholder="activeTab === 'akademik' ? 'Cari siswa, NISN, kelas, atau mata pelajaran...' : 'Cari siswa, NISN, kelas, atau ekstrakurikuler...'"
                            class="h-full w-full min-w-0 border-none bg-transparent text-sm outline-none ring-0 placeholder:text-slate-400 focus:outline-none focus:ring-0">

                        @if (request('search'))
                            <a :href="'{{ route('nilai-akademik.index') }}?tab=' + activeTab"
                                class="flex h-6 w-6 shrink-0 items-center justify-center text-slate-400 transition hover:text-slate-600" title="Reset pencarian">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        @endif

                        <button type="submit" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white transition hover:bg-blue-700" aria-label="Cari">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ================= AKADEMIK ================= --}}
            <div x-show="activeTab === 'akademik'" @if ($tabAwal !== 'akademik') x-cloak @endif>

                {{-- Desktop / tablet: tabel --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-left text-xs uppercase tracking-wider text-slate-500">
                                <th class="w-16 px-6 py-4 font-medium">No</th>
                                <th class="px-6 py-4 font-medium">Siswa</th>
                                <th class="px-6 py-4 font-medium">Kelas</th>
                                <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                                <th class="px-6 py-4 font-medium">Rata-rata Nilai Akhir</th>
                                <th class="px-6 py-4 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($nilaiAkademik as $row)
                                @php
                                    $profilUrl = route('nilai-akademik.siswa', $row->siswa_id);
                                    $rata = (float) $row->rata_rata;
                                    $badge = $rata >= 75 ? 'bg-green-50 text-green-700 ring-green-600/10'
                                        : ($rata >= 60 ? 'bg-amber-50 text-amber-700 ring-amber-600/10' : 'bg-red-50 text-red-700 ring-red-600/10');
                                @endphp
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-6 py-4 text-slate-400">{{ $nilaiAkademik->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ $profilUrl }}" class="group flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">{{ $inisialDari($row->siswa_nama) }}</span>
                                            <span class="min-w-0">
                                                <span class="block truncate font-medium text-slate-800 transition group-hover:text-blue-600">{{ $row->siswa_nama }}</span>
                                                <span class="block text-xs text-slate-400">NISN {{ $row->siswa_nisn }}</span>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($row->kelas_nama)
                                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $row->kelas_nama }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $row->jumlah_mapel }} mapel</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold ring-1 {{ $badge }}">{{ $formatNilai($rata) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end">
                                            <a href="{{ $profilUrl }}" title="Lihat Profil Nilai"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">{!! $iconEye !!}</svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                        <svg class="mx-auto mb-3 h-10 w-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        Belum ada data nilai akademik pada semester aktif.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile: kartu --}}
                <div class="divide-y divide-slate-100 md:hidden">
                    @forelse ($nilaiAkademik as $row)
                        @php
                            $profilUrl = route('nilai-akademik.siswa', $row->siswa_id);
                            $rata = (float) $row->rata_rata;
                            $badge = $rata >= 75 ? 'bg-green-50 text-green-700 ring-green-600/10'
                                : ($rata >= 60 ? 'bg-amber-50 text-amber-700 ring-amber-600/10' : 'bg-red-50 text-red-700 ring-red-600/10');
                        @endphp
                        <a href="{{ $profilUrl }}" class="flex items-center gap-3 px-4 py-4 transition active:bg-slate-50">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">{{ $inisialDari($row->siswa_nama) }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium text-slate-800">{{ $row->siswa_nama }}</span>
                                <span class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
                                    <span>NISN {{ $row->siswa_nisn }}</span>
                                    @if ($row->kelas_nama)
                                        <span class="rounded-md bg-slate-100 px-1.5 py-0.5 font-semibold text-slate-600">{{ $row->kelas_nama }}</span>
                                    @endif
                                    <span>{{ $row->jumlah_mapel }} mapel</span>
                                </span>
                            </span>
                            <span class="flex shrink-0 flex-col items-end gap-1">
                                <span class="text-[10px] uppercase tracking-wider text-slate-400">Rata-rata</span>
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold ring-1 {{ $badge }}">{{ $formatNilai($rata) }}</span>
                            </span>
                        </a>
                    @empty
                        <div class="px-6 py-14 text-center text-sm text-slate-400">Belum ada data nilai akademik pada semester aktif.</div>
                    @endforelse
                </div>

                @if ($nilaiAkademik->hasPages())
                    <div class="border-t border-slate-100 px-4 py-4 sm:px-6">{{ $nilaiAkademik->links() }}</div>
                @endif
            </div>

            {{-- ================= EKSTRAKURIKULER ================= --}}
            <div x-show="activeTab === 'ekstrakurikuler'" @if ($tabAwal !== 'ekstrakurikuler') x-cloak @endif>

                @php
                    $warnaPredikat = fn ($p) => match ($p) {
                        'Sangat Baik' => 'bg-green-50 text-green-700 ring-green-600/10',
                        'Baik' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
                        'Cukup' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                        default => 'bg-red-50 text-red-700 ring-red-600/10',
                    };
                @endphp

                {{-- Desktop / tablet: tabel --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-left text-xs uppercase tracking-wider text-slate-500">
                                <th class="w-16 px-6 py-4 font-medium">No</th>
                                <th class="px-6 py-4 font-medium">Siswa</th>
                                <th class="px-6 py-4 font-medium">Kelas</th>
                                <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                                <th class="px-6 py-4 font-medium">Nilai</th>
                                <th class="px-6 py-4 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($nilaiEkstrakurikuler as $row)
                                @php
                                    $profilEkskulUrl = route('nilai-akademik.siswa-ekskul', $row->siswa_id);
                                    $daftar = $ekskulPerSiswa->get($row->siswa_id, collect());
                                @endphp
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-6 py-4 text-slate-400">{{ $nilaiEkstrakurikuler->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ $profilEkskulUrl }}" class="group flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">{{ $inisialDari($row->siswa_nama) }}</span>
                                            <span class="min-w-0">
                                                <span class="block truncate font-medium text-slate-800 transition group-hover:text-blue-600">{{ $row->siswa_nama }}</span>
                                                <span class="block text-xs text-slate-400">NISN {{ $row->siswa_nisn }}</span>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($row->kelas_nama)
                                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $row->kelas_nama }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="block text-slate-600">{{ $row->jumlah_ekskul }} kegiatan</span>
                                        <span class="block max-w-[16rem] truncate text-xs text-slate-400">
                                            {{ $daftar->take(2)->map(fn ($n) => $n->ekstrakurikuler->nama_ekstrakurikuler ?? '-')->implode(', ') }}@if ($daftar->count() > 2), +{{ $daftar->count() - 2 }} lainnya @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @foreach ($daftar as $n)
                                                <span class="inline-flex min-w-[1.75rem] items-center justify-center rounded-lg px-2 py-1 font-mono text-xs font-semibold ring-1 {{ $warnaPredikat($n->predikat) }}"
                                                    title="{{ $n->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}: {{ $n->predikat }}">{{ $n->nilai }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end">
                                            <a href="{{ $profilEkskulUrl }}" title="Lihat Profil Ekstrakurikuler"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">{!! $iconEye !!}</svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                        <svg class="mx-auto mb-3 h-10 w-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728l1.414 1.414M16.95 16.95l1.414 1.414" /></svg>
                                        Belum ada data nilai ekstrakurikuler pada semester aktif.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile: kartu --}}
                <div class="divide-y divide-slate-100 md:hidden">
                    @forelse ($nilaiEkstrakurikuler as $row)
                        @php
                            $profilEkskulUrl = route('nilai-akademik.siswa-ekskul', $row->siswa_id);
                            $daftar = $ekskulPerSiswa->get($row->siswa_id, collect());
                        @endphp
                        <a href="{{ $profilEkskulUrl }}" class="block px-4 py-4 transition active:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">{{ $inisialDari($row->siswa_nama) }}</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium text-slate-800">{{ $row->siswa_nama }}</span>
                                    <span class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
                                        <span>NISN {{ $row->siswa_nisn }}</span>
                                        @if ($row->kelas_nama)
                                            <span class="rounded-md bg-slate-100 px-1.5 py-0.5 font-semibold text-slate-600">{{ $row->kelas_nama }}</span>
                                        @endif
                                        <span>{{ $row->jumlah_ekskul }} kegiatan</span>
                                    </span>
                                </span>
                            </span>
                            @if ($daftar->isNotEmpty())
                                <span class="mt-3 flex flex-wrap gap-1.5 pl-[3.25rem]">
                                    @foreach ($daftar as $n)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1 text-xs ring-1 {{ $warnaPredikat($n->predikat) }}">
                                            <span class="max-w-[8rem] truncate">{{ $n->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</span>
                                            <span class="font-mono font-semibold">{{ $n->nilai }}</span>
                                        </span>
                                    @endforeach
                                </span>
                            @endif
                        </a>
                    @empty
                        <div class="px-6 py-14 text-center text-sm text-slate-400">Belum ada data nilai ekstrakurikuler pada semester aktif.</div>
                    @endforelse
                </div>

                @if ($nilaiEkstrakurikuler->hasPages())
                    <div class="border-t border-slate-100 px-4 py-4 sm:px-6">{{ $nilaiEkstrakurikuler->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
