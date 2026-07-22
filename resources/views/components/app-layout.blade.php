<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - Sistem Informasi Akademik</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800">

    {{-- Background image dengan opacity terpisah --}}
    <div class="fixed inset-0 bg-cover bg-center -z-10"
        style="background-image: url('{{ asset('build/assets/bg-dashboard.jpg') }}'); opacity: 0.6;"></div>
    <div class="fixed inset-0 bg-black/40 -z-10"></div>

    <div class="flex min-h-screen">

        <aside class="w-64 bg-white text-slate-700 flex flex-col border-r border-gray-200">
            <div class="h-24 flex items-center justify-center border-b border-gray-200">
                <img src="{{ asset('build/assets/logo.jpg') }}" alt="Logo"
                    class="w-14 h-14 object-cover">
            </div>

            <nav class="flex-1 py-4">
                @php
                $menus = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10'],
                ['route' => 'siswa.index', 'label' => 'Siswa', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                ['route' => 'guru.index', 'label' => 'Guru', 'icon' => 'M8 14v.5M12 14v.5M16 14v.5M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                ['route' => 'orang-tua.index', 'label' => 'Orang Tua', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'mata-pelajaran.index', 'label' => 'Mata Pelajaran', 'icon' => 'M4 6h16M4 10h16M4 14h10M4 18h10'],
                ['route' => 'kelas.index', 'label' => 'Kelas', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0l2 0M5 21l-2 0M9 7h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1'],
                ['route' => 'nilai-akademik.index', 'label' => 'Nilai Akademik', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ];
                @endphp

                @foreach ($menus as $menu)
                @php $active = request()->routeIs(explode('.', $menu['route'])[0].'*'); @endphp
                <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                    class="flex items-center gap-3 px-6 py-3 text-sm transition
                      {{ $active ? 'bg-indigo-50 text-indigo-600 font-semibold border-l-4 border-indigo-500' : 'text-slate-500 hover:bg-gray-100 hover:text-slate-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                    </svg>
                    <span>{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </nav>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col">
            {{-- Top bar --}}
            <header class="h-20 bg-white border-b flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-slate-800">{{ $title ?? 'Dashboard' }}</h1>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name ?? '' }}</span>

                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                            class="w-10 h-10 rounded-full border flex items-center justify-center text-slate-500 hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                            x-transition
                            class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8">
                @if (session('success'))
                <div id="alert-success" class="mb-5 p-3 rounded bg-green-100 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div id="alert-error" class="mb-5 p-3 rounded bg-red-100 text-red-700 text-sm">
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