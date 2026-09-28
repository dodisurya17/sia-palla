<div class="p-6 pt-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-slate-800">Detail Nilai Akademik</h3>
            <p class="text-sm text-slate-500">{{ $nilai->siswa->nama ?? '-' }} &middot; {{ $nilai->semester }}</p>
        </div>
    </div>

    <dl class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Siswa</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->siswa->nama ?? '-' }}</dd>
            <dd class="text-xs text-slate-400">NISN: {{ $nilai->siswa->nisn ?? '-' }}</dd>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Mata Pelajaran</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->mataPelajaran->nama_mapel ?? '-' }}</dd>
            <dd class="text-xs text-slate-400">Kode: {{ $nilai->mataPelajaran->kode_mapel ?? '-' }}</dd>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Guru Pengampu</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->guru->nama ?? '-' }}</dd>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs uppercase tracking-wider text-slate-400 mb-1">Semester</dt>
            <dd class="text-sm font-medium text-slate-700">{{ $nilai->semester }}</dd>
        </div>
    </dl>

    <div class="border border-gray-100 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                    <th class="px-4 py-3 font-medium">Tugas</th>
                    <th class="px-4 py-3 font-medium">UTS</th>
                    <th class="px-4 py-3 font-medium">UAS</th>
                    <th class="px-4 py-3 font-medium">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-3 text-slate-600">{{ $nilai->nilai_tugas }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $nilai->nilai_uts }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $nilai->nilai_uas }}</td>
                    <td class="px-4 py-3">
                        @php
                        $badgeColor = $nilai->nilai_akhir >= 75
                        ? 'bg-green-50 text-green-700'
                        : ($nilai->nilai_akhir >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700');
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $badgeColor }} text-xs font-semibold">
                            {{ $nilai->nilai_akhir }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="text-xs text-slate-400 mt-4">
        Nilai akhir dihitung otomatis: 30% Tugas + 30% UTS + 40% UAS.
    </p>
</div>