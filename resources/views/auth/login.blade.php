<x-guest-layout>
    {{-- Brand — mobile only --}}
    <div class="mb-8 flex items-center gap-3 lg:hidden">
        <img src="{{ asset('images/logo.jpg') }}" alt="YAPNUSDA" class="h-11 w-11 rounded-lg object-cover ring-1 ring-slate-200">
        <div>
            <p class="text-sm font-semibold text-slate-900">Sistem Informasi Akademik</p>
            <p class="text-xs text-slate-400">Portal Akademik YAPNUSDA</p>
        </div>
    </div>

    <div class="mb-8 hidden lg:block">
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Masuk</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-900">Selamat datang kembali</h2>
        <p class="mt-1.5 text-sm text-slate-500">Masuk untuk mengakses sistem informasi akademik Anda.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-input-error :messages="$errors->all()" class="mb-4" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
        @csrf

        {{-- Username / Email --}}
        <div>
            <label for="email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                Username / Email
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0-.83.67-1.5 1.5-1.5h16.5c.83 0 1.5.67 1.5 1.5v10.5c0 .83-.67 1.5-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V6.75Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6" />
                    </svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="email@akademik.ac.id"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-3.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                Password
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5h-10.5a1.5 1.5 0 0 1-1.5-1.5v-7.5a1.5 1.5 0 0 1 1.5-1.5Z" />
                    </svg>
                </span>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                    placeholder="••••••••"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-10 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                    <svg x-show="!showPassword" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="showPassword" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember + forgot --}}
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/40">
                Ingat saya
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:from-blue-700 hover:to-blue-800 hover:shadow-blue-600/35 focus:outline-none focus:ring-4 focus:ring-blue-500/30 active:scale-[0.99]">
            Login
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>
</x-guest-layout>