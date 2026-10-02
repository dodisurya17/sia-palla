@php
    // Helper aman: baca atribut hanya jika memang ada di model (tanpa memicu query / error).
    $attr = fn ($model, $key) => array_key_exists($key, $model->getAttributes()) ? $model->getAttribute($key) : null;

    $fmtNilai = fn ($n) => ($n === null || $n === '') ? '' : rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');

    // ---- Opsi siswa (nama, NISN, kelas) ----
    $siswaOptions = collect($siswa)->map(function ($s) use ($attr) {
        $kelas = $attr($s, 'kelas_nama');
        if (! $kelas && $s->relationLoaded('kelas')) {
            $kelas = data_get($s, 'kelas.nama_kelas') ?? data_get($s, 'kelas.nama');
        }

        return [
            'value' => (string) $s->id,
            'label' => $s->nama,
            'sub'   => $s->nisn ? 'NISN ' . $s->nisn : null,
            'badge' => $kelas ?: null,
        ];
    })->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)->values();

    // ---- Opsi mata pelajaran ----
    $mapelOptions = collect($mataPelajaran)->map(fn ($mp) => [
        'value' => (string) $mp->id,
        'label' => $mp->nama_mapel,
        'sub'   => null,
        'badge' => $mp->kode_mapel,
    ])->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)->values();

    // ---- Opsi guru ----
    $guruOptions = collect($guru)->map(fn ($g) => [
        'value' => (string) $g->id,
        'label' => $g->nama,
        'sub'   => ($nip = $attr($g, 'nip')) ? 'NIP ' . $nip : null,
        'badge' => null,
    ])->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)->values();

    $inputClass = 'h-11 w-full rounded-xl border bg-slate-50 px-4 text-base text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-500/30 sm:text-sm';

    $nilaiFields = [
        ['name' => 'nilai_tugas', 'label' => 'Nilai Tugas', 'bobot' => '30%', 'model' => 't'],
        ['name' => 'nilai_uts',   'label' => 'Nilai UTS',   'bobot' => '30%', 'model' => 'u'],
        ['name' => 'nilai_uas',   'label' => 'Nilai UAS',   'bobot' => '40%', 'model' => 'a'],
    ];

    $nilaiAwal = [
        't' => old('nilai_tugas', $fmtNilai($nilai->nilai_tugas)),
        'u' => old('nilai_uts', $fmtNilai($nilai->nilai_uts)),
        'a' => old('nilai_uas', $fmtNilai($nilai->nilai_uas)),
    ];
@endphp

@if ($errors->any())
    <div class="mb-6 flex items-start gap-2.5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-600/10">
        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg>
        Periksa kembali isian yang ditandai merah di bawah.
    </div>
@endif

