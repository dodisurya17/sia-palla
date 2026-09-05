<x-app-layout>
    <x-slot name="title">Edit Ekstrakurikuler</x-slot>

    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6 flex items-center gap-4">
            <a href="{{ route('ekstrakurikuler.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-slate-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Edit Ekstrakurikuler</h2>
                <p class="text-sm text-slate-500">Perbarui data {{ $ekstrakurikuler->nama_ekstrakurikuler }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('ekstrakurikuler.update', $ekstrakurikuler) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                <div class="p-6 space-y-5">
                    <div>
                        <label for="nama_ekstrakurikuler" class="block text-sm font-medium text-slate-700 mb-1.5">Nama
                            Ekstrakurikuler</label>
                        <input type="text" id="nama_ekstrakurikuler" name="nama_ekstrakurikuler"
                            value="{{ old('nama_ekstrakurikuler', $ekstrakurikuler->nama_ekstrakurikuler) }}"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nama_ekstrakurikuler') border-red-300 @enderror">
                        @error('nama_ekstrakurikuler')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis" class="block text-sm font-medium text-slate-700 mb-1.5">Jenis</label>
                        <select id="jenis" name="jenis"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('jenis') border-red-300 @enderror">
                            <option value="pilihan" {{ old('jenis', $ekstrakurikuler->jenis) === 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                            <option value="wajib" {{ old('jenis', $ekstrakurikuler->jenis) === 'wajib' ? 'selected' : '' }}>Wajib</option>
                        </select>
                        @error('jenis')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pembina" class="block text-sm font-medium text-slate-700 mb-1.5">Pembina</label>
                        <input type="text" id="pembina" name="pembina"
                            value="{{ old('pembina', $ekstrakurikuler->pembina) }}"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('pembina') border-red-300 @enderror">
                        @error('pembina')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jadwal" class="block text-sm font-medium text-slate-700 mb-1.5">Jadwal</label>
                        <input type="text" id="jadwal" name="jadwal"
                            value="{{ old('jadwal', $ekstrakurikuler->jadwal) }}"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('jadwal') border-red-300 @enderror">
                        @error('jadwal')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sticky bottom-0 bg-white border-t border-gray-100 p-4 flex items-center justify-end gap-3">
                    <a href="{{ route('ekstrakurikuler.index') }}"
                        class="px-5 py-2.5 border border-gray-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>