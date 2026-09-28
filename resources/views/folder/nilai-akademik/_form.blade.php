@php
$isEdit = $nilai->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Siswa --}}
    <div>
        <label for="siswa_id" class="block text-sm font-medium text-slate-700 mb-1.5">Siswa</label>
        <select id="siswa_id" name="siswa_id"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('siswa_id') border-red-300 @enderror">
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswa as $s)
            <option value="{{ $s->id }}" {{ old('siswa_id', $nilai->siswa_id) == $s->id ? 'selected' : '' }}>
                {{ $s->nama }} @if($s->nisn) ({{ $s->nisn }}) @endif
            </option>
            @endforeach
        </select>
        @error('siswa_id')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Mata Pelajaran --}}
    <div>
        <label for="mata_pelajaran_id" class="block text-sm font-medium text-slate-700 mb-1.5">Mata Pelajaran</label>
        <select id="mata_pelajaran_id" name="mata_pelajaran_id"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('mata_pelajaran_id') border-red-300 @enderror">
            <option value="">-- Pilih Mata Pelajaran --</option>
            @foreach ($mataPelajaran as $mp)
            <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id', $nilai->mata_pelajaran_id) == $mp->id ? 'selected' : '' }}>
                {{ $mp->kode_mapel }} - {{ $mp->nama_mapel }}
            </option>
            @endforeach
        </select>
        @error('mata_pelajaran_id')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Guru --}}
    <div>
        <label for="guru_id" class="block text-sm font-medium text-slate-700 mb-1.5">Guru Pengampu</label>
        <select id="guru_id" name="guru_id"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('guru_id') border-red-300 @enderror">
            <option value="">-- Pilih Guru --</option>
            @foreach ($guru as $g)
            <option value="{{ $g->id }}" {{ old('guru_id', $nilai->guru_id) == $g->id ? 'selected' : '' }}>
                {{ $g->nama }}
            </option>
            @endforeach
        </select>
        @error('guru_id')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Semester --}}
    <div>
        <label for="semester" class="block text-sm font-medium text-slate-700 mb-1.5">Semester</label>
        <select id="semester" name="semester"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('semester') border-red-300 @enderror">
            <option value="">-- Pilih Semester --</option>
            @foreach (['Ganjil', 'Genap'] as $sem)
            <option value="{{ $sem }}" {{ old('semester', $nilai->semester) == $sem ? 'selected' : '' }}>{{ $sem }}</option>
            @endforeach
        </select>
        @error('semester')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nilai Tugas --}}
    <div>
        <label for="nilai_tugas" class="block text-sm font-medium text-slate-700 mb-1.5">Nilai Tugas</label>
        <input type="number" step="0.01" min="0" max="100" id="nilai_tugas" name="nilai_tugas"
            value="{{ old('nilai_tugas', $nilai->nilai_tugas) }}"
            placeholder="0 - 100"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('nilai_tugas') border-red-300 @enderror">
        @error('nilai_tugas')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nilai UTS --}}
    <div>
        <label for="nilai_uts" class="block text-sm font-medium text-slate-700 mb-1.5">Nilai UTS</label>
        <input type="number" step="0.01" min="0" max="100" id="nilai_uts" name="nilai_uts"
            value="{{ old('nilai_uts', $nilai->nilai_uts) }}"
            placeholder="0 - 100"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('nilai_uts') border-red-300 @enderror">
        @error('nilai_uts')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nilai UAS --}}
    <div>
        <label for="nilai_uas" class="block text-sm font-medium text-slate-700 mb-1.5">Nilai UAS</label>
        <input type="number" step="0.01" min="0" max="100" id="nilai_uas" name="nilai_uas"
            value="{{ old('nilai_uas', $nilai->nilai_uas) }}"
            placeholder="0 - 100"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('nilai_uas') border-red-300 @enderror">
        @error('nilai_uas')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Info nilai akhir --}}
    <div class="md:col-span-2 flex items-center gap-2 px-4 py-3 bg-indigo-50 rounded-xl text-xs text-indigo-600">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Nilai akhir akan dihitung otomatis: 30% Tugas + 30% UTS + 40% UAS.
    </div>
</div>