<x-app-layout :title="'Dashboard'">

    {{-- Welcome banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 p-10 text-white shadow-xl shadow-slate-900/5 ring-1 ring-slate-900/5">

        {{-- Signature: connected-node network motif --}}
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
            <h2 class="mt-2 text-2xl font-bold leading-snug">Sistem Informasi Akademik</h2>
            <h3 class="mt-1 text-lg font-bold uppercase tracking-wide text-blue-100">SMA Katolik Palla</h3>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-5">

        @php
        $stats = [
        ['label' => 'Siswa', 'value' => $totalSiswa, 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
        ['label' => 'Guru', 'value' => $totalGuru, 'icon' => 'M8 14v.5M12 14v.5M16 14v.5M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
        ['label' => 'Orang Tua', 'value' => $totalOrangTua, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['label' => 'Kelas', 'value' => $totalKelas, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0l2 0M5 21l-2 0M9 7h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1'],
        ['label' => 'Mata Pelajaran', 'value' => $totalMapel, 'icon' => 'M4 6h16M4 10h16M4 14h10M4 18h10'],
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

</x-app-layout>