<x-app-layout :title="'Data Siswa'">
    <div class="bg-white border rounded-lg" x-data="{ deleteModal: false, deleteFormId: null, deleteName: '' }">
        <div class="flex items-center justify-between p-5 border-b">
            <form method="GET" action="{{ route('siswa.index') }}" class="flex-1 max-w-xs" id="searchForm">
                <div style="position: relative; display: flex; align-items: center; background: #fff; border: 1px solid #e5e7eb; border-radius: 999px; height: 44px; padding: 0 4px 0 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                    <input
                        type="text"
                        id="searchInput"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama / NISN..."
                        autocomplete="off"
                        style="flex: 1; border: none; outline: none; background: transparent; font-size: 14px; height: 100%;" />

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
            </form>

            <script>
                const searchInput = document.getElementById('searchInput');
                const clearBtn = document.getElementById('clearBtn');
                const searchForm = document.getElementById('searchForm');

                function toggleClear() {
                    clearBtn.classList.toggle('hidden', searchInput.value.length === 0);
                }

                searchInput.addEventListener('input', toggleClear);

                function clearSearchInput() {
                    searchInput.value = '';
                    toggleClear();
                    searchForm.submit();
                }
            </script>

            <a href="{{ route('siswa.create') }}"
                class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200  text-sm font-medium">
                + Tambah Siswa
            </a>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">NISN</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Jenis Kelamin</th>
                    <th class="px-5 py-3">Kelas</th>
                    <th class="px-5 py-3">Orang Tua</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($siswas as $siswa)
                <tr>
                    <td class="px-5 py-3">{{ $siswa->nisn }}</td>
                    <td class="px-5 py-3">{{ $siswa->nama }}</td>
                    <td class="px-5 py-3">{{ $siswa->jenis_kelamin }}</td>
                    <td class="px-5 py-3">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="px-5 py-3">{{ $siswa->orangTua->nama ?? '-' }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            {{-- Lihat --}}
                            <a href="{{ route('siswa.show', $siswa) }}"
                                class="text-gray-500 hover:text-gray-700 transition"
                                title="Lihat">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('siswa.edit', $siswa) }}"
                                class="text-indigo-500 hover:text-indigo-700 transition"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 19.5H4.5" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form id="delete-form-{{ $siswa->id }}" action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button"
                                @click="deleteModal = true; deleteFormId = 'delete-form-{{ $siswa->id }}'; deleteName = '{{ $siswa->nama }}'"
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
                    <td colspan="6" class="px-5 py-6 text-center text-gray-400">Belum ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $siswas->links() }}
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

                <h3 class="text-base font-semibold text-slate-800 mb-1">Hapus Data Siswa</h3>
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