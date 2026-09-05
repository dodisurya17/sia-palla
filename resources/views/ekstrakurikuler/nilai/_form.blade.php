<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

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

    <div>
        <label for="ekstrakurikuler_id" class="block text-sm font-medium text-slate-700 mb-1.5">Ekstrakurikuler</label>
        <select id="ekstrakurikuler_id" name="ekstrakurikuler_id"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('ekstrakurikuler_id') border-red-300 @enderror">
            <option value="">-- Pilih Ekstrakurikuler --</option>
            @foreach ($ekstrakurikuler as $e)
            <option value="{{ $e->id }}" {{ old('ekstrakurikuler_id', $nilai->ekstrakurikuler_id) == $e->id ? 'selected' : '' }}>
                {{ $e->nama_ekstrakurikuler }}
            </option>
            @endforeach
        </select>
        @error('ekstrakurikuler_id')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

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

    <div>
        <label for="predikat" class="block text-sm font-medium text-slate-700 mb-1.5">Predikat</label>
        <select id="predikat" name="predikat"
            class="w-full h-11 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('predikat') border-red-300 @enderror">
            <option value="">-- Pilih Predikat --</option>
            @foreach (['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $p)
            <option value="{{ $p }}" {{ old('predikat', $nilai->predikat) == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        @error('predikat')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan (opsional)</label>
        <textarea id="keterangan" name="keterangan" rows="3"
            placeholder="Contoh: Aktif mengikuti latihan dan lomba tingkat kabupaten"
            class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300 outline-none transition @error('keterangan') border-red-300 @enderror">{{ old('keterangan', $nilai->keterangan) }}</textarea>
        @error('keterangan')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>