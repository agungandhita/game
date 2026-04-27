@extends('layouts.quiz')

@section('title', 'Pilih Kelas')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-10 animate-fade-in-up">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl shadow-lg mb-4">
            <span class="text-3xl">🎒</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-gray-800 mb-2">
            Pilih <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-teal-600">Kelasmu!</span>
        </h1>
        <p class="text-gray-500 text-lg font-semibold">
            Halo, <strong class="text-green-600">{{ auth()->user()->name }}</strong>! Kerjakan soal dari kelasmu yuk! 🌟
        </p>
    </div>

    {{-- Grade Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        @php
            $gradeEmojis     = ['🌱','📚','🔬','🌍','⚡','🏆'];
            $gradeGradients  = [
                'from-orange-400 to-red-400',
                'from-blue-400 to-indigo-500',
                'from-green-400 to-teal-500',
                'from-purple-400 to-pink-500',
                'from-yellow-400 to-orange-400',
                'from-teal-400 to-cyan-500',
            ];
        @endphp

        @foreach($grades as $index => $grade)
            @php
                $gradeLevelIds  = $grade->levels->pluck('id')->toArray();
                $completedCount = $progress->filter(
                    fn($p) => in_array($p->levelId, $gradeLevelIds) && $p->status === 'completed'
                )->count();
                $totalLevels    = count($gradeLevelIds);
                $pct            = $totalLevels > 0 ? round(($completedCount / $totalLevels) * 100) : 0;
                $gradient       = $gradeGradients[$index % count($gradeGradients)];
                $emoji          = $gradeEmojis[$index % count($gradeEmojis)];
            @endphp

            <a href="{{ route('quiz.levels', $grade->id) }}"
               class="group block transform transition-all duration-300 hover:scale-105 hover:-translate-y-2"
               style="animation: fadeInUp 0.5s ease {{ $index * 0.08 }}s both;">

                <div class="bg-gradient-to-br {{ $gradient }} rounded-3xl shadow-xl overflow-hidden border-4 border-white/50 h-full">
                    <div class="p-6 text-center text-white relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/10 rounded-full"></div>

                        <div class="relative z-10">
                            <div class="text-5xl mb-3 drop-shadow-md group-hover:scale-125 transition-transform duration-300">
                                {{ $emoji }}
                            </div>
                            <h2 class="text-2xl font-black mb-1 drop-shadow">{{ $grade->name }}</h2>
                            <p class="text-white/80 text-sm font-semibold mb-4">
                                {{ $completedCount }}/{{ $totalLevels }} Level Selesai
                            </p>

                            <div class="bg-white/30 rounded-full h-2.5 overflow-hidden backdrop-blur-sm">
                                <div class="bg-white h-full rounded-full" style="width: {{ $pct }}%"></div>
                            </div>

                            @if($completedCount > 0)
                                <div class="mt-3 flex justify-center gap-0.5">
                                    @for($s = 1; $s <= $totalLevels; $s++)
                                        <span class="text-sm {{ $s <= $completedCount ? 'opacity-100' : 'opacity-30' }}">⭐</span>
                                    @endfor
                                </div>
                            @else
                                <p class="text-white/70 text-xs mt-3 font-semibold">Belum dimulai</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-black/10 px-5 py-3 flex items-center justify-between">
                        <span class="text-white/90 text-xs font-bold uppercase tracking-wide">Mulai →</span>
                        <span class="text-white/90 text-xs font-bold">{{ $pct }}%</span>
                    </div>
                </div>
            </a>
        @endforeach

        @if($grades->isEmpty())
            <div class="col-span-3 text-center py-20 text-gray-400">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-bold text-gray-500">Belum ada kelas tersedia</h3>
                <p class="text-sm mt-2">Tunggu admin menambahkan soal ya!</p>
            </div>
        @endif
    </div>

    {{-- Tip --}}
    <div class="mt-10 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 text-2xl">💡</div>
        <div>
            <p class="font-bold text-gray-700">Cara Bermain</p>
            <p class="text-sm text-gray-500">Pilih kelas → pilih level → jawab soal sebelum waktu habis! Kumpulkan bintang ⭐⭐⭐ di setiap level.</p>
        </div>
    </div>
</div>
@endsection
