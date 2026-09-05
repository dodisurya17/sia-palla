<div class="p-6 pt-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728l1.414 1.414M16.95 16.95l1.414 1.414" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-slate-800">Detail Nilai Ekstrakurikuler</h3>
            <p class="text-sm text-slate-500">{{ $nilai->siswa->nama ?? '-' }} &middot; {{ $nilai->semester }}</p>
        </div>
    </div>

    <dl class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Siswa</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->siswa->nama ?? '-' }}</dd>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Ekstrakurikuler</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</dd>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Semester</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->semester }}</dd>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Predikat</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->predikat }}</dd>
        </div>
    </dl>

    @if ($nilai->keterangan)
    <div class="border border-gray-100 rounded-xl p-4">
        <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">Keterangan</p>
        <p class="text-sm text-slate-600">{{ $nilai->keterangan }}</p>
    </div>
    @endif
</div>