{{-- ================= 1. DATA PENILAIAN ================= --}}
<section>
    <div class="mb-4 flex items-center gap-3">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">1</span>
        <h3 class="text-xs font-semibold uppercase tracking-widest text-slate-400">Data Penilaian</h3>
        <span class="h-px flex-1 bg-slate-100"></span>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-searchable-select name="siswa_id" label="Siswa" :options="$siswaOptions"
                :value="$nilai->siswa_id" avatar required noun="siswa"
                placeholder="Cari nama, NISN, atau kelas..."
                search-placeholder="Ketik nama, NISN, atau kelas..." />
            @error('siswa_id')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-searchable-select name="mata_pelajaran_id" label="Mata Pelajaran" :options="$mapelOptions"
                :value="$nilai->mata_pelajaran_id" required noun="mata pelajaran"
                placeholder="Pilih mata pelajaran"
                search-placeholder="Ketik nama atau kode mapel..." />
            @error('mata_pelajaran_id')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-searchable-select name="guru_id" label="Guru Pengampu" :options="$guruOptions"
                :value="$nilai->guru_id" avatar required noun="guru"
                placeholder="Pilih guru pengampu"
                search-placeholder="Ketik nama guru..." />
            @error('guru_id')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Semester: jumlahnya sedikit, cukup select biasa --}}
        <div>
            <label for="semester_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                Semester<span class="ml-0.5 text-red-500">*</span>
            </label>
            <div class="relative">
                <select id="semester_id" name="semester_id"
                    class="{{ $inputClass }} appearance-none pr-10 {{ $errors->has('semester_id') ? 'border-red-300' : 'border-slate-200' }}">
                    <option value="">Pilih semester</option>
                    @foreach ($semester as $sem)
                        <option value="{{ $sem->id }}" {{ old('semester_id', $nilai->semester_id) == $sem->id ? 'selected' : '' }}>
                            {{ $sem->label }}{{ $sem->status === 'aktif' ? ' (Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </div>
            @error('semester_id')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

{{-- ================= 2. NILAI ================= --}}
<section class="mt-8"
    x-data="{
        t: @js((string) $nilaiAwal['t']),
        u: @js((string) $nilaiAwal['u']),
        a: @js((string) $nilaiAwal['a']),
        num(v) { const n = parseFloat(String(v).replace(',', '.')); return isNaN(n) ? null : n; },
        bad(v) { const n = this.num(v); return n !== null && (n < 0 || n > 100); },
        get lengkap() { return [this.t, this.u, this.a].every(v => this.num(v) !== null) && !this.anyBad; },
        get anyBad() { return this.bad(this.t) || this.bad(this.u) || this.bad(this.a); },
        get akhir() { return this.lengkap ? this.num(this.t) * 0.3 + this.num(this.u) * 0.3 + this.num(this.a) * 0.4 : null; },
        fmt(n) { return n === null ? '—' : (Math.round(n * 100) / 100).toLocaleString('id-ID'); },
        tone(n) {
            if (n === null) return { chip: 'bg-white text-slate-300 ring-slate-200', bar: 'bg-slate-300' };
            if (n >= 75) return { chip: 'bg-green-50 text-green-700 ring-green-600/10', bar: 'bg-green-500' };
            if (n >= 60) return { chip: 'bg-amber-50 text-amber-700 ring-amber-600/10', bar: 'bg-amber-500' };
            return { chip: 'bg-red-50 text-red-700 ring-red-600/10', bar: 'bg-red-500' };
        },
    }">

    <div class="mb-4 flex items-center gap-3">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">2</span>
        <h3 class="text-xs font-semibold uppercase tracking-widest text-slate-400">Nilai (0 &ndash; 100)</h3>
        <span class="h-px flex-1 bg-slate-100"></span>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        @foreach ($nilaiFields as $f)
            <div>
                <label for="{{ $f['name'] }}" class="mb-1.5 flex items-center justify-between text-sm font-medium text-slate-700">
                    {{ $f['label'] }}
                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-500">{{ $f['bobot'] }}</span>
                </label>
                <div class="relative">
                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal"
                        id="{{ $f['name'] }}" name="{{ $f['name'] }}" x-model="{{ $f['model'] }}"
                        placeholder="0 - 100"
                        :class="bad({{ $f['model'] }}) ? 'border-red-300' : '{{ $errors->has($f['name']) ? 'border-red-300' : 'border-slate-200' }}'"
                        class="{{ $inputClass }} pr-12 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400">/100</span>
                </div>
                <p x-show="bad({{ $f['model'] }})" x-cloak class="mt-1.5 text-xs text-red-500">Nilai harus antara 0 dan 100.</p>
                @error($f['name'])
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>

    {{-- Pratinjau nilai akhir --}}
    <div class="mt-5 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-900/5 sm:p-5">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Nilai Akhir</p>
                <p class="mt-1 text-xs text-slate-500">30% Tugas + 30% UTS + 40% UAS, dihitung otomatis saat disimpan.</p>
            </div>
            <span class="inline-flex min-w-[4.5rem] shrink-0 justify-center rounded-xl px-4 py-2 text-2xl font-bold ring-1 transition"
                :class="tone(akhir).chip" x-text="fmt(akhir)"></span>
        </div>
        <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-slate-200">
            <div class="h-full rounded-full transition-all duration-300" :class="tone(akhir).bar" :style="`width: ${akhir === null ? 0 : Math.min(100, akhir)}%`"></div>
        </div>
        <p x-show="!lengkap && !anyBad" class="mt-3 text-xs text-slate-400">Isi ketiga nilai untuk melihat perkiraan nilai akhir.</p>
    </div>
</section>
