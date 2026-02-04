<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ $world->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($world->levels as $level)
                    @php
                        $userLevelProgress = $progress[$level->id] ?? null;
                        $isLocked = false; 
                        // Simple lock logic: lock if previous level not completed (unless it's the first level)
                        if ($level->sequence > 1) {
                            $prevLevel = $world->levels->where('sequence', $level->sequence - 1)->first();
                            $prevProgress = $progress[$prevLevel->id] ?? null;
                            if (!$prevProgress || !$prevProgress->is_completed) {
                                $isLocked = true;
                            }
                        }
                    @endphp

                    <div class="relative group">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-4 {{ $isLocked ? 'border-gray-300 opacity-75' : ($userLevelProgress && $userLevelProgress->is_completed ? 'border-green-400' : 'border-blue-400') }} transition transform hover:scale-105">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-full {{ $isLocked ? 'bg-gray-200' : 'bg-blue-100' }} flex items-center justify-center text-xl font-bold {{ $isLocked ? 'text-gray-400' : 'text-blue-600' }}">
                                    {{ $level->sequence }}
                                </div>
                                <div class="flex space-x-1">
                                    @for($i = 1; $i <= 3; $i++)
                                        <span class="text-lg {{ $userLevelProgress && $userLevelProgress->stars >= $i ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-800 mb-2 {{ $isLocked ? 'text-gray-500' : '' }}">{{ $level->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">Hadiah: {{ $level->points_reward }} Poin</p>

                            @if($isLocked)
                                <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg cursor-not-allowed flex items-center justify-center">
                                    <span class="mr-2">🔒</span> Terkunci
                                </button>
                            @else
                                <a href="{{ route('level.show', $level->id) }}" class="block w-full text-center {{ $userLevelProgress && $userLevelProgress->is_completed ? 'bg-green-500 hover:bg-green-600' : 'bg-secondary hover:bg-teal-500' }} text-white font-bold py-2 px-4 rounded-lg shadow transition">
                                    {{ $userLevelProgress && $userLevelProgress->is_completed ? 'Main Lagi' : 'Mulai' }} ▶️
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>