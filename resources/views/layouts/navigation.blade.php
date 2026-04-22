<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-md sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <img src="/images/logo.jpeg" alt="Logo" class="w-10 h-10 rounded-full object-cover">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary">
                        MathQuest
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-secondary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-lg font-medium leading-5 transition duration-150 ease-in-out">
                        Peta Dunia
                    </a>
                    <a href="{{ route('leaderboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('leaderboard') ? 'border-secondary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-lg font-medium leading-5 transition duration-150 ease-in-out">
                        🏆 Peringkat
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="flex items-center mr-4 bg-yellow-100 px-3 py-1 rounded-full border border-yellow-300">
                    <span class="text-yellow-600 mr-1">⭐</span>
                    <span class="font-bold text-yellow-700">{{ auth()->user()->total_points }} Poin</span>
                </div>
                
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = ! open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div class="flex items-center">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset(auth()->user()->avatar->image_path) }}" alt="Avatar" class="w-8 h-8 rounded-full mr-2 border-2 border-primary">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-200 mr-2 flex items-center justify-center">👤</div>
                            @endif
                            <span class="text-lg text-dark">{{ auth()->user()->nickname }}</span>
                        </div>

                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50" 
                         style="display: none;">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Keluar
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>