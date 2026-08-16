<div class="p-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0l2 0M5 21l-2 0M9 7h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-slate-800">{{ $kelas->nama_kelas }}</h3>
            <p class="text-sm text-slate-500">{{ $kelas->tingkat ?? 'Tingkat belum diatur' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Wali Kelas</p>
            <p class="text-sm font-medium text-slate-700">{{ $kelas->wali_kelas ?? '-' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Jumlah Siswa</p>
            <p class="text-sm font-medium text-slate-700">{{ $kelas->siswa_count }} siswa</p>
        </div>
    </div>

    <div>
        <p class="text-xs uppercase tracking-wider text-slate-400 mb-3">Daftar Siswa</p>

        @if ($kelas->siswa->isEmpty())
        <p class="text-sm text-slate-400 text-center py-8">Belum ada siswa di kelas ini.</p>
        @else
        <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 rounded-xl border border-gray-100">
            @foreach ($kelas->siswa as $siswa)
            <div class="px-4 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-semibold">
                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                </div>
                <span class="text-sm text-slate-600">{{ $siswa->nama }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>