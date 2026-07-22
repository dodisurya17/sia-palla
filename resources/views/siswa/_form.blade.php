@php $siswa = $siswa ?? null; @endphp

@php
$inputClass = "w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-700 placeholder:text-slate-400
focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors";
$selectClass = "w-full appearance-none border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-700 bg-white
focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors";
$labelClass = "block text-sm font-medium text-slate-700 mb-1.5";
@endphp

<div class="space-y-8">

    {{-- Section: Informasi Pribadi --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Informasi Pribadi</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">NISN <span class="text-red-500">*</span></label>
                <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}"
                    placeholder="Contoh: 0051234567"
                    class="{{ $inputClass }} @error('nisn') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror">
                @error('nisn') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $siswa->nama ?? '') }}"
                    placeholder="Nama lengkap siswa"
                    class="{{ $inputClass }} @error('nama') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror">
                @error('nama') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Jenis Kelamin</label>
                <div class="relative">
                    <select name="jenis_kelamin" class="{{ $selectClass }}">
                        <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'P')>Perempuan</option>
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', $siswa?->tanggal_lahir?->format('Y-m-d') ?? '') }}"
                    class="{{ $inputClass }}">
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $labelClass }}">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir ?? '') }}"
                    placeholder="Contoh: Jakarta"
                    class="{{ $inputClass }}">
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100"></div>

    {{-- Section: Alamat --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Alamat</h2>
        </div>

        <div>
            <label class="{{ $labelClass }}">Alamat Lengkap</label>
            <textarea name="alamat" rows="3" placeholder="Nama jalan, RT/RW, kelurahan, kecamatan, kota"
                class="{{ $inputClass }} resize-none">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
        </div>
    </div>

    <div class="border-t border-slate-100"></div>

    {{-- Section: Data Akademik --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Data Akademik</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Kelas</label>
                <div class="relative">
                    <select name="kelas_id" class="{{ $selectClass }}">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(old('kelas_id', $siswa->kelas_id ?? '') == $k->id)>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Orang Tua</label>
                <div class="relative">
                    <select name="orang_tua_id" class="{{ $selectClass }}">
                        <option value="">Pilih Orang Tua</option>
                        @foreach ($orangTua as $ot)
                        <option value="{{ $ot->id }}" @selected(old('orang_tua_id', $siswa->orang_tua_id ?? '') == $ot->id)>{{ $ot->nama }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

</div>