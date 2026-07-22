<x-guest-layout>
    <div class="w-full max-w-sm mx-auto">

        {{-- Header card --}}
        <div class="bg-white border border-b-0 rounded-t-lg px-8 py-6 text-center">
            <p class="font-semibold mb-3">Sistem Informasi Akademik</p>
            <img src="{{ asset('build/assets/logo.jpg') }}" alt="Logo" class="mx-auto w-14 h-14 object-cover">
        </div>

        <div class="bg-white border rounded-b-lg px-8 py-6">

            <x-auth-session-status class="mb-4" :status="session('status')" />
            <x-input-error :messages="$errors->all()" class="mb-4" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Username / Email"
                        class="w-full border rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <input id="password" type="password" name="password" required
                        placeholder="Password"
                        class="w-full border rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                        Forgot password?
                    </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-gray-900 text-white rounded py-2 font-semibold hover:bg-gray-800">
                    Login
                </button>

                @if (Route::has('register'))
                <p class="text-center text-sm">
                    <a href="{{ route('register') }}" class="text-indigo-600 underline">Sign in here</a>
                </p>
                @endif
            </form>
        </div>
    </div>
</x-guest-layout>