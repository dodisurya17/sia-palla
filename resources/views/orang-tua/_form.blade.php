@php
$isEdit = isset($orangTua);
@endphp

<div class="max-w-3xl mx-auto">
    {{-- Header card --}}
    <div class="flex items-center gap-4 mb-6 bg-white rounded-2xl shadow-sm border p-6">
        <a href="{{ route('orang-tua.index') }}"
            class="w-10 h-10 flex items-center justify-center rounded-full bg-white border hover:bg-gray-50 transition">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <div>
            <h1 class="text-lg font-semibold text-slate-800">
                {{ $isEdit ? 'Edit Data Orang Tua' : 'Tambah Data Orang Tua' }}
            </h1>
            <p class="text-sm text-slate-500">
                {{ $isEdit ? 'Perbarui informasi ' . $orangTua->nama : 'Isi informasi orang tua baru di bawah ini' }}
            </p>
        </div>
    </div>

    <form action="{{ $isEdit ? route('orang-tua.update', $orangTua) : route('orang-tua.store') }}" method="POST">
        @csrf
        @if ($isEdit)
        @method('PUT')
        @endif

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-6 space-y-8">

                {{-- Informasi Pribadi --}}
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Informasi Pribadi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm text-slate-600 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $orangTua->nama ?? '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-400 @enderror">
                            @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-600 mb-1.5">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $orangTua->no_hp ?? '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('no_hp') border-red-400 @enderror">
                            @error('no_hp')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-600 mb-1.5">Pekerjaan</label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $orangTua->pekerjaan ?? '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('pekerjaan') border-red-400 @enderror">
                            @error('pekerjaan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Alamat</h2>
                    <div>
                        <label class="block text-sm text-slate-600 mb-1.5">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('alamat') border-red-400 @enderror">{{ old('alamat', $orangTua->alamat ?? '') }}</textarea>
                        @error('alamat')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Data Anak --}}
                <div
                    x-data="orangTuaAnakForm({
                        kelasList: {{ Js::from($kelasList->map(fn($k) => ['id' => $k->id, 'nama_kelas' => $k->nama_kelas])) }},
                        initialRows: {{ Js::from(old('new_siswa', [])) }}
                    })">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-slate-700">Data Anak</h2>
                        <button type="button" @click="addRow()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-medium hover:bg-indigo-100">
                            + Tambah Anak Baru
                        </button>
                    </div>

                    {{-- Siswa existing yang belum punya orang tua --}}
                    @if ($availableSiswa->isNotEmpty())
                    <div class="mb-6">
                        <p class="text-xs text-slate-500 mb-2">Pilih siswa yang sudah terdaftar untuk dihubungkan sebagai anak:</p>
                        <div class="border rounded-lg divide-y max-h-56 overflow-y-auto">
                            @foreach ($availableSiswa as $siswa)
                            <label class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="existing_siswa_ids[]" value="{{ $siswa->id }}"
                                    {{ in_array($siswa->id, old('existing_siswa_ids', $assignedIds)) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-slate-700">{{ $siswa->nama }}</span>
                                <span class="text-slate-400">· NISN {{ $siswa->nisn }}</span>
                                <span class="ml-auto text-slate-400">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <p class="text-xs text-slate-400 mb-6">Tidak ada siswa yang belum terhubung ke orang tua.</p>
                    @endif

                    {{-- Baris siswa baru (dinamis via Alpine) --}}
                    <template x-for="(row, index) in rows" :key="row.uid">
                        <div class="border rounded-lg p-4 mb-3 relative">
                            <button type="button" @click="removeRow(index)"
                                class="absolute top-3 right-3 text-slate-400 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <p class="text-xs font-medium text-slate-500 mb-3">Anak Baru #<span x-text="index + 1"></span></p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Nama Lengkap</label>
                                    <input type="text" :name="`new_siswa[${index}][nama]`" x-model="row.nama"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">NISN</label>
                                    <input type="text" :name="`new_siswa[${index}][nisn]`" x-model="row.nisn"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Jenis Kelamin</label>
                                    <select :name="`new_siswa[${index}][jenis_kelamin]`" x-model="row.jenis_kelamin"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Kelas</label>
                                    <select :name="`new_siswa[${index}][kelas_id]`" x-model="row.kelas_id"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Pilih Kelas --</option>
                                        <template x-for="kelas in kelasList" :key="kelas.id">
                                            <option :value="kelas.id" x-text="kelas.nama_kelas"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Tempat Lahir</label>
                                    <input type="text" :name="`new_siswa[${index}][tempat_lahir]`" x-model="row.tempat_lahir"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Tanggal Lahir</label>
                                    <input type="date" :name="`new_siswa[${index}][tanggal_lahir]`" x-model="row.tanggal_lahir"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </template>

                    <p x-show="rows.length === 0" class="text-xs text-slate-400">Belum ada anak baru ditambahkan.</p>

                    @error('new_siswa.*.nisn')
                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Sticky footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t sticky bottom-0">
                <a href="{{ route('orang-tua.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 transition-colors">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}
                </button>
            </div>
        </div>
    </form>
    <script>
        function orangTuaAnakForm({
            kelasList = [],
            initialRows = []
        }) {
            return {
                kelasList,
                rows: initialRows.length ?
                    initialRows.map(r => ({
                        ...r,
                        uid: crypto.randomUUID()
                    })) : [],
                addRow() {
                    this.rows.push({
                        uid: crypto.randomUUID(),
                        nama: '',
                        nisn: '',
                        jenis_kelamin: '',
                        kelas_id: '',
                        tempat_lahir: '',
                        tanggal_lahir: '',
                    });
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                },
            };
        }
    </script>
</div>