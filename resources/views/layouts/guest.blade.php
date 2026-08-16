<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased">

    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-4 sm:p-6">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl shadow-slate-900/5 ring-1 ring-slate-900/5 overflow-hidden lg:flex">

            {{-- Brand panel — desktop only --}}
            <div class="relative hidden lg:flex lg:w-[42%] flex-col justify-between overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 p-10 text-white">

                {{-- Signature: connected-node network motif --}}
                <svg class="absolute inset-0 h-full w-full opacity-[0.18]" viewBox="0 0 400 600" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="330" cy="90" r="3" fill="white" />
                    <circle cx="270" cy="150" r="3" fill="white" />
                    <circle cx="360" cy="220" r="3" fill="white" />
                    <circle cx="300" cy="290" r="3" fill="white" />
                    <circle cx="220" cy="340" r="3" fill="white" />
                    <circle cx="340" cy="400" r="3" fill="white" />
                    <circle cx="260" cy="460" r="3" fill="white" />
                    <circle cx="370" cy="500" r="3" fill="white" />
                    <circle cx="180" cy="230" r="3" fill="white" />
                    <line x1="330" y1="90" x2="270" y2="150" stroke="white" stroke-width="1" />
                    <line x1="270" y1="150" x2="360" y2="220" stroke="white" stroke-width="1" />
                    <line x1="360" y1="220" x2="300" y2="290" stroke="white" stroke-width="1" />
                    <line x1="300" y1="290" x2="220" y2="340" stroke="white" stroke-width="1" />
                    <line x1="220" y1="340" x2="340" y2="400" stroke="white" stroke-width="1" />
                    <line x1="340" y1="400" x2="260" y2="460" stroke="white" stroke-width="1" />
                    <line x1="260" y1="460" x2="370" y2="500" stroke="white" stroke-width="1" />
                    <line x1="220" y1="340" x2="180" y2="230" stroke="white" stroke-width="1" />
                    <line x1="180" y1="230" x2="270" y2="150" stroke="white" stroke-width="1" />
                </svg>
                <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -top-16 -right-10 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative">
                    <img src="{{ asset('build/assets/logo.jpg') }}" alt="YAPNUSDA" class="h-14 w-14 rounded-sm object-cover">
                    <p class="mt-8 text-sm font-medium uppercase tracking-widest text-blue-100">Sia-Palla</p>
                    <h1 class="mt-3 text-3xl font-bold leading-snug">
                        Sistem Informasi<br>Akademik
                    </h1>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-blue-100">
                        SMP Katolik Palla.
                    </p>
                </div>

                <p class="relative text-xs text-blue-100/80">© {{ date('Y') }} SIA-PALLA · Semua hak dilindungi</p>
            </div>

            {{-- Content panel --}}
            <div class="w-full px-6 py-10 sm:px-10 lg:w-[58%] lg:px-14 lg:py-14">
                <div class="mx-auto w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

</body>

</html>