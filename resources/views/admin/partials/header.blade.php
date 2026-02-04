<header class='flex shadow-sm z-[99] py-2 px-4 sm:px-8 bg-white/80 backdrop-blur-md min-h-[72px] tracking-wide fixed top-0 w-full border-b border-gray-100'>
    <div class='flex flex-wrap items-center justify-between gap-4 w-full relative'>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="bg-blue-50 p-2 rounded-lg group-hover:bg-blue-100 transition-colors">
                <img src="/img/lamongan.png" alt="logo" class='w-8 object-contain' />
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-800 leading-tight">
                    Panel Admin
                </h1>
                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-wider">Sistem Gamifikasi</p>
            </div>
        </a>

        <div class="flex items-center gap-4">
            <div class="hidden sm:flex flex-col items-end mr-2">
                <span class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</span>
                <span class="text-[10px] text-blue-600 font-semibold uppercase tracking-tighter">Administrator</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </div>
</header>
