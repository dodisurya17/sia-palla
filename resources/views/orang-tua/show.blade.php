<x-app-layout :title="'Detail Orang Tua'">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6 bg-white rounded-2xl shadow-sm border p-6">
            <a href="{{ route('orang-tua.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <div>
                <h1 class="text-lg font-semibold text-slate-800">{{ $orangTua->nama }}</h1>
                <p class="text-sm text-slate-500">{{ $orangTua->pekerjaan ?? 'Orang Tua Siswa' }}</p>
            </div>

            <a href="{{ route('orang-tua.edit', $orangTua) }}"
                class="ml-auto inline-flex items-center gap-1 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-medium hover:bg-indigo-100">
                Edit
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6 space-y-6">
            <div>
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Informasi Pribadi</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-500">Nama</dt>
                        <dd class="text-slate-800 font-medium">{{ $orangTua->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">No. HP</dt>
                        <dd class="text-slate-800 font-medium">{{ $orangTua->no_hp ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Pekerjaan</dt>
                        <dd class="text-slate-800 font-medium">{{ $orangTua->pekerjaan ?? '-' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-slate-500">Alamat</dt>
                        <dd class="text-slate-800 font-medium">{{ $orangTua->alamat ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            @if ($orangTua->siswa->isNotEmpty())
            <div class="pt-4 border-t">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Data Anak</h2>
                <div class="space-y-2">
                    @foreach ($orangTua->siswa as $anak)
                    <div class="flex items-center justify-between text-sm border rounded-lg px-4 py-2.5">
                        <div>
                            <span class="text-slate-800 font-medium">{{ $anak->nama }}</span>
                            <span class="text-slate-400"> · {{ $anak->nisn }}</span>
                        </div>
                        <span class="text-slate-500">{{ $anak->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="pt-4 border-t">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Data Anak</h2>
                <p class="text-sm text-slate-400">Belum ada data anak yang terhubung.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>