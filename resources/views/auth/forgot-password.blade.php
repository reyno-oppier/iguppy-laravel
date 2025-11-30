@section('title', 'Lupa Password - iGuppy')
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
            
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                    i<span class="text-blue-600">Guppy</span>
                </h1>
                <p class="text-gray-500 text-sm mt-1">Smart Aquarium System</p>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">
                Lupa Password
            </h2>

            <div class="mb-6 text-sm text-gray-600 text-center">
                Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </div>

            {{-- Menggunakan komponen Breeze/Jetstream bawaan untuk status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    {{-- Mengganti x-input-label ke label HTML biasa --}}
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    
                    {{-- Mengganti x-text-input ke input HTML biasa dengan kelas modern --}}
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm @error('email') border-red-500 @enderror">
                    
                    {{-- Menampilkan error validasi Laravel --}}
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-center pt-2">
                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold text-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        Kirim Tautan Reset Password
                    </button>
                </div>
            </form>

            <p class="text-center text-gray-600 text-sm mt-6">
                <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline flex items-center justify-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Halaman Masuk</span>
                </a>
            </p>

        </div>
    </div>
</x-guest-layout>