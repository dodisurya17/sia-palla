<x-app-layout :title="'Dashboard Orang Tua'">

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
            <h2 class="mt-2 text-2xl font-bold leading-snug">{{ $orangTua->nama }}</h2>
            <h3 class="mt-1 text-lg font-bold uppercase tracking-wide text-blue-100">Orang Tua / Wali</h3>
        </div>
    </div>

    {{-- Anak list --}}
    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 mb-4">Data Anak</h3>

        @forelse ($orangTua->siswa as $anak)
        <div class="flex items-center justify-between gap-4 py-4 border-b border-slate-100 last:border-b-0">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 font-semibold">
                    {{ strtoupper(substr($anak->nama, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $anak->nama }}</p>
                    <p class="text-sm text-slate-500">
                        NISN: {{ $anak->nisn }} · Kelas: {{ $anak->kelas?->nama_kelas ?? '-' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('nilai-akademik.index', ['search' => $anak->nama]) }}"
                class="text-sm font-medium text-blue-600 hover:underline whitespace-nowrap">
                Lihat Nilai
            </a>
        </div>
        @empty
        <p class="text-sm text-slate-500">Belum ada data anak yang terhubung ke akun ini.</p>
        @endforelse
    </div>

</x-app-layout>