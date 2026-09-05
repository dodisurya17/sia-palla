<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - Sistem Informasi Akademik</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans text-slate-900 antialiased bg-gray-200">

    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

        {{-- Backdrop (mobile only) --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-64 flex-shrink-0 bg-white flex flex-col border-r border-slate-900/5
                   transform transition-transform duration-200 ease-in-out
                   lg:static lg:translate-x-0 lg:z-auto">

            {{-- Brand --}}
            <div class="flex items-center gap-3 px-6 h-24 border-b border-slate-900/5">
                <img src="{{ asset('images/logo.jpg') }}" alt="YAPNUSDA"
                    class="h-11 w-11 rounded-sm object-cover ring-1 ring-slate-200">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Sia-Palla</p>
                    <p class="text-sm font-bold text-slate-900 leading-tight">Sistem Akademik</p>
                </div>
            </div>

            <nav class="flex-1 py-4">
                @php
                $userRole = auth()->user()->role;

                $menus = [
                [
                'route' => 'dashboard',
                'label' => 'Dashboard',
                'icon' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10',
                'roles' => ['admin', 'guru', 'orang_tua'],
                ],
                [
                'route' => 'user.index',
                'label' => 'Manajemen User',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'roles' => ['admin'],
                ],
                [
                'route' => 'siswa.index',
                'label' => 'Siswa',
                'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                'roles' => ['admin'],
                ],
                [
                'route' => 'guru.index',
                'label' => 'Guru',
                'icon' => 'M8 14v.5M12 14v.5M16 14v.5M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z',
                'roles' => ['admin'],
                ],
                [
                'route' => 'orang-tua.index',
                'label' => 'Orang Tua',
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                'roles' => ['admin'],
                ],
                [
                'route' => 'mata-pelajaran.index',
                'label' => 'Mata Pelajaran',
                'icon' => 'M4 6h16M4 10h16M4 14h10M4 18h10',
                'roles' => ['admin'],
                ],
                [
                'route' => 'ekstrakurikuler.index',
                'label' => 'Ekstrakurikuler',
                'icon' => 'M12 15a3 3 0 100-6 3 3 0 000 6zM12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728l1.414 1.414M16.95 16.95l1.414 1.414',
                'roles' => ['admin'],
                ],
                [
                'route' => 'kelas.index',
                'label' => 'Kelas',
                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0l2 0M5 21l-2 0M9 7h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1',
                'roles' => ['admin'],
                ],
                [
                'route' => 'nilai-akademik.index',
                'label' => 'Nilai Akademik',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'roles' => ['admin', 'guru', 'orang_tua'],
                ],
                ];

                $menus = array_filter($menus, fn ($menu) => in_array($userRole, $menu['roles']));
                @endphp

                <div class="space-y-1 px-3">
                    @foreach ($menus as $menu)
                    @php $active = request()->routeIs(explode('.', $menu['route'])[0].'*'); @endphp
                    <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition
              {{ $active
                  ? 'bg-blue-50 text-blue-600 font-semibold ring-1 ring-blue-500/10'
                  : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="h-[18px] w-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                        </svg>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </nav>

            <p class="px-6 py-5 text-[11px] text-slate-400">© {{ date('Y') }} SIA-PALLA</p>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top bar --}}
            <header class="h-20 flex-shrink-0 bg-white border-b border-slate-900/5 flex items-center justify-between px-4 sm:px-8">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" @click="sidebarOpen = true"
                        class="lg:hidden flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Portal Akademik</p>
                        <h1 class="text-xl font-bold text-slate-900 truncate">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-4 flex-shrink-0">
                    <span class="hidden sm:inline text-sm text-slate-500">{{ auth()->user()->name ?? '' }}</span>

                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition
                            class="absolute right-0 mt-2 w-44 overflow-hidden rounded-xl bg-white shadow-xl shadow-slate-900/10 ring-1 ring-slate-900/5 z-10">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                <div id="alert-success" class="mb-5 flex items-center gap-2 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-green-600/10">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div id="alert-error" class="mb-5 flex items-center gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-600/10">
                    {{ session('error') }}
                </div>
                @endif

                @if (session('success') || session('error'))
                <script>
                    setTimeout(() => {
                        const success = document.getElementById('alert-success');
                        const error = document.getElementById('alert-error');
                        if (success) {
                            success.style.transition = 'opacity 0.5s ease';
                            success.style.opacity = '0';
                            setTimeout(() => success.remove(), 500);
                        }
                        if (error) {
                            error.style.transition = 'opacity 0.5s ease';
                            error.style.opacity = '0';
                            setTimeout(() => error.remove(), 500);
                        }
                    }, 3000);
                </script>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

</body>

</html>