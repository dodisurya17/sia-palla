<x-app-layout>
    <x-slot name="title">Profil Ekstrakurikuler Siswa</x-slot>

    @php
    $inisial = \Illuminate\Support\Str::upper(collect(explode(' ', trim($siswa->nama)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
    $predikatColor = fn ($p) => match ($p) {
        'Sangat Baik' => 'bg-green-50 text-green-700',
        'Baik' => 'bg-blue-50 text-blue-700',
        'Cukup' => 'bg-amber-50 text-amber-700',
        default => 'bg-red-50 text-red-700',
    };
    $nilaiColor = fn ($n) => match ($n) {
        'A' => 'text-green-600',
        'B' => 'text-blue-600',
        'C' => 'text-amber-600',
        default => 'text-red-600',
    };
    $kembali = route('nilai-akademik.index', ['tab' => 'ekstrakurikuler']);
    @endphp

    <div x-data="{ deleteFormId: null, deleteName: '' }" class="space-y-6">

        {{-- Header profil --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-6 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <a href="{{ $kembali }}"
                        class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl border border-gray-200 text-slate-500 hover:bg-gray-50 transition"
                        title="Kembali">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-indigo-50 text-indigo-600 text-lg font-semibold flex items-center justify-center">
                        {{ $inisial }}
                    </div>

                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-slate-800 truncate">{{ $siswa->nama }}</h2>
                        <div class="flex items-center flex-wrap gap-x-3 gap-y-1 text-sm text-slate-500">
                            <span>NISN {{ $siswa->nisn }}</span>
                            @if ($kelasNama)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">{{ $kelasNama }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                        {{ $semesterAktif?->label ?? 'Belum ada semester aktif' }}
                    </span>
                    <a href="{{ route('riwayat-nilai.index') }}"
                        class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-white border border-gray-200 text-slate-600 shadow-sm hover:bg-gray-50 text-sm font-medium">
                        Riwayat Nilai
                    </a>
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Ekstrakurikuler</p>
                <p class="text-2xl font-bold text-slate-800">{{ $ringkasan['jumlah'] }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Wajib / Pilihan</p>
                <p class="text-2xl font-bold text-slate-800">
                    {{ $ringkasan['wajib'] }}<span class="text-slate-300 font-normal"> / </span>{{ $ringkasan['pilihan'] }}
                </p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Nilai Tertinggi</p>
                @if ($ringkasan['tertinggi'])
                <p class="text-2xl font-bold {{ $nilaiColor($ringkasan['tertinggi']->nilai) }}">{{ $ringkasan['tertinggi']->nilai }}</p>
                <p class="text-xs text-slate-400 truncate mt-0.5">{{ $ringkasan['tertinggi']->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</p>
                @else
                <p class="text-2xl font-bold text-slate-300">-</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Nilai Terendah</p>
                @if ($ringkasan['terendah'])
                <p class="text-2xl font-bold {{ $nilaiColor($ringkasan['terendah']->nilai) }}">{{ $ringkasan['terendah']->nilai }}</p>
                <p class="text-xs text-slate-400 truncate mt-0.5">{{ $ringkasan['terendah']->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</p>
                @else
                <p class="text-2xl font-bold text-slate-300">-</p>
                @endif
            </div>
        </div>

        {{-- Tabel nilai per ekstrakurikuler --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-slate-800">Nilai Ekstrakurikuler</h3>
                <p class="text-sm text-slate-500">Nilai huruf A (terbaik) sampai E, lengkap dengan predikat dan keterangan dari pembina.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-6 py-4 font-medium w-16">No</th>
                            <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                            <th class="px-6 py-4 font-medium">Pembina</th>
                            <th class="px-6 py-4 font-medium">Nilai</th>
                            <th class="px-6 py-4 font-medium">Predikat</th>
                            <th class="px-6 py-4 font-medium">Keterangan</th>
                            @if ($bisaKelola)
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($nilaiList as $nilai)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <span class="text-slate-700 font-medium">{{ $nilai->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</span>
                                @if (($nilai->ekstrakurikuler->jenis ?? null))
                                <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 text-xs font-medium capitalize">
                                    {{ $nilai->ekstrakurikuler->jenis }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->ekstrakurikuler->pembina ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-xs font-semibold">
                                    {{ $nilai->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $predikatColor($nilai->predikat) }} text-xs font-semibold">
                                    {{ $nilai->predikat }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs">
                                {{ $nilai->keterangan ?: '-' }}
                            </td>
                            @if ($bisaKelola)
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('nilai-ekstrakurikuler.edit', $nilai) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        @click="deleteFormId = 'delete-form-ekskul-{{ $nilai->id }}'; deleteName = @js(($nilai->ekstrakurikuler->nama_ekstrakurikuler ?? 'nilai') . ' - ' . $siswa->nama)"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="delete-form-ekskul-{{ $nilai->id }}" method="POST"
                                        action="{{ route('nilai-ekstrakurikuler.destroy', $nilai) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $bisaKelola ? 7 : 6 }}" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728l1.414 1.414M16.95 16.95l1.414 1.414" />
                                </svg>
                                Belum ada nilai ekstrakurikuler untuk siswa ini pada semester aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 text-xs text-slate-400">
                Menampilkan nilai semester aktif. Untuk semester sebelumnya, buka menu
                <a href="{{ route('riwayat-nilai.index') }}" class="text-indigo-600 hover:underline">Riwayat Nilai</a>.
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        @if ($bisaKelola)
        <template x-teleport="body">
            <div x-show="deleteFormId !== null" x-cloak
                class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0" style="background-color: rgba(15, 23, 42, 0.6);"
                    @click="deleteFormId = null"></div>
                <div x-show="deleteFormId !== null" x-transition
                    class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-auto p-6">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-1">Hapus Data?</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        Anda yakin ingin menghapus <span class="font-medium text-slate-700" x-text="deleteName"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="deleteFormId = null"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="button" @click="document.getElementById(deleteFormId).submit()"
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </template>
        @endif
    </div>
</x-app-layout>
