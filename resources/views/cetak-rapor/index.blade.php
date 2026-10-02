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
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none">
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
                        <a href="{{ $printUrl }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak / Download PDF
                        </a>
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
    </div>

</x-app-layout>
