<x-app-layout :title="'Detail Guru'">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6 bg-white rounded-2xl shadow-sm border p-6">
            <a href="{{ route('guru.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h16v11H4V10z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4z" />
                </svg>
            </div>

            <div>
                <h1 class="text-lg font-semibold text-slate-800">{{ $guru->nama }}</h1>
                <p class="text-sm text-slate-500">NIP {{ $guru->nip }}</p>
            </div>

            <a href="{{ route('guru.edit', $guru) }}"
                class="ml-auto inline-flex items-center gap-1 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-medium hover:bg-indigo-100">
                Edit
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-8 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Informasi Pribadi</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-500">NIP</dt>
                        <dd class="text-slate-800 font-medium">{{ $guru->nip }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Nama</dt>
                        <dd class="text-slate-800 font-medium">{{ $guru->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">No. HP</dt>
                        <dd class="text-slate-800 font-medium">{{ $guru->no_hp ?? '-' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-slate-500">Alamat</dt>
                        <dd class="text-slate-800 font-medium">{{ $guru->alamat ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="pt-4 border-t">
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Data Mengajar</h2>
                <dl class="text-sm">
                    <dt class="text-slate-500">Mata Pelajaran</dt>
                    <dd class="text-slate-800 font-medium">{{ $guru->mataPelajaran->nama_mapel ?? '-' }}</dd>
                </dl>
            </div>

            @if ($guru->nilaiAkademik->isNotEmpty())
            <div class="pt-4 border-t">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Nilai Akademik yang Diinput</h2>
                <p class="text-sm text-slate-500">{{ $guru->nilaiAkademik->count() }} entri nilai tercatat oleh guru ini.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>