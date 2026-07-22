{{--
    Partial ini dipakai di dua tempat:
    1. show.blade.php (halaman penuh, ada tombol back)
    2. Dimuat via AJAX untuk modal di index.blade.php (tanpa tombol back)

    Variabel:
    - $mata_pelajaran (wajib)
    - $modal (opsional, default false) -> true jika dirender di dalam modal
--}}
@php
$modal = $modal ?? false;
@endphp

<div class="bg-white {{ $modal ? '' : 'rounded-2xl shadow-sm border' }} overflow-hidden">

    {{-- Header --}}
    <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-4 {{ $modal ? 'pr-16' : '' }}">
        <div class="flex items-center gap-4">
            @unless ($modal)
            <a href="{{ route('mata-pelajaran.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-slate-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            @endunless
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h10" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">{{ $mata_pelajaran->nama_mapel }}</h2>
                <p class="text-sm text-slate-500">Detail mata pelajaran</p>
            </div>
        </div>
        <a href="{{ route('mata-pelajaran.edit', $mata_pelajaran) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
    </div>

    {{-- Stat row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100 border-b border-gray-100">
        <div class="p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Kode Mapel</p>
            <p class="text-lg font-semibold text-slate-800 font-mono">{{ $mata_pelajaran->kode_mapel }}</p>
        </div>
        <div class="p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">KKM</p>
            <p class="text-lg font-semibold text-slate-800">{{ $mata_pelajaran->kkm }}</p>
        </div>
        <div class="p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Jumlah Guru</p>
            <p class="text-lg font-semibold text-slate-800">{{ $mata_pelajaran->guru->count() }} guru</p>
        </div>
    </div>

    {{-- Guru list --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-slate-700">Guru Pengampu</h3>
    </div>
    <div class="divide-y divide-gray-100 {{ $modal ? 'max-h-72 overflow-y-auto' : '' }}">
        @forelse ($mata_pelajaran->guru as $guru)
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-700">{{ $guru->nama }}</p>
                <p class="text-xs text-slate-400">NIP: {{ $guru->nip }}</p>
            </div>
            <a href="{{ route('guru.show', $guru) }}" class="text-xs text-indigo-600 hover:underline">Lihat
                detail</a>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-slate-400 text-sm">Belum ada guru yang mengampu mata
            pelajaran ini.</div>
        @endforelse
    </div>

</div>