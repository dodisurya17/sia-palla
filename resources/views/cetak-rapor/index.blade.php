<x-app-layout :title="'Cetak Rapor'">

    @php
        $hasPreview = !is_null($rapor);
        $printUrl = $hasPreview && !$rapor['kosong']
            ? route('cetak-rapor.print', ['siswa_id' => $rapor['siswa']['id'], 'semester_id' => $rapor['semester']['id']])
            : null;
    @endphp

    <div class="mx-auto max-w-5xl space-y-6"
        x-data="{
            kelas: @js($kelasOptions),
            siswa: @js($siswaOptions),
            semesters: @js($semesterOptions),
            map: @js($semesterPerSiswa),
            editOpen: {{ old('_edit') ? 'true' : 'false' }},
            kelasId: '',
            siswaId: @js($selectedSiswa),
            semesterId: @js($selectedSemester),

            get filteredSiswa() {
                return this.kelasId
                    ? this.siswa.filter(s => String(s.kelas_id) === String(this.kelasId))
                    : this.siswa;
            },
            get availableSemesters() {
                if (!this.siswaId) return [];
                const ids = (this.map[this.siswaId] || []).map(String);
                return this.semesters.filter(s => ids.includes(String(s.id)));
            },
            get selectedSiswa() {
                return this.siswa.find(s => String(s.id) === String(this.siswaId)) || null;
            },
            onKelasChange() {
                if (this.siswaId && !this.filteredSiswa.some(s => String(s.id) === String(this.siswaId))) {
                    this.siswaId = '';
                    this.semesterId = '';
                }
            },
            onSiswaChange() {
                const av = this.availableSemesters;
                if (!av.some(s => String(s.id) === String(this.semesterId))) {
                    const pick = av.find(s => s.aktif) || av[0];
                    this.semesterId = pick ? String(pick.id) : '';
                }
            },
            get ready() { return this.siswaId && this.semesterId; },
        }">

        {{-- Alur: 1 Pilih Siswa -> 2 Pilih Semester -> 3 Preview -> 4 Cetak --}}
        <ol class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            {{-- Langkah 1 & 2 mengikuti pilihan di form (Alpine), langkah 3 mengikuti ada/tidaknya preview --}}
            <li class="flex items-center gap-3 rounded-2xl bg-white p-3.5 shadow-sm ring-1 ring-slate-900/5">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold transition"
                    :class="siswaId ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400'">1</span>
                <span class="text-sm font-semibold text-slate-700">Pilih Siswa</span>
            </li>
            <li class="flex items-center gap-3 rounded-2xl bg-white p-3.5 shadow-sm ring-1 ring-slate-900/5">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold transition"
                    :class="semesterId ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400'">2</span>
                <span class="text-sm font-semibold text-slate-700">Pilih Semester</span>
            </li>
            <li class="flex items-center gap-3 rounded-2xl bg-white p-3.5 shadow-sm ring-1 ring-slate-900/5">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $hasPreview ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }}">3</span>
                <span class="text-sm font-semibold text-slate-700">Preview Rapor</span>
            </li>
            <li class="flex items-center gap-3 rounded-2xl bg-white p-3.5 shadow-sm ring-1 ring-slate-900/5">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-400">4</span>
                <span class="text-sm font-semibold text-slate-700">Cetak / PDF</span>
            </li>
        </ol>

        {{-- Form pilihan --}}
        <form method="GET" action="{{ route('cetak-rapor.index') }}"
            class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8">

            <div class="mb-6 flex items-start gap-4">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Rapor Siswa</p>
                    <h2 class="text-lg font-bold text-slate-900">Pilih siswa dan semester</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Rapor akan ditampilkan sebagai preview sebelum dicetak atau disimpan sebagai PDF.</p>
                </div>
            </div>

            @if (count($siswaOptions) === 0)
                <div class="rounded-2xl bg-slate-50 px-5 py-8 text-center text-sm text-slate-500 ring-1 ring-slate-900/5">
                    Belum ada data siswa yang bisa dicetak rapornya.
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-3">

                    {{-- Filter kelas --}}
                    <div>
                        <label for="kelas" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kelas</label>
                        <select id="kelas" x-model="kelasId" @change="onKelasChange()"
                            class="w-full rounded-xl border-slate-200 bg-white text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Semua kelas</option>
                            <template x-for="k in kelas" :key="k.id">
                                <option :value="k.id" x-text="k.nama"></option>
                            </template>
                        </select>
                    </div>

                    {{-- 1. Siswa --}}
                    <div>
                        <label for="siswa_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">1. Siswa</label>
                        <select id="siswa_id" name="siswa_id" x-model="siswaId" @change="onSiswaChange()" required
                            class="w-full rounded-xl border-slate-200 bg-white text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Pilih siswa…</option>
                            <template x-for="s in filteredSiswa" :key="s.id">
                                <option :value="s.id" :selected="String(s.id) === String(siswaId)"
                                    x-text="s.nama + ' — ' + s.kelas"></option>
                            </template>
                        </select>
                    </div>

                    {{-- 2. Semester --}}
                    <div>
                        <label for="semester_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">2. Semester</label>
                        <select id="semester_id" name="semester_id" x-model="semesterId" required :disabled="!siswaId"
                            class="w-full rounded-xl border-slate-200 bg-white text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500/20 disabled:bg-slate-50 disabled:text-slate-400">
                            <option value="" x-text="!siswaId ? 'Pilih siswa dulu' : (availableSemesters.length ? 'Pilih semester…' : 'Belum ada nilai')"></option>
                            <template x-for="s in availableSemesters" :key="s.id">
                                <option :value="s.id" :selected="String(s.id) === String(semesterId)"
                                    x-text="s.label + (s.aktif ? ' (aktif)' : '')"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Ringkasan pilihan --}}
                <div x-show="selectedSiswa" x-cloak x-transition
                    class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-1 rounded-2xl bg-slate-50 px-5 py-3 text-sm ring-1 ring-slate-900/5">
                    <span class="text-slate-500">NISN <b class="ml-1 font-semibold text-slate-800" x-text="selectedSiswa?.nisn"></b></span>
                    <span class="text-slate-500">Kelas <b class="ml-1 font-semibold text-slate-800" x-text="selectedSiswa?.kelas"></b></span>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs text-slate-400" x-show="siswaId && availableSemesters.length === 0" x-cloak>
                        Siswa ini belum memiliki nilai akademik pada semester mana pun.
                    </p>
                    <span x-show="!(siswaId && availableSemesters.length === 0)"></span>

                    <button type="submit" :disabled="!ready"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium transition disabled:cursor-not-allowed disabled:bg-slate-100 disabled:border-slate-200 disabled:text-slate-400 disabled:shadow-none disabled:hover:bg-slate-100 disabled:hover:border-slate-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Preview
                    </button>
                </div>
            @endif
        </form>

        {{-- Preview --}}
        @if ($hasPreview)
            <section class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-900/5 sm:p-6">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Preview Rapor</p>
                        <h2 class="truncate text-lg font-bold text-slate-900">
                            {{ $rapor['siswa']['nama'] }}
                            <span class="font-medium text-slate-400">·</span>
                            <span class="font-semibold text-slate-600">{{ $rapor['semester']['jenis'] }} {{ $rapor['semester']['tahun_ajaran'] }}</span>
                        </h2>
                    </div>

                    @if ($printUrl)
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($canEdit)
                                <button type="button" @click="editOpen = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-100 text-amber-700 shadow-sm hover:bg-amber-100 hover:border-amber-200 text-sm font-medium transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                            @endif

                            <a href="{{ $printUrl }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak / Download PDF
                            </a>
                        </div>
                    @endif
                </div>

                @if ($rapor['kosong'])
                    <div class="rounded-2xl bg-amber-50 px-5 py-8 text-center text-sm text-amber-800 ring-1 ring-amber-600/10">
                        Belum ada nilai akademik untuk siswa ini pada semester yang dipilih, sehingga rapor belum bisa dibuat.
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl bg-slate-100 p-3 ring-1 ring-slate-900/5 sm:p-6">
                        <div class="shadow-xl shadow-slate-900/10">
                            @include('cetak-rapor._rapor', ['rapor' => $rapor])
                        </div>
                    </div>
                    <p class="mt-3 text-center text-xs text-slate-400">
                        Pada dialog cetak, pilih printer untuk mencetak atau “Save as PDF” untuk mengunduh PDF.
                    </p>
                @endif
            </section>
        @endif

        {{-- Modal edit rapor (hanya admin & guru) --}}
        @if ($hasPreview && !$rapor['kosong'] && $canEdit)
            @php
                $inp = 'w-full rounded-xl border-slate-200 bg-white text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:ring-blue-500/20';
                $lbl = 'mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500';
                $btnBatal = 'px-4 py-2.5 border border-gray-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition';
                $btnSimpan = 'px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition';
            @endphp

            <template x-teleport="body">
                <div x-show="editOpen" x-cloak @keydown.escape.window="editOpen = false"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0" style="background-color: rgba(15, 23, 42, 0.6);"
                        @click="editOpen = false"></div>

                    <div x-show="editOpen" x-transition
                        class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-auto max-h-[85vh] flex flex-col">

                        <form method="POST" action="{{ route('cetak-rapor.update') }}" class="flex min-h-0 flex-1 flex-col">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="_edit" value="1">
                            <input type="hidden" name="siswa_id" value="{{ $rapor['siswa']['id'] }}">
                            <input type="hidden" name="semester_id" value="{{ $rapor['semester']['id'] }}">

                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 p-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-800">Edit Rapor</h3>
                                        <p class="text-sm text-slate-500">
                                            {{ $rapor['siswa']['nama'] }} · {{ $rapor['semester']['jenis'] }} {{ $rapor['semester']['tahun_ajaran'] }}
                                        </p>
                                    </div>
                                </div>
                                <button type="button" @click="editOpen = false"
                                    class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-slate-400 shadow-sm transition hover:bg-gray-50 hover:text-slate-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Isi --}}
                            <div class="flex-1 space-y-8 overflow-y-auto p-6">

                                @if ($errors->any())
                                    <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-600/10">
                                        Data belum tersimpan. Periksa kembali isian Anda.
                                    </div>
                                @endif

                                {{-- Capaian Kompetensi --}}
                                <section>
                                    <h4 class="text-sm font-semibold text-slate-800">Capaian Kompetensi</h4>
                                    <p class="mb-4 text-xs text-slate-400">Terisi otomatis dari nilai. Ubah bila perlu; kosongkan untuk kembali ke teks otomatis.</p>

                                    <div class="space-y-4">
                                        @foreach ($rapor['nilai'] as $row)
                                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-900/5">
                                                <div class="mb-3 flex items-center justify-between gap-3">
                                                    <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600">
                                                        {{ $row['no'] }}. {{ $row['nama'] }}
                                                    </span>
                                                    <span class="text-xs text-slate-500">Nilai akhir <b class="font-semibold text-slate-800">{{ $row['akhir'] }}</b></span>
                                                </div>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="{{ $lbl }}">Pemahaman</label>
                                                        <textarea rows="2" name="capaian[{{ $row['mapel_id'] }}][paham]"
                                                            data-auto="{{ $row['paham_auto'] }}" maxlength="500"
                                                            class="{{ $inp }}">{{ old('capaian.' . $row['mapel_id'] . '.paham', $row['paham']) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="{{ $lbl }}">Bimbingan</label>
                                                        <textarea rows="2" name="capaian[{{ $row['mapel_id'] }}][bimbingan]"
                                                            data-auto="{{ $row['bimbingan_auto'] }}" maxlength="500"
                                                            class="{{ $inp }}">{{ old('capaian.' . $row['mapel_id'] . '.bimbingan', $row['bimbingan']) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>

                                {{-- Keterangan ekstrakurikuler --}}
                                <section>
                                    <h4 class="text-sm font-semibold text-slate-800">Keterangan Ekstrakurikuler</h4>
                                    <p class="mb-4 text-xs text-slate-400">Kosongkan untuk kembali ke keterangan bawaan.</p>

                                    @forelse ($rapor['ekskul'] as $e)
                                        <div class="mb-3">
                                            <label class="{{ $lbl }}">{{ $e['nama'] }}</label>
                                            <input type="text" name="keterangan_ekskul[{{ $e['ekstrakurikuler_id'] }}]"
                                                data-auto="{{ $e['keterangan_auto'] }}" maxlength="255"
                                                value="{{ old('keterangan_ekskul.' . $e['ekstrakurikuler_id'], $e['keterangan_tampil']) }}"
                                                class="{{ $inp }}">
                                        </div>
                                    @empty
                                        <p class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-500 ring-1 ring-slate-900/5">
                                            Siswa ini belum memiliki nilai ekstrakurikuler pada semester ini.
                                        </p>
                                    @endforelse
                                </section>

                                {{-- Catatan wali kelas --}}
                                <section>
                                    <h4 class="mb-3 text-sm font-semibold text-slate-800">Catatan Wali Kelas</h4>
                                    <textarea rows="3" name="catatan_wali_kelas" maxlength="1000"
                                        placeholder="Tulis catatan wali kelas…"
                                        class="{{ $inp }}">{{ old('catatan_wali_kelas', $rapor['catatan']['wali_kelas']) }}</textarea>
                                </section>

                                {{-- Ketidakhadiran --}}
                                <section>
                                    <h4 class="mb-3 text-sm font-semibold text-slate-800">Ketidakhadiran</h4>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                        @foreach (['sakit' => 'Sakit', 'izin' => 'Izin', 'alpa' => 'Tanpa Keterangan'] as $key => $label)
                                            <div>
                                                <label class="{{ $lbl }}">{{ $label }} (hari)</label>
                                                <input type="number" min="0" max="366" name="{{ $key }}"
                                                    value="{{ old($key, $rapor['catatan'][$key]) }}" placeholder="0"
                                                    class="{{ $inp }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            </div>

                            {{-- Footer --}}
                            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 p-6">
                                <button type="button"
                                    @click="$el.closest('form').querySelectorAll('[data-auto]').forEach(f => f.value = f.dataset.auto)"
                                    class="text-sm font-medium text-slate-500 transition hover:text-slate-700">
                                    Setel ulang capaian &amp; ekskul ke otomatis
                                </button>
                                <div class="flex gap-3">
                                    <button type="button" @click="editOpen = false" class="{{ $btnBatal }}">Batal</button>
                                    <button type="submit" class="{{ $btnSimpan }}">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        @endif
    </div>

</x-app-layout>
