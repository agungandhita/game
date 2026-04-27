<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Panel Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ─── Sidebar ─── --}}
    <aside class="hidden md:flex flex-col w-64 bg-white border-r border-gray-100 shadow-sm flex-shrink-0">

        {{-- Brand --}}
        <div class="px-6 py-5 border-b border-gray-100 flex-shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-md flex-shrink-0 overflow-hidden bg-white">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm leading-tight">Panel Admin</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Sistem Kuis</p>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto flex flex-col gap-0.5">

            {{-- Label: Utama --}}
            <p class="px-4 pt-1 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-gauge-high w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.scores.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.scores.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>Laporan Skor</span>
            </a>

            {{-- Label: Bank Soal --}}
            <p class="px-4 pt-5 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bank Soal</p>

            <a href="{{ route('admin.grades.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.grades.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-graduation-cap w-5 text-center"></i>
                <span>Kelas</span>
            </a>

            <a href="{{ route('admin.questions.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.questions.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-circle-question w-5 text-center"></i>
                <span>Soal</span>
            </a>

            {{-- Label: Manajemen --}}
            <p class="px-4 pt-5 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen</p>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>

        </nav>

        {{-- User + Logout --}}
        <div class="px-3 py-4 border-t border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-3 px-4 py-2.5 mb-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-indigo-600 font-medium">Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                    <i class="fas fa-right-from-bracket w-5 text-center"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── Main Content ─── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar (mobile) --}}
        <header class="md:hidden bg-white border-b border-gray-100 px-4 py-3 flex items-center justify-between flex-shrink-0">
            <span class="font-bold text-gray-800">Panel Admin</span>
            <a href="{{ route('quiz.index') }}" class="text-sm text-indigo-600 font-semibold">← Quiz</a>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl font-semibold flex items-center gap-2 shadow-sm text-sm flex-shrink-0">
                <i class="fas fa-circle-check"></i> {{ session('success') }}
                <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-600 px-5 py-3 rounded-2xl font-semibold flex items-center gap-2 shadow-sm text-sm flex-shrink-0">
                <i class="fas fa-circle-exclamation"></i> {{ session('error') }}
                <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100">✕</button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('container')
        </main>
    </div>
</div>

@yield('scripts')
</body>
</html>
