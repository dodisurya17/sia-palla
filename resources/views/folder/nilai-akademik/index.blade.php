<x-app-layout>
    <x-slot name="title">Nilai Akademik</x-slot>

    <div x-data="{
        activeTab: '{{ request('tab', 'akademik') }}',
        deleteFormId: null,
        deleteName: '',
        showModalOpen: false,
        showModalLoading: false,
        showModalContent: '',
        async openShowModal(url) {
            this.showModalOpen = true;
            this.showModalLoading = true;
            this.showModalContent = '';
            try {
                const res = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.showModalContent = await res.text();
            } catch (e) {
                this.showModalContent = '<p class=&quot;p-6 text-sm text-red-500&quot;>Gagal memuat detail. Silakan coba lagi.</p>';
            } finally {
                this.showModalLoading = false;
            }
        }
    }">

        {{-- Single merged container: header, tabs, search, table --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Data Nilai Akademik</h2>
                        <p class="text-sm text-slate-500">Kelola nilai akademik siswa per mata pelajaran</p>
                    </div>
                </div>

                <a x-show="activeTab === 'akademik'" href="{{ route('nilai-akademik.create') }}"
                    class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium">
                    + Tambah Nilai
                </a>
                <a x-show="activeTab === 'ekstrakurikuler'" href="{{ route('nilai-ekstrakurikuler.create') }}"
                    class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium">
                    + Tambah Nilai Ekstrakurikuler
                </a>
            </div>

            {{-- Tabs --}}
            <div class="px-6 pt-4 flex gap-1 border-b border-gray-100">
                <button type="button" @click="activeTab = 'akademik'"
                    :class="activeTab === 'akademik' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition">
                    Akademik
                </button>
                <button type="button" @click="activeTab = 'ekstrakurikuler'"
                    :class="activeTab === 'ekstrakurikuler' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition">
                    Ekstrakurikuler
                </button>
            </div>

            {{-- Search --}}
            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" action="{{ route('nilai-akademik.index') }}" class="max-w-md">
                    <div class="flex items-center h-11 gap-2 pl-4 pr-2 bg-gray-100 rounded-full overflow-hidden transition focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:shadow-sm">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama siswa atau mata pelajaran..."
                            class="w-full min-w-0 h-full text-sm bg-transparent border-none outline-none ring-0 focus:ring-0 focus:outline-none placeholder:text-slate-400">

                        @if (request('search'))
                        <a href="{{ route('nilai-akademik.index') }}"
                            class="shrink-0 flex items-center justify-center w-6 h-6 text-slate-400 hover:text-slate-600 transition" title="Reset pencarian">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                        @endif

                        <button type="submit"
                            class="shrink-0 flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table Akademik --}}
            <div x-show="activeTab === 'akademik'" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-6 py-4 font-medium">Siswa</th>
                            <th class="px-6 py-4 font-medium">Mata Pelajaran</th>
                            <th class="px-6 py-4 font-medium">Semester</th>
                            <th class="px-6 py-4 font-medium">Nilai Tugas</th>
                            <th class="px-6 py-4 font-medium">Nilai UTS</th>
                            <th class="px-6 py-4 font-medium">Nilai UAS</th>
                            <th class="px-6 py-4 font-medium">Nilai Akhir</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($nilaiAkademik as $nilai)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $nilai->siswa->nama ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 font-mono text-xs font-semibold">
                                    {{ $nilai->mataPelajaran->kode_mapel ?? '-' }}
                                </span>
                                <span class="text-slate-600">{{ $nilai->mataPelajaran->nama_mapel ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->semester }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->nilai_tugas }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->nilai_uts }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->nilai_uas }}</td>
                            <td class="px-6 py-4">
                                @php
                                $nilaiAkhir = $nilai->nilai_akhir;
                                $badgeColor = $nilaiAkhir >= 75
                                ? 'bg-green-50 text-green-700'
                                : ($nilaiAkhir >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $badgeColor }} text-xs font-semibold">
                                    {{ $nilaiAkhir }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        @click="openShowModal('{{ route('nilai-akademik.show', $nilai) }}')"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('nilai-akademik.edit', $nilai) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        @click="deleteFormId = 'delete-form-{{ $nilai->id }}'; deleteName = '{{ $nilai->siswa->nama ?? 'data ini' }}'"
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Belum ada data nilai akademik.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($nilaiAkademik->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $nilaiAkademik->links() }}
                </div>
                @endif
            </div>

            {{-- Table Ekstrakurikuler --}}
            <div x-show="activeTab === 'ekstrakurikuler'" x-cloak class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-6 py-4 font-medium">Siswa</th>
                            <th class="px-6 py-4 font-medium">Ekstrakurikuler</th>
                            <th class="px-6 py-4 font-medium">Semester</th>
                            <th class="px-6 py-4 font-medium">Nilai</th>
                            <th class="px-6 py-4 font-medium">Predikat</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($nilaiEkstrakurikuler as $nilai)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $nilai->siswa->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $nilai->semester }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-xs font-semibold">
                                    {{ $nilai->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $predikatColor = match ($nilai->predikat) {
                                'Sangat Baik' => 'bg-green-50 text-green-700',
                                'Baik' => 'bg-blue-50 text-blue-700',
                                'Cukup' => 'bg-amber-50 text-amber-700',
                                default => 'bg-red-50 text-red-700',
                                };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $predikatColor }} text-xs font-semibold">
                                    {{ $nilai->predikat }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        @click="openShowModal('{{ route('nilai-ekstrakurikuler.show', $nilai) }}')"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('nilai-ekstrakurikuler.edit', $nilai) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        @click="deleteFormId = 'delete-form-ekskul-{{ $nilai->id }}'; deleteName = ('{{ $nilai->siswa->nama ?? 'data ini' }}') + ' - ' + ('{{ $nilai->ekstrakurikuler->nama_ekstrakurikuler ?? '' }}')"
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728l1.414 1.414M16.95 16.95l1.414 1.414" />
                                </svg>
                                Belum ada data nilai ekstrakurikuler.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($nilaiEkstrakurikuler->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $nilaiEkstrakurikuler->links() }}
                </div>
                @endif
            </div>

        </div>
        {{-- /Single merged container --}}

        {{-- Delete confirmation modal --}}
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

        {{-- Detail (show) modal --}}
        <template x-teleport="body">
            <div x-show="showModalOpen" x-cloak
                class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0" style="background-color: rgba(15, 23, 42, 0.6);"
                    @click="showModalOpen = false"></div>
                <div x-show="showModalOpen" x-transition
                    class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl mx-auto max-h-[85vh] overflow-y-auto">

                    <button type="button" @click="showModalOpen = false"
                        class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm border border-gray-200 text-slate-400 hover:text-slate-600 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div x-show="showModalLoading" class="p-16 flex items-center justify-center">
                        <svg class="w-6 h-6 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>

                    <div x-show="!showModalLoading" x-html="showModalContent"></div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>