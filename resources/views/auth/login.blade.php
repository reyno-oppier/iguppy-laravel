@section('title', 'Masuk - iGuppy')
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
            
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                    i<span class="text-blue-600">Guppy</span>
                </h1>
                <p class="text-gray-500 text-sm mt-1">Smart Aquarium System</p>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                Hai, Selamat Datang!
            </h2>
            
            {{-- BLOK NOTIFIKASI ERROR KUSTOM (DITEMPATKAN DI BAWAH JUDUL 'Selamat Datang') --}}
            @if ($errors->has('email') || $errors->has('password'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Login Gagal!</strong>
                    <span class="block sm:inline">
                        @if ($errors->has('email'))
                            {{-- Akan menampilkan "Akun anda tidak terdaftar" --}}
                            {{ $errors->first('email') }}
                        @elseif ($errors->has('password'))
                            {{-- Akan menampilkan "Password anda salah" --}}
                            {{ $errors->first('password') }}
                        @endif
                    </span>
                </div>
            @endif
            {{-- AKHIR BLOK NOTIFIKASI ERROR KUSTOM --}}

            <x-auth-session-status class="mb-4" :status="session('status')" />

            @if (session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 4000)" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-white hover:text-gray-200">
                        ✕
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    {{-- Blok @error('email') individual dihapus, karena sudah ditampilkan di atas --}}
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    {{-- Blok @error('password') individual dihapus, karena sudah ditampilkan di atas --}}
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 text-sm hover:underline">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold text-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    Masuk
                </button>
            </form>

            <p class="text-center text-gray-600 text-sm mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">
                    Yuk, Buat Akun
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>