<div class="p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-4">{{ $ekstrakurikuler->nama_ekstrakurikuler }}</h3>
    <dl class="space-y-3 text-sm">
        <div class="flex justify-between">
            <dt class="text-slate-500">Jenis</dt>
            <dd class="text-slate-700 font-medium">{{ ucfirst($ekstrakurikuler->jenis) }}</dd>
        </div>
        <div class="flex justify-between">
            <dt class="text-slate-500">Pembina</dt>
            <dd class="text-slate-700 font-medium">{{ $ekstrakurikuler->pembina ?? '-' }}</dd>
        </div>
        <div class="flex justify-between">
            <dt class="text-slate-500">Jadwal</dt>
            <dd class="text-slate-700 font-medium">{{ $ekstrakurikuler->jadwal ?? '-' }}</dd>
        </div>
    </dl>
</div>