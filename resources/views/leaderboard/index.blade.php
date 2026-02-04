<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-dark">🏆 Papan Peringkat</h2>
                <p class="text-gray-600 mt-2">Siapa yang paling rajin belajar?</p>
            </div>

            <!-- Grade Filter -->
            <div class="mb-6 flex justify-center space-x-2">
                <a href="{{ route('leaderboard') }}" 
                   class="px-4 py-2 rounded-full font-bold transition {{ !$selectedGrade ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Semua
                </a>
                @foreach([3, 4, 5] as $grade)
                    <a href="{{ route('leaderboard', ['grade' => $grade]) }}" 
                       class="px-4 py-2 rounded-full font-bold transition {{ $selectedGrade == $grade ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Kelas {{ $grade }}
                    </a>
                @endforeach
            </div>

            <!-- Leaderboard Table -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                @if($users->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <span class="text-4xl">📭</span>
                        <p class="mt-4">Belum ada data peringkat.</p>
                    </div>
                @else
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left">#</th>
                                <th class="px-6 py-4 text-left">Nama</th>
                                <th class="px-6 py-4 text-center">Kelas</th>
                                <th class="px-6 py-4 text-right">Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr class="border-b {{ $index < 3 ? 'bg-yellow-50' : '' }} hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-lg">
                                        @if($index === 0)
                                            <span class="text-2xl">🥇</span>
                                        @elseif($index === 1)
                                            <span class="text-2xl">🥈</span>
                                        @elseif($index === 2)
                                            <span class="text-2xl">🥉</span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $user->nickname }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $user->grade }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-primary text-lg">
                                        {{ number_format($user->total_points) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
