<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — KuisPintar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Nunito', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Nunito', sans-serif; }
        .floating { animation: floating 3s ease-in-out infinite; }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob 8s ease-in-out infinite;
        }
        @keyframes blob {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            50% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-teal-50 to-cyan-50 min-h-screen flex items-center justify-center p-4">

    {{-- Decorative --}}
    <div class="fixed top-0 right-0 w-72 h-72 bg-teal-200/40 blob translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 w-96 h-96 bg-green-200/40 blob -translate-x-1/4 translate-y-1/4 pointer-events-none"></div>

    <div class="relative w-full max-w-md z-10">

        {{-- Logo / Header --}}
        <div class="text-center mb-8">
            <div class="floating inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-xl border-4 border-teal-100 mb-4 overflow-hidden">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Sekolah" class="w-full h-full object-cover">
            </div>
            <h1 class="text-3xl font-black text-gray-800">KuisPintar</h1>
            <p class="text-gray-500 font-semibold mt-1">Daftar dan mulai belajar sekarang!</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-white p-8">

            <h2 class="text-2xl font-black text-gray-800 mb-1">Buat Akun Baru 🎉</h2>
            <p class="text-gray-500 text-sm mb-6">Isi data berikut untuk mulai petualangan belajar!</p>

            {{-- Error messages --}}
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold text-gray-600 mb-2">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkapmu"
                            required autofocus
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 rounded-2xl font-semibold text-gray-800 placeholder-gray-300 focus:outline-none focus:border-teal-400 focus:bg-white transition-all @error('name') border-red-300 bg-red-50 @enderror"
                        >
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-bold text-gray-600 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">📧</span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="email@kamu.com"
                            required
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 rounded-2xl font-semibold text-gray-800 placeholder-gray-300 focus:outline-none focus:border-teal-400 focus:bg-white transition-all @error('email') border-red-300 bg-red-50 @enderror"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-bold text-gray-600 mb-2">
                        Password <span class="text-red-400">*</span>
                        <span class="text-gray-400 font-normal">(min. 6 karakter)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border-2 border-gray-100 rounded-2xl font-semibold text-gray-800 placeholder-gray-300 focus:outline-none focus:border-teal-400 focus:bg-white transition-all @error('password') border-red-300 bg-red-50 @enderror"
                        >
                        <button type="button" @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <span x-text="show ? '🙈' : '👁️'"></span>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-600 mb-2">
                        Ulangi Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔐</span>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 rounded-2xl font-semibold text-gray-800 placeholder-gray-300 focus:outline-none focus:border-teal-400 focus:bg-white transition-all"
                        >
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    :disabled="loading"
                    class="w-full bg-teal-500 hover:bg-teal-600 disabled:opacity-70 text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-teal-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0 text-lg flex items-center justify-center gap-2">
                    <span x-show="!loading">🎉 Daftar & Mulai Belajar</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-teal-600 font-black hover:text-teal-700 transition-colors">
                        Masuk di sini →
                    </a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6 font-semibold">KuisPintar © {{ date('Y') }} · Belajar Menyenangkan</p>
    </div>
</body>
</html>