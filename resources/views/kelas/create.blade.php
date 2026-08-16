<x-app-layout>
    <x-slot name="title">Tambah Kelas</x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0l2 0M5 21l-2 0M9 7h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Tambah Kelas</h2>
                <p class="text-sm text-slate-500">Lengkapi data kelas baru di bawah ini</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('kelas.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Nama Kelas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}"
                    placeholder="Contoh: VII-A"
                    class="w-full h-11 px-4 text-sm rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition @error('nama_kelas') border-red-300 @enderror">
                @error('nama_kelas')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tingkat" class="block text-sm font-medium text-slate-700 mb-1.5">Tingkat</label>
                <input type="text" name="tingkat" id="tingkat" value="{{ old('tingkat') }}"
                    placeholder="Contoh: VII"
                    class="w-full h-11 px-4 text-sm rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition @error('tingkat') border-red-300 @enderror">
                @error('tingkat')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="wali_kelas" class="block text-sm font-medium text-slate-700 mb-1.5">Wali Kelas</label>
                <input type="text" name="wali_kelas" id="wali_kelas" value="{{ old('wali_kelas') }}"
                    placeholder="Nama wali kelas"
                    class="w-full h-11 px-4 text-sm rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition @error('wali_kelas') border-red-300 @enderror">
                @error('wali_kelas')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('kelas.index') }}"
                    class="px-5 py-2.5 border border-gray-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</x-app-layout>