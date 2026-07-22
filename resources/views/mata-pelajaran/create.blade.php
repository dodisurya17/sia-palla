<x-app-layout>
    <x-slot name="title">Tambah Mata Pelajaran</x-slot>

    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6 flex items-center gap-4">
            <a href="{{ route('mata-pelajaran.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-slate-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Tambah Mata Pelajaran</h2>
                <p class="text-sm text-slate-500">Lengkapi data mata pelajaran baru</p>
            </div>
        </div>

        <form method="POST" action="{{ route('mata-pelajaran.store') }}">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                <div class="p-6 space-y-5">
                    <div>
                        <label for="kode_mapel" class="block text-sm font-medium text-slate-700 mb-1.5">Kode Mata
                            Pelajaran</label>
                        <input type="text" id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel') }}"
                            placeholder="Contoh: MTK01"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('kode_mapel') border-red-300 @enderror">
                        @error('kode_mapel')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_mapel" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Mata
                            Pelajaran</label>
                        <input type="text" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel') }}"
                            placeholder="Contoh: Matematika"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nama_mapel') border-red-300 @enderror">
                        @error('nama_mapel')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kkm" class="block text-sm font-medium text-slate-700 mb-1.5">KKM (Kriteria Ketuntasan
                            Minimal)</label>
                        <input type="number" id="kkm" name="kkm" value="{{ old('kkm', 75) }}" min="0" max="100"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('kkm') border-red-300 @enderror">
                        @error('kkm')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Footer actions --}}
                <div class="sticky bottom-0 bg-white border-t border-gray-100 p-4 flex items-center justify-end gap-3">
                    <a href="{{ route('mata-pelajaran.index') }}"
                        class="px-5 py-2.5 border border-gray-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan
                    </button>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>