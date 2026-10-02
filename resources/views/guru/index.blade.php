<x-app-layout :title="'Data Guru'">
    <div class="bg-white border rounded-lg" x-data="{ deleteModal: false, deleteFormId: null, deleteName: '' }">
        {{-- Toolbar: pencarian + filter (otomatis terapkan saat pilihan berubah) --}}
        @php
            $hasFilter = request()->filled('search') || request()->filled('mata_pelajaran_id') || request()->filled('kelas_id');
            $selectStyle = 'appearance: none; -webkit-appearance: none; width: 100%; height: 44px; padding: 0 36px 0 16px; background: #fff url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239ca3af\' stroke-width=\'2\'%3e%3cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 9l-7 7-7-7\'/%3e%3c/svg%3e") no-repeat right 12px center / 16px; border: 1px solid #e5e7eb; border-radius: 999px; font-size: 14px; color: #374151; box-shadow: 0 1px 2px rgba(0,0,0,0.04); cursor: pointer;';
        @endphp

        <div class="p-5 border-b">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                <form method="GET" action="{{ route('guru.index') }}" id="filterForm"
                    class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)_minmax(0,1fr)_auto] gap-3 items-center">

                    {{-- Cari nama / NIP --}}
                    <div style="position: relative; display: flex; align-items: center; background: #fff; border: 1px solid #e5e7eb; border-radius: 999px; height: 44px; padding: 0 4px 0 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                        <input
                            type="text"
                            id="searchInput"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / NIP..."
                            autocomplete="off"
                            style="flex: 1; min-width: 0; border: none; outline: none; background: transparent; font-size: 14px; height: 100%;" />

                        <button
                            type="button"
                            id="clearBtn"
                            aria-label="Hapus pencarian"
                            class="{{ request('search') ? '' : 'hidden' }}"
                            style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border: none; background: transparent; color: #9ca3af; cursor: pointer; border-radius: 50%;"
                            onclick="clearSearchInput()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div style="width: 1px; height: 20px; background: #e5e7eb; margin: 0 6px;"></div>

                        <button
                            type="submit"
                            aria-label="Cari"
                            style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: none; background: transparent; color: #374151; cursor: pointer; border-radius: 50%;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Filter Mata Pelajaran --}}
                    <select name="mata_pelajaran_id" aria-label="Filter mata pelajaran"
                        class="js-auto-submit" style="{{ $selectStyle }}">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach ($mataPelajarans as $mapel)
                            <option value="{{ $mapel->id }}" @selected((string) request('mata_pelajaran_id') === (string) $mapel->id)>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Kelas --}}
                    <select name="kelas_id" aria-label="Filter kelas"
                        class="js-auto-submit" style="{{ $selectStyle }}">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected((string) request('kelas_id') === (string) $kelas->id)>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Reset --}}
                    @if ($hasFilter)
                        <a href="{{ route('guru.index') }}"
                            class="inline-flex items-center justify-center gap-1 h-11 px-4 rounded-full border border-gray-200 bg-white text-gray-600 text-sm font-medium hover:bg-gray-50 transition whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </a>
                    @endif
                </form>

                <a href="{{ route('guru.create') }}"
                    class="inline-flex items-center justify-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium whitespace-nowrap">
                    + Tambah Guru
                </a>
            </div>

            <script>
                (function () {
                    const form = document.getElementById('filterForm');
                    const searchInput = document.getElementById('searchInput');
                    const clearBtn = document.getElementById('clearBtn');

                    // Filter langsung bekerja ketika pilihan berubah (tanpa tombol Cari)
                    form.querySelectorAll('.js-auto-submit').forEach(function (el) {
                        el.addEventListener('change', function () { form.submit(); });
                    });

                    searchInput.addEventListener('input', function () {
                        clearBtn.classList.toggle('hidden', searchInput.value.length === 0);
                    });

                    window.clearSearchInput = function () {
                        searchInput.value = '';
                        form.submit();
                    };
                })();
            </script>

            {{-- Ringkasan hasil filter --}}
            @if ($judulFilter)
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
                        </svg>
                        {{ $judulFilter }}
                    </span>
                    <span class="text-sm text-slate-500">{{ $gurus->total() }} guru ditemukan</span>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3 w-14">No</th>
                    <th class="px-5 py-3">NIP</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">No. HP</th>
                    <th class="px-5 py-3">Mata Pelajaran</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($gurus as $guru)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-5 py-3 text-gray-400">{{ $gurus->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-3">{{ $guru->nip }}</td>
                    <td class="px-5 py-3">{{ $guru->nama }}</td>
                    <td class="px-5 py-3">{{ $guru->no_hp ?? '-' }}</td>
                    <td class="px-5 py-3">{{ $guru->mataPelajaran->nama_mapel ?? '-' }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            {{-- Lihat --}}
                            <a href="{{ route('guru.show', $guru) }}"
                                class="text-gray-500 hover:text-gray-700 transition"
                                title="Lihat">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('guru.edit', $guru) }}"
                                class="text-indigo-500 hover:text-indigo-700 transition"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 19.5H4.5" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form id="delete-form-{{ $guru->id }}" action="{{ route('guru.destroy', $guru) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button"
                                @click="deleteModal = true; deleteFormId = 'delete-form-{{ $guru->id }}'; deleteName = '{{ $guru->nama }}'"
                                class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397M4.772 5.79c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center">
                        <div class="mx-auto w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </div>
                        @if ($hasFilter)
                            <p class="text-sm font-medium text-slate-600">Tidak ada guru yang cocok dengan filter.</p>
                            <p class="text-xs text-slate-400 mt-1">Coba ubah mata pelajaran / kelas, atau
                                <a href="{{ route('guru.index') }}" class="text-indigo-600 hover:underline">reset filter</a>.</p>
                        @else
                            <p class="text-sm text-gray-400">Belum ada data guru.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="p-4">
            {{ $gurus->links() }}
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="deleteModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
            x-transition.opacity>
            <div x-show="deleteModal" x-transition
                @click.outside="deleteModal = false"
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">

                <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>

                <h3 class="text-base font-semibold text-slate-800 mb-1">Hapus Data Guru</h3>
                <p class="text-sm text-slate-500 mb-6">
                    Apakah kamu yakin ingin menghapus <span class="font-medium text-slate-700" x-text="deleteName"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="deleteModal = false"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="button"
                        @click="document.getElementById(deleteFormId).submit()"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg shadow-sm hover:bg-red-700 transition-colors">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>