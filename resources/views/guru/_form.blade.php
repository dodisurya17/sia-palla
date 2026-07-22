@php
$isEdit = isset($guru);
@endphp

<div class="max-w-3xl mx-auto">
    {{-- Header card --}}
    <div class="flex items-center gap-4 mb-6 bg-white rounded-2xl shadow-sm border p-6">
        <a href="{{ route('guru.index') }}"
            class="w-10 h-10 flex items-center justify-center rounded-full bg-white border hover:bg-gray-50 transition">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h16v11H4V10z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4z" />
            </svg>
        </div>

        <div>
            <h1 class="text-lg font-semibold text-slate-800">
                {{ $isEdit ? 'Edit Data Guru' : 'Tambah Data Guru' }}
            </h1>
            <p class="text-sm text-slate-500">
                {{ $isEdit ? 'Perbarui informasi guru ' . $guru->nama : 'Isi informasi guru baru di bawah ini' }}
            </p>
        </div>
    </div>

    <form action="{{ $isEdit ? route('guru.update', $guru) : route('guru.store') }}" method="POST">
        @csrf
        @if ($isEdit)
        @method('PUT')
        @endif

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-6 space-y-8">

                {{-- Informasi Pribadi --}}
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Informasi Pribadi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm text-slate-600 mb-1.5">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $guru->nip ?? '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nip') border-red-400 @enderror">
                            @error('nip')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-600 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $guru->nama ?? '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-400 @enderror">
                            @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-600 mb-1.5">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $guru->no_hp ?? '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('no_hp') border-red-400 @enderror">
                            @error('no_hp')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Alamat</h2>
                    <div>
                        <label class="block text-sm text-slate-600 mb-1.5">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('alamat') border-red-400 @enderror">{{ old('alamat', $guru->alamat ?? '') }}</textarea>
                        @error('alamat')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Data Mengajar --}}
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Data Mengajar</h2>
                    <div>
                        <label class="block text-sm text-slate-600 mb-1.5">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('mata_pelajaran_id') border-red-400 @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mataPelajarans as $mp)
                            <option value="{{ $mp->id }}"
                                {{ old('mata_pelajaran_id', $guru->mata_pelajaran_id ?? '') == $mp->id ? 'selected' : '' }}>
                                {{ $mp->kode_mapel }} - {{ $mp->nama_mapel }}
                            </option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Sticky footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t sticky bottom-0">
                <a href="{{ route('guru.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 transition-colors">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}
                </button>
            </div>
        </div>
    </form>
</div>