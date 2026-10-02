<x-app-layout>
    <x-slot name="title">Profil Nilai Siswa</x-slot>

    @php
    $isOrangTua = auth()->user()->isOrangTua();
    $inisial = \Illuminate\Support\Str::upper(collect(explode(' ', trim($siswa->nama)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
    $fmt = fn ($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');
    $badge = fn ($n) => $n >= 75
        ? 'bg-green-50 text-green-700'
        : ($n >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700');
    @endphp

    <div x-data="{ deleteFormId: null, deleteName: '' }" class="space-y-6">

        {{-- Header profil --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-6 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <a href="{{ route('nilai-akademik.index') }}"
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
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Mata Pelajaran</p>
                <p class="text-2xl font-bold text-slate-800">{{ $ringkasan['jumlah'] }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Rata-rata Nilai Akhir</p>
                @if ($ringkasan['rata_rata'] !== null)
                <p class="text-2xl font-bold {{ $ringkasan['rata_rata'] >= 75 ? 'text-green-600' : ($ringkasan['rata_rata'] >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                    {{ $fmt($ringkasan['rata_rata']) }}
                </p>
                @else
                <p class="text-2xl font-bold text-slate-300">-</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Nilai Tertinggi</p>
                @if ($ringkasan['tertinggi'])
                <p class="text-2xl font-bold text-slate-800">{{ $fmt($ringkasan['tertinggi']->nilai_akhir) }}</p>
                <p class="text-xs text-slate-400 truncate mt-0.5">{{ $ringkasan['tertinggi']->mataPelajaran->nama_mapel ?? '-' }}</p>
                @else
                <p class="text-2xl font-bold text-slate-300">-</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">Nilai Terendah</p>
                @if ($ringkasan['terendah'])
                <p class="text-2xl font-bold text-slate-800">{{ $fmt($ringkasan['terendah']->nilai_akhir) }}</p>
                <p class="text-xs text-slate-400 truncate mt-0.5">{{ $ringkasan['terendah']->mataPelajaran->nama_mapel ?? '-' }}</p>
                @else
                <p class="text-2xl font-bold text-slate-300">-</p>
                @endif
            </div>
        </div>

        {{-- Tabel nilai per mata pelajaran --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-slate-800">Nilai Mata Pelajaran</h3>
                <p class="text-sm text-slate-500">Nilai akhir dihitung otomatis: 30% Tugas + 30% UTS + 40% UAS.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-6 py-4 font-medium w-16">No</th>
                            <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                            <th class="px-6 py-4 font-medium">Guru</th>
                            <th class="px-6 py-4 font-medium">Tugas</th>
                            <th class="px-6 py-4 font-medium">UTS</th>
                            <th class="px-6 py-4 font-medium">UAS</th>
                            <th class="px-6 py-4 font-medium">Nilai Akhir</th>
                            @unless ($isOrangTua)
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($nilaiList as $nilai)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 font-mono text-xs font-semibold">
                                    {{ $nilai->mataPelajaran->kode_mapel ?? '-' }}
                                </span>
                                <span class="text-slate-700 font-medium">{{ $nilai->mataPelajaran->nama_mapel ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->guru->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $fmt($nilai->nilai_tugas) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $fmt($nilai->nilai_uts) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $fmt($nilai->nilai_uas) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $badge($nilai->nilai_akhir) }} text-xs font-semibold">
                                    {{ $fmt($nilai->nilai_akhir) }}
                                </span>
                            </td>
                            @unless ($isOrangTua)
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('nilai-akademik.edit', $nilai) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        @click="deleteFormId = 'delete-form-{{ $nilai->id }}'; deleteName = @js(($nilai->mataPelajaran->nama_mapel ?? 'nilai') . ' - ' . $siswa->nama)"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="delete-form-{{ $nilai->id }}" method="POST"
                                        action="{{ route('nilai-akademik.destroy', $nilai) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                            @endunless
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isOrangTua ? 7 : 8 }}" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Belum ada nilai akademik untuk siswa ini pada semester aktif.
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
        @unless ($isOrangTua)
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
        @endunless
    </div>
</x-app-layout>
