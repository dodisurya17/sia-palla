<x-app-layout>
    <x-slot name="title">Detail Mata Pelajaran</x-slot>

    <div class="max-w-3xl mx-auto">
        @include('mata-pelajaran.partials.detail', ['mata_pelajaran' => $mata_pelajaran, 'modal' => false])
    </div>
</x-app-layout>