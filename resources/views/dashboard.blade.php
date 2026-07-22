<x-app-layout :title="'Dashboard'">
    <div class="bg-white border rounded-lg p-10 text-center mb-8">
        <p class="text-sm tracking-widest text-gray-500 mb-2">SELAMAT DATANG</p>
        <h2 class="text-2xl font-semibold">Sistem Informasi Akademik</h2>
        <h3 class="text-xl font-bold uppercase">SMA Katolik Palla</h3>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white border rounded-lg p-5">
            <p class="text-sm text-gray-500">Siswa</p>
            <p class="text-2xl font-bold">{{ $totalSiswa }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5">
            <p class="text-sm text-gray-500">Guru</p>
            <p class="text-2xl font-bold">{{ $totalGuru }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5">
            <p class="text-sm text-gray-500">Orang Tua</p>
            <p class="text-2xl font-bold">{{ $totalOrangTua }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5">
            <p class="text-sm text-gray-500">Kelas</p>
            <p class="text-2xl font-bold">{{ $totalKelas }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5">
            <p class="text-sm text-gray-500">Mata Pelajaran</p>
            <p class="text-2xl font-bold">{{ $totalMapel }}</p>
        </div>
    </div>
</x-app-layout>
