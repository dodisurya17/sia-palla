<x-app-layout :title="'Edit User'">
    <div class="max-w-lg mx-auto bg-white border rounded-lg p-6" x-data="{ role: '{{ old('role', $user->role) }}' }">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-semibold text-slate-800">Edit User</h2>
            <a href="{{ route('user.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Kembali</a>
        </div>

        @if ($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-600">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('user.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                <select name="role" x-model="role" required
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="orang_tua" {{ old('role', $user->role) === 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                </select>
            </div>

            {{-- Dropdown Guru — muncul hanya jika role = guru --}}
            <div x-show="role === 'guru'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Data Guru</label>
                <select name="guru_id"
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">Pilih guru</option>
                    @foreach ($guruList as $guru)
                    <option value="{{ $guru->id }}" {{ old('guru_id', $user->guru_id) == $guru->id ? 'selected' : '' }}>
                        {{ $guru->nama }} — NIP {{ $guru->nip }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Orang Tua — muncul hanya jika role = orang_tua --}}
            <div x-show="role === 'orang_tua'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Data Orang Tua</label>
                <select name="orang_tua_id"
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">Pilih orang tua</option>
                    @foreach ($orangTuaList as $ortu)
                    <option value="{{ $ortu->id }}" {{ old('orang_tua_id', $user->orang_tua_id) == $ortu->id ? 'selected' : '' }}>
                        {{ $ortu->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password baru (opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('user.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-1 px-4 py-2.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 shadow-sm hover:bg-blue-100 hover:border-blue-200 text-sm font-medium">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>