<x-app-layout :title="'Dashboard Guru'">

    {{-- Welcome banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 p-10 text-white shadow-xl shadow-slate-900/5 ring-1 ring-slate-900/5">

        <svg class="absolute inset-0 h-full w-full opacity-[0.15]" viewBox="0 0 800 300" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="120" cy="60" r="3" fill="white" />
            <circle cx="220" cy="110" r="3" fill="white" />
            <circle cx="60" cy="180" r="3" fill="white" />
            <circle cx="640" cy="50" r="3" fill="white" />
            <circle cx="720" cy="130" r="3" fill="white" />
            <circle cx="600" cy="220" r="3" fill="white" />
            <circle cx="760" cy="230" r="3" fill="white" />
            <line x1="120" y1="60" x2="220" y2="110" stroke="white" stroke-width="1" />
            <line x1="220" y1="110" x2="60" y2="180" stroke="white" stroke-width="1" />
            <line x1="640" y1="50" x2="720" y2="130" stroke="white" stroke-width="1" />
            <line x1="720" y1="130" x2="600" y2="220" stroke="white" stroke-width="1" />
            <line x1="600" y1="220" x2="760" y2="230" stroke="white" stroke-width="1" />
        </svg>
        <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -top-16 -right-10 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>

        <div class="relative text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-100">Selamat Datang</p>
            <h2 class="mt-2 text-2xl font-bold leading-snug">{{ $guru->nama }}</h2>
            <h3 class="mt-1 text-lg font-bold uppercase tracking-wide text-blue-100">
                {{ $guru->mataPelajaran?->nama_mapel ?? 'Guru' }}
            </h3>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-2 gap-5 md:grid-cols-3">

        @php
        $stats = [
        ['label' => 'Nilai Diinput', 'value' => $totalNilaiDiinput, 'icon' => 'M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7a2 2 0 00-2-2h-3.28a1 1 0 01-.948-.684L13.5 3H10.5l-.772 1.316A1 1 0 018.78 5H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'NIP', 'value' => $guru->nip, 'icon' => 'M8 14v.5M12 14v.5M16 14v.5M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
        ['label' => 'Mata Pelajaran', 'value' => $guru->mataPelajaran?->nama_mapel ?? '-', 'icon' => 'M4 6h16M4 10h16M4 14h10M4 18h10'],
        ];
        @endphp

        @foreach ($stats as $stat)
        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-900/5 transition hover:shadow-md hover:shadow-slate-900/5">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-0.5 text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick action --}}
    <a href="{{ route('nilai-akademik.create') }}"
        class="mt-6 flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-900/5 transition hover:shadow-md hover:shadow-slate-900/5">
        <div>
            <p class="font-semibold text-slate-900">Input Nilai Baru</p>
            <p class="text-sm text-slate-500 mt-0.5">Tambahkan nilai untuk siswa yang Anda ajar</p>
        </div>
        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </a>

</x-app-layout>