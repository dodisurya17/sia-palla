{{--
    Komponen: <x-searchable-select>
    Dropdown dengan pencarian (combobox) untuk data yang banyak (siswa, guru, mapel, dst).

    Props:
      name              nama field yang dikirim ke server (hidden input)
      label             teks label
      options           array/collection: [['value'=>1,'label'=>'Nama','sub'=>'NISN ...','badge'=>'X IPA 1'], ...]
      value             nilai terpilih (otomatis memakai old() jika ada)
      placeholder       teks saat belum memilih
      searchPlaceholder placeholder kolom pencarian
      noun              kata benda untuk teks bantuan ("siswa", "guru", ...)
      avatar            true = tampilkan inisial nama sebagai avatar
      required          true = tampilkan tanda *
      limit             jumlah maksimal baris yang dirender sekali waktu (default 50)
--}}
@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'placeholder' => 'Pilih...',
    'searchPlaceholder' => 'Ketik untuk mencari...',
    'noun' => 'data',
    'avatar' => false,
    'required' => false,
    'limit' => 50,
])

@php
    $id = $attributes->get('id', $name);
    $terpilih = old($name, $value);
    $adaError = $errors->has($name);
@endphp

<div x-data="searchableSelect({
        options: @js($options),
        value: @js($terpilih !== null ? (string) $terpilih : ''),
        limit: {{ (int) $limit }},
        avatar: @js((bool) $avatar),
        noun: @js($noun),
    })"
    :class="open ? 'z-30' : ''"
    class="relative">

    <label for="{{ $id }}-trigger" class="mb-1.5 block text-sm font-medium text-slate-700">
        {{ $label }}@if ($required)<span class="ml-0.5 text-red-500">*</span>@endif
    </label>

    <input type="hidden" name="{{ $name }}" :value="value">

    {{-- Trigger --}}
    <div class="relative">
        <button type="button" id="{{ $id }}-trigger" x-ref="trigger"
            role="combobox" aria-haspopup="listbox" :aria-expanded="open"
            @click="toggle()"
            @keydown.arrow-down.prevent="open || show()"
            @keydown="typeahead($event)"
            :class="open ? 'border-blue-300 bg-white ring-2 ring-blue-500/30' : '{{ $adaError ? 'border-red-300' : 'border-slate-200' }} bg-slate-50 hover:border-slate-300'"
            class="flex h-11 w-full items-center gap-2.5 rounded-xl border pl-3 pr-16 text-left text-sm outline-none transition focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-500/30">

            <template x-if="selected">
                <span class="flex min-w-0 flex-1 items-center gap-2.5">
                    <span x-show="avatar" x-text="initials(selected.label)"
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-50 text-[11px] font-semibold text-blue-600"></span>
                    <span class="truncate font-medium text-slate-800" x-text="selected.label"></span>
                    <span x-show="selected.sub" x-text="selected.sub" class="hidden truncate text-xs text-slate-400 sm:inline"></span>
                    <span x-show="selected.badge" x-text="selected.badge"
                        class="shrink-0 rounded-md bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-600"></span>
                </span>
            </template>
            <span x-show="!selected" class="flex-1 truncate text-slate-400">{{ $placeholder }}</span>
        </button>

        {{-- Hapus pilihan --}}
        <button type="button" x-show="selected" x-cloak @click="clear()" aria-label="Hapus pilihan"
            class="absolute right-9 top-1/2 flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <svg class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
    </div>

    {{-- Panel --}}
    <div x-show="open" x-cloak x-transition.opacity.duration.100ms
        @click.outside="close()"
        :class="dropUp ? 'bottom-full mb-2' : 'top-full mt-2'"
        class="absolute left-0 right-0 z-30 overflow-hidden rounded-xl bg-white shadow-xl shadow-slate-900/10 ring-1 ring-slate-900/5">

        {{-- Pencarian --}}
        <div class="flex items-center gap-2 border-b border-slate-100 px-3">
            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input type="text" x-ref="search" x-model="query" autocomplete="off" spellcheck="false"
                placeholder="{{ $searchPlaceholder }}"
                @keydown.arrow-down.prevent="move(1)"
                @keydown.arrow-up.prevent="move(-1)"
                @keydown.enter.prevent="choose(results[active])"
                @keydown.escape.prevent.stop="close(true)"
                @keydown.tab="close()"
                class="h-11 w-full min-w-0 border-0 bg-transparent p-0 text-base outline-none ring-0 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-sm">
            <button type="button" x-show="query" @click="query = ''; $refs.search.focus()" aria-label="Hapus pencarian"
                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-slate-400 transition hover:text-slate-600">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Daftar --}}
        <ul x-ref="list" role="listbox" class="max-h-64 overflow-y-auto overscroll-contain py-1">
            <template x-for="(o, i) in results" :key="o.value">
                <li role="option" :aria-selected="o.value === String(value)" :data-active="i === active"
                    @click="choose(o)" @mousemove="hover(i, $event)"
                    :class="i === active ? 'bg-blue-50' : ''"
                    class="flex cursor-pointer items-center gap-3 px-3 py-2 text-sm">
                    <span x-show="avatar" x-text="initials(o.label)"
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600"></span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate font-medium text-slate-800" x-html="hl(o.label)"></span>
                        <span x-show="o.sub" class="block truncate text-xs text-slate-400" x-html="hl(o.sub)"></span>
                    </span>
                    <span x-show="o.badge" x-html="hl(o.badge)"
                        class="shrink-0 rounded-md bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-600"></span>
                    <svg x-show="o.value === String(value)" class="h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </li>
            </template>
        </ul>

        {{-- Kosong --}}
        <div x-show="!results.length" class="px-4 py-8 text-center text-sm text-slate-400">
            <svg class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            Tidak ada <span x-text="noun"></span> yang cocok<span x-show="query"> dengan &ldquo;<span class="font-medium text-slate-600" x-text="query"></span>&rdquo;</span>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-3 py-2 text-[11px] text-slate-400">
            <span x-text="footerText"></span>
            <span class="hidden shrink-0 sm:inline">&uarr;&darr; pilih &middot; Enter konfirmasi &middot; Esc tutup</span>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
            const reEsc = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const norm = (s) => String(s ?? '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, ' ').trim();
            const rupiahFmt = (n) => Number(n).toLocaleString('id-ID');

            Alpine.data('searchableSelect', (cfg) => {
                // Disimpan di luar state reaktif supaya 1000+ data tetap ringan.
                const all = Object.freeze((cfg.options || []).map((o) => Object.freeze({
                    ...o,
                    value: String(o.value),
                    _l: norm(o.label),
                    _k: norm([o.label, o.sub, o.badge].filter(Boolean).join(' ')),
                })));

                return {
                    avatar: !!cfg.avatar,
                    limit: cfg.limit || 50,
                    noun: cfg.noun || 'data',
                    value: cfg.value ?? '',
                    open: false,
                    dropUp: false,
                    query: '',
                    active: 0,
                    results: [],
                    total: all.length,
                    tokens: [],

                    init() {
                        this.search();
                        this.$watch('query', () => this.search());
                    },

                    get selected() {
                        return all.find((o) => o.value === String(this.value)) || null;
                    },

                    get footerText() {
                        if (!this.total) return '';
                        return this.total > this.results.length
                            ? `Menampilkan ${this.results.length} dari ${rupiahFmt(this.total)} ${this.noun} \u2014 ketik untuk mempersempit`
                            : `${rupiahFmt(this.total)} ${this.noun}`;
                    },

                    search() {
                        const raw = this.query.trim();
                        const q = norm(raw).split(' ').filter(Boolean);
                        this.tokens = raw.toLowerCase().split(/\s+/).filter(Boolean);

                        let list = all;
                        if (q.length) {
                            list = all.filter((o) => q.every((t) => o._k.includes(t)));
                            // Nama yang diawali kata kunci tampil lebih dulu
                            list = [...list].sort((a, b) => Number(b._l.startsWith(q[0])) - Number(a._l.startsWith(q[0])));
                        }

                        this.total = list.length;
                        this.results = list.slice(0, this.limit);

                        const idx = q.length ? -1 : this.results.findIndex((o) => o.value === String(this.value));
                        this.active = idx >= 0 ? idx : 0;
                        if (this.$refs.list) this.$refs.list.scrollTop = 0;
                    },

                    toggle() { this.open ? this.close() : this.show(); },

                    show() {
                        this.query = '';
                        this.search();
                        const r = this.$refs.trigger.getBoundingClientRect();
                        const bawah = window.innerHeight - r.bottom;
                        this.dropUp = bawah < 360 && r.top > bawah;
                        this.open = true;
                        this.$nextTick(() => {
                            this.$refs.search.focus({ preventScroll: true });
                            this.reveal();
                        });
                    },

                    close(refocus = false) {
                        this.open = false;
                        if (refocus) this.$refs.trigger.focus();
                    },

                    choose(o) {
                        if (!o) return;
                        this.value = o.value;
                        this.close(true);
                    },

                    clear() {
                        this.value = '';
                        this.$refs.trigger.focus();
                    },

                    move(d) {
                        const n = this.results.length;
                        if (!n) return;
                        this.active = (this.active + d + n) % n;
                        this.reveal();
                    },

                    hover(i, e) {
                        if (e.movementX || e.movementY) this.active = i;
                    },

                    reveal() {
                        this.$nextTick(() => {
                            this.$refs.list?.querySelector('[data-active="true"]')?.scrollIntoView({ block: 'nearest' });
                        });
                    },

                    // Mengetik langsung saat trigger fokus => panel terbuka dengan kata kunci tsb.
                    typeahead(e) {
                        if (this.open || e.ctrlKey || e.metaKey || e.altKey || e.key.length !== 1 || e.key === ' ') return;
                        e.preventDefault();
                        this.show();
                        this.query = e.key;
                    },

                    initials(nama) {
                        return String(nama || '').trim().split(/\s+/).slice(0, 2).map((w) => w.charAt(0)).join('').toUpperCase();
                    },

                    hl(text) {
                        text = String(text ?? '');
                        if (!this.tokens.length) return esc(text);
                        const re = new RegExp('(' + this.tokens.map(reEsc).join('|') + ')', 'gi');
                        return text.split(re).map((p, i) => (i % 2 ? '<mark class="rounded-sm bg-yellow-100 text-inherit">' + esc(p) + '</mark>' : esc(p))).join('');
                    },
                };
            });
        });
    </script>
@endonce
