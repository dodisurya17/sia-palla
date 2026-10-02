<x-app-layout>
    <x-slot name="title">Riwayat Nilai</x-slot>

    @php
    $inisial = fn ($nama) => collect(preg_split('/\s+/', trim($nama ?? '')))
        ->filter()->take(2)->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))->implode('') ?: '?';

    $resetUrl = $semesterDipilih
        ? route('riwayat-nilai.index', ['semester_id' => $semesterDipilih->id, 'tab' => $tab])
        : route('riwayat-nilai.index');
    @endphp

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

        {{-- Search + filter --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <form method="GET" action="{{ route('riwayat-nilai.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="semester_id" value="{{ $semesterDipilih->id }}">
                <input type="hidden" name="tab" value="{{ $tab }}">

                {{-- Search --}}
                <div class="flex items-center h-11 gap-2 pl-4 pr-2 bg-gray-100 rounded-full overflow-hidden transition focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:shadow-sm flex-1 min-w-[240px] max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ $tab === 'ekstrakurikuler' ? 'Cari siswa, NISN, kelas, atau ekstrakurikuler...' : 'Cari siswa, NISN, kelas, atau mata pelajaran...' }}"
                        class="w-full min-w-0 h-full text-sm bg-transparent border-none outline-none ring-0 focus:ring-0 focus:outline-none placeholder:text-slate-400">

                    <button type="submit"
                        class="shrink-0 flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                {{-- Filter kelas --}}
                <div class="relative">
                    <select name="kelas_id" onchange="this.form.submit()" aria-label="Filter kelas"
                        style="background-image:none"
                        class="appearance-none h-11 pl-4 pr-10 text-sm rounded-full border-none cursor-pointer transition focus:ring-2 focus:ring-indigo-500/30 focus:outline-none
                            {{ $kelasId ? 'bg-indigo-50 text-indigo-700 font-medium' : 'bg-gray-100 text-slate-600 hover:bg-gray-200/70' }}">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasOptions as $k)
                        <option value="{{ $k->id }}" @selected($kelasId === $k->id)>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 {{ $kelasId ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Filter mapel / ekstrakurikuler --}}
                <div class="relative">
                    <select name="item_id" onchange="this.form.submit()"
                        aria-label="{{ $tab === 'ekstrakurikuler' ? 'Filter ekstrakurikuler' : 'Filter mata pelajaran' }}"
                        style="background-image:none"
                        class="appearance-none h-11 pl-4 pr-10 text-sm rounded-full border-none cursor-pointer max-w-[240px] truncate transition focus:ring-2 focus:ring-indigo-500/30 focus:outline-none
                            {{ $itemId ? 'bg-indigo-50 text-indigo-700 font-medium' : 'bg-gray-100 text-slate-600 hover:bg-gray-200/70' }}">
                        <option value="">{{ $tab === 'ekstrakurikuler' ? 'Semua Ekstrakurikuler' : 'Semua Mapel' }}</option>
                        @foreach ($itemOptions as $o)
                        <option value="{{ $o->id }}" @selected($itemId === $o->id)>{{ $o->label }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 {{ $itemId ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Reset --}}
                @if ($filterAktif)
                <a href="{{ $resetUrl }}"
                    class="inline-flex items-center gap-1.5 h-11 px-4 rounded-full text-sm text-slate-500 hover:text-slate-700 hover:bg-gray-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset
                </a>
                @endif

                <p class="ml-auto text-xs text-slate-400">{{ $nilai->total() }} siswa</p>
            </form>
        </div>

        @if ($tab === 'ekstrakurikuler')
        {{-- Tabel nilai ekstrakurikuler (1 siswa = 1 baris) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-medium w-16">No</th>
                        <th class="px-6 py-4 font-medium">Siswa</th>
                        <th class="px-6 py-4 font-medium">Kelas</th>
                        <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($nilai as $n)
                    @php $detailUrl = route('nilai-siswa.show', ['siswa' => $n->siswa_id, 'semester_id' => $semesterDipilih->id]); @endphp
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 text-slate-400">{{ $nilai->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ $detailUrl }}" class="flex items-center gap-3 group">
                                <span class="w-10 h-10 shrink-0 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold flex items-center justify-center">
                                    {{ $inisial($n->siswa->nama ?? '') }}
                                </span>
                                <span class="min-w-0">
                                    <span class="block font-medium text-slate-700 group-hover:text-indigo-600 transition truncate">{{ $n->siswa->nama ?? '-' }}</span>
                                    <span class="block text-xs text-slate-400">NISN {{ $n->siswa->nisn ?? '-' }}</span>
                                </span>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ $n->siswa->kelas->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($ekskulPerSiswa->get($n->siswa_id, collect()) as $e)
                                @php
                                $predikatColor = match ($e->predikat) {
                                    'Sangat Baik' => 'bg-green-50 text-green-700',
                                    'Baik' => 'bg-blue-50 text-blue-700',
                                    'Cukup' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-red-50 text-red-700',
                                };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 pl-2.5 pr-1 py-1 rounded-lg bg-gray-50 border border-gray-100 text-xs text-slate-600">
                                    {{ $e->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                    <span class="px-1.5 py-0.5 rounded-md {{ $predikatColor }} font-semibold">{{ $e->predikat }}</span>
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ $detailUrl }}" title="Lihat Nilai"
                                class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 shadow-sm hover:bg-indigo-100 hover:border-indigo-200 text-xs font-medium transition">
                                Lihat Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ $filterAktif ? 'Tidak ada data yang cocok dengan filter.' : 'Belum ada data nilai ekstrakurikuler pada semester ini.' }}
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
        {{-- Tabel nilai akademik (1 siswa = 1 baris, rata-rata nilai akhir) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-medium w-16">No</th>
                        <th class="px-6 py-4 font-medium">Siswa</th>
                        <th class="px-6 py-4 font-medium">Kelas</th>
                        <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-medium">{{ $mapelTerpilih ? 'Nilai Akhir' : 'Rata-rata Nilai Akhir' }}</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($nilai as $n)
                    @php
                    $detailUrl = route('nilai-siswa.show', ['siswa' => $n->siswa_id, 'semester_id' => $semesterDipilih->id]);
                    $badgeColor = $n->rata_rata >= 75
                        ? 'bg-green-50 text-green-700 border-green-100'
                        : ($n->rata_rata >= 60 ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-red-50 text-red-700 border-red-100');
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 text-slate-400">{{ $nilai->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ $detailUrl }}" class="flex items-center gap-3 group">
                                <span class="w-10 h-10 shrink-0 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold flex items-center justify-center">
                                    {{ $inisial($n->siswa->nama ?? '') }}
                                </span>
                                <span class="min-w-0">
                                    <span class="block font-medium text-slate-700 group-hover:text-indigo-600 transition truncate">{{ $n->siswa->nama ?? '-' }}</span>
                                    <span class="block text-xs text-slate-400">NISN {{ $n->siswa->nisn ?? '-' }}</span>
                                </span>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ $n->siswa->kelas->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if ($mapelTerpilih)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 font-mono text-xs font-semibold">{{ $mapelTerpilih->kode_mapel }}</span>
                            {{ $mapelTerpilih->nama_mapel }}
                            @else
                            {{ $n->jumlah_mapel }} mapel
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg border {{ $badgeColor }} text-xs font-semibold">
                                {{ number_format($n->rata_rata, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ $detailUrl }}" title="Lihat Nilai"
                                class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 shadow-sm hover:bg-indigo-100 hover:border-indigo-200 text-xs font-medium transition">
                                Lihat Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ $filterAktif ? 'Tidak ada data yang cocok dengan filter.' : 'Belum ada data nilai pada semester ini.' }}
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
