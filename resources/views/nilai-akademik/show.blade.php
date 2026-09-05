<x-app-layout>
    <x-slot name="title">Detail Nilai Akademik</x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center gap-4">
            <a href="{{ route('nilai-akademik.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-slate-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-lg font-semibold text-slate-800">Detail Nilai Akademik</h2>
        </div>

        @include('nilai-akademik.partials.detail')
    </div>
</x-app-layout>