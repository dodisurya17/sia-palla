<x-app-layout :title="'Detail Siswa'">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 bg-white rounded-xl px-5 py-4 border border-slate-200 shadow-sm">
            <a href="{{ route('siswa.index') }}"
                class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-slate-800">Detail Siswa</h1>
                <p class="text-sm text-slate-500">Informasi lengkap data siswa.</p>
            </div>

            <a href="{{ route('siswa.edit', $siswa) }}"
                class="ml-auto inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
                Edit
            </a>
        </div>

        {{-- Card: Profil --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="flex items-center gap-4 p-6 sm:p-8">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.866 0-9 1.79-9 5v2h18v-2c0-3.21-5.134-5-9-5z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-800">{{ $siswa->nama }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">NISN: {{ $siswa->nisn }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Data Personal --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="p-6 sm:p-8 space-y-8">

                {{-- Section: Data Personal --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Data Personal</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                        <div>
                            <p class="text-slate-500 mb-1">Jenis Kelamin</p>
                            <p class="font-medium text-slate-700">
                                {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Tempat, Tanggal Lahir</p>
                            <p class="font-medium text-slate-700">
                                {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir?->format('d-m-Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Kelas</p>
                            <p class="font-medium text-slate-700">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-slate-500 mb-1">Alamat</p>
                            <p class="font-medium text-slate-700">{{ $siswa->alamat }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100"></div>

                {{-- Section: Data Orang Tua --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Data Orang Tua</h3>
                    </div>

                    <div class="text-sm">
                        <p class="text-slate-500 mb-1">Nama Orang Tua</p>
                        <p class="font-medium text-slate-700">{{ $siswa->orangTua->nama ?? '-' }}</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Card: Nilai Akademik --}}
        @php
        $nilaiPerSemester = $siswa->nilaiAkademik->groupBy('semester');
        $semesterList = ['Ganjil', 'Genap'];
        @endphp

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm" x-data="{ tab: 'Ganjil' }">
            <div class="p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Nilai Akademik</h3>
                </div>

                {{-- Tabs --}}
                <div class="flex gap-1 mb-5 bg-slate-100 rounded-lg p-1 w-fit">
                    @foreach ($semesterList as $s)
                    <button type="button"
                        @click="tab = '{{ $s }}'"
                        :class="tab === '{{ $s }}' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-1.5 text-sm font-medium rounded-md transition-colors">
                        Semester {{ $s }}
                    </button>
                    @endforeach
                </div>

                {{-- Konten per semester --}}
                @foreach ($semesterList as $s)
                <div x-show="tab === '{{ $s }}'" x-cloak>
                    <div class="overflow-hidden rounded-xl border border-slate-100">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-slate-500 bg-slate-50 text-left">
                                    <th class="px-4 py-2.5 font-medium">Mapel</th>
                                    <th class="px-4 py-2.5 font-medium">Semester</th>
                                    <th class="px-4 py-2.5 font-medium">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($nilaiPerSemester->get($s, []) as $n)
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $n->mataPelajaran->nama_mapel }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $n->semester }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $n->nilai_akhir }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-10">
                                        <div class="flex flex-col items-center justify-center text-slate-400 gap-2">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m-9 4h13a2 2 0 002-2V7.914a2 2 0 00-.586-1.414l-3.914-3.914A2 2 0 0012.086 2H6a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                <circle cx="17" cy="17" r="4" stroke-linecap="round" stroke-linejoin="round" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 20l-1.5-1.5" />
                                            </svg>
                                            <p class="text-sm">Nilai akademik untuk Semester {{ $s }} belum tersedia.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>