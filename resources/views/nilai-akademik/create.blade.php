<x-app-layout>
    <x-slot name="title">Tambah Nilai Akademik</x-slot>

    {{-- Tanpa overflow-hidden agar dropdown pencarian tidak terpotong --}}
    <div class="mx-auto max-w-4xl rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">

        {{-- Header --}}
        <div class="flex items-center gap-4 border-b border-slate-100 p-5 sm:p-6">
            <a href="{{ route('nilai-akademik.index') }}" title="Kembali"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-slate-900">Tambah Nilai Akademik</h2>
                <p class="text-sm text-slate-500">Masukkan nilai siswa untuk mata pelajaran tertentu</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('nilai-akademik.store') }}"
            x-data="{ submitting: false }"
            @submit="setTimeout(() => submitting = true, 0)"
            @pageshow.window="submitting = false">
            @csrf

            <div class="p-5 sm:p-6">
                @include('nilai-akademik._form')
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-end sm:p-6">
                <a href="{{ route('nilai-akademik.index') }}"
                    class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" name="lanjut" value="1" :disabled="submitting"
                    class="inline-flex items-center justify-center rounded-full border border-blue-100 bg-blue-50 px-5 py-2.5 text-sm font-medium text-blue-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-100 disabled:opacity-60">
                    Simpan &amp; Tambah Lagi
                </button>
                <button type="submit" :disabled="submitting"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 disabled:opacity-60">
                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
