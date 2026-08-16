<x-app-layout>
    <x-slot name="title">Tambah Nilai Akademik</x-slot>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 flex items-center gap-4">
            <a href="{{ route('nilai-akademik.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Tambah Nilai Akademik</h2>
                <p class="text-sm text-slate-500">Masukkan nilai siswa untuk mata pelajaran tertentu</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('nilai-akademik.store') }}" class="p-6">
            @csrf

            @include('nilai-akademik._form')

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('nilai-akademik.index') }}"
                    class="px-5 py-2.5 border border-gray-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</x-app-layout>