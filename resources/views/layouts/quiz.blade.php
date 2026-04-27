<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KuisPintar') — Belajar Seru!</title>

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
                    animation: {
                        'bounce-slow': 'bounce 2s infinite',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Nunito', sans-serif; }
        .bg-dots {
            background-color: #f0fdf4;
            background-image: radial-gradient(circle, #bbf7d0 1px, transparent 1px);
            background-size: 28px 28px;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease forwards; }
    </style>

    @yield('styles')
</head>
<body class="bg-dots min-h-screen text-gray-800">

    {{-- ─── Navbar ─── --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b-2 border-green-100 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('quiz.index') }}" class="flex items-center gap-2 group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform overflow-hidden bg-white">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-xl font-black text-gray-800 hidden sm:block">KuisPintar</span>
            </a>

            {{-- User info --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 bg-green-50 border border-green-100 rounded-full px-4 py-1.5">
                    <span class="text-green-600 text-lg">👤</span>
                    <span class="font-bold text-green-700 text-sm">{{ auth()->user()->name }}</span>
                </div>

                {{-- Admin badge --}}
                @if(auth()->user()->role === \App\Enums\UserRole::Admin)
                    <a href="{{ route('admin.dashboard') }}"
                       class="bg-purple-100 text-purple-700 font-bold text-xs px-3 py-1.5 rounded-full border border-purple-200 hover:bg-purple-200 transition-colors">
                        ⚙️ Admin
                    </a>
                @endif

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-500 font-bold text-sm px-4 py-1.5 rounded-full border border-red-100 transition-all flex items-center gap-1">
                        <span>👋</span>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ─── Flash Messages ─── --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
             class="max-w-5xl mx-auto px-4 pt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl font-bold flex items-center gap-2 shadow-sm">
                <span class="text-xl">✅</span>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">✕</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
             class="max-w-5xl mx-auto px-4 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-3 rounded-2xl font-bold flex items-center gap-2 shadow-sm">
                <span class="text-xl">❌</span>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">✕</button>
            </div>
        </div>
    @endif

    {{-- ─── Content ─── --}}
    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
