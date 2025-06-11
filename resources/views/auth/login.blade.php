<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{-- [MODIFIKASI] Menggunakan logo aplikasi yang spesifik dan menambahkan judul --}}
            <div class="text-center -mt-9">
                <a href="/">
                    <img class="w-auto mx-auto" src="{{ asset('images/icon-web.png') }}" alt="SISINPEM Logo">
                </a>
                <h2 class="-mt-6 text-2xl font-bold text-slate-800">
                    Selamat Datang Kembali!
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Silakan login untuk masuk ke dashboard Anda.
                </p>
            </div>
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- [MODIFIKASI] Input Email dengan ikon --}}
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-heroicon-o-envelope class="h-5 w-5 text-gray-400" />
                </div>
                <x-input id="email" class="block mt-1 w-full pl-10" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" placeholder="Email" />
            </div>

            {{-- [MODIFIKASI] Input Password dengan ikon --}}
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                </div>
                <x-input id="password" class="block mt-1 w-full pl-10" type="password" name="password" required
                    autocomplete="current-password" placeholder="Password" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            <div>
                {{-- [MODIFIKASI] Tombol dibuat full-width dan dengan warna brand --}}
                <x-button class="w-full flex justify-center bg-blue-600 hover:bg-blue-700">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        {{-- [BARU] Link kembali ke beranda atau untuk bantuan --}}
        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">
                Belum punya akun? <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Hubungi
                    Administrator</a>
            </p>
            <p class="mt-2 text-gray-500">
                <a href="{{ url('/') }}" class="hover:text-blue-500 transition-colors">&larr; Kembali ke
                    Beranda</a>
            </p>
        </div>

    </x-authentication-card>
</x-guest-layout>
