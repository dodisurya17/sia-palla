<x-guest-layout>
    <div class="w-full max-w-sm mx-auto">

        {{-- Header card --}}
        <div class="bg-white border border-b-0 rounded-t-lg px-8 py-6 text-center">
            <p class="font-semibold mb-3">Sistem Informasi Akademik</p>
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mx-auto w-14 h-14 object-cover">
        </div>

        <div class="bg-white border rounded-b-lg px-8 py-6">

            <x-input-error :messages="$errors->all()" class="mb-4" />

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="name" placeholder="Name"
                        class="w-full border rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username" placeholder="Email"
                        class="w-full border rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <input id="password" type="password" name="password" required
                        autocomplete="new-password" placeholder="Password"
                        class="w-full border rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Confirm Password"
                        class="w-full border rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <button type="submit"
                    class="w-full bg-gray-900 text-white rounded py-2 font-semibold hover:bg-gray-800">
                    Register
                </button>

                <p class="text-center text-sm">
                    <a href="{{ route('login') }}" class="text-indigo-600 underline">
                        {{ __('Already registered?') }}
                    </a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>