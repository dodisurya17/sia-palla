<x-app-layout :title="'Edit Siswa'">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6 bg-white rounded-xl px-5 py-4 border border-slate-200 shadow-sm">
            <a href="{{ route('siswa.index') }}"
                class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-slate-800">Edit Siswa</h1>
                <p class="text-sm text-slate-500">Perbarui data siswa {{ $siswa->nama }}.</p>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <form method="POST" action="{{ route('siswa.update', $siswa) }}">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8">
                    @include('siswa._form')
                </div>

                {{-- Sticky action footer --}}
                <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl">
                    <a href="{{ route('siswa.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 active:bg-indigo-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>