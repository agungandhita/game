@extends('layouts.quiz')

@section('title', 'Level ' . $grade->name)

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 mb-6 text-sm font-semibold text-gray-400">
        <a href="{{ route('quiz.index') }}" class="hover:text-green-600 transition-colors flex items-center gap-1">
            🏠 <span>Menu Utama</span>
        </a>
        <span class="text-gray-300">›</span>
        <span class="text-gray-700">{{ $grade->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-black text-gray-800">
            Level di <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-teal-600">{{ $grade->name }}</span>
        </h1>
        <p class="text-gray-500 mt-2 font-semibold">Selesaikan semua level untuk mendapat bintang penuh! ⭐</p>
    </div>

    {{-- Level Cards --}}
    <div class="space-y-4">
        @php
            $levelGradients = [
                'from-green-400 to-teal-500',
                'from-blue-400 to-indigo-500',
                'from-purple-400 to-pink-500',
            ];
        @endphp

        @foreach($levels as $idx => $level)
            @php
                $prog     = $progress->get($level->id);
                $status   = $prog ? $prog->status : 'locked';
                $score    = $prog ? $prog->score  : null;
                $stars    = $prog ? $prog->stars  : 0;
                $gradient = $levelGradients[$idx % count($levelGradients)];
            @endphp

            <div style="animation: fadeInUp 0.5s ease {{ ($idx * 0.12) + 0.1 }}s both; opacity:0;">

                @if($status === 'locked')
                {{-- ─── LOCKED ─── --}}
                <div class="bg-gray-100 rounded-2xl border-2 border-dashed border-gray-200 overflow-hidden">
                    <div class="p-5 flex items-center gap-4 opacity-50">
                        <div class="w-14 h-14 bg-gray-200 rounded-2xl flex items-center justify-center flex-shrink-0 text-3xl">
                            🔒
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-black text-gray-500">{{ $level->name }}</h3>
                            <p class="text-xs text-gray-400 font-semibold mt-1">Selesaikan level sebelumnya dulu!</p>
                        </div>
                        <div class="flex gap-0.5">
                            @for($s = 1; $s <= 3; $s++)
                                <span class="text-gray-200 text-lg">⭐</span>
                            @endfor
                        </div>
                    </div>
                </div>

                @elseif($status === 'completed')
                {{-- ─── COMPLETED ─── --}}
                <div class="bg-white rounded-2xl border-2 border-green-200 shadow-md overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="h-1.5 bg-gradient-to-r {{ $gradient }}"></div>
                    <div class="p-5 flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br {{ $gradient }} rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                            <span class="text-2xl font-black text-white">{{ $idx + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-black text-gray-800">{{ $level->name }}</h3>
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">✓ Selesai</span>
                            </div>
                            <div class="flex items-center gap-3 mt-1.5">
                                <div class="flex gap-0.5">
                                    @for($s = 1; $s <= 3; $s++)
                                        <span class="text-base {{ $s <= ($stars ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}">⭐</span>
                                    @endfor
                                </div>
                                <span class="text-sm font-bold text-green-600">{{ number_format($score ?? 0, 2) }} poin</span>
                                <span class="text-xs text-gray-400">⏱ {{ $level->time_per_question }}s/soal</span>
                            </div>
                        </div>
                        <form action="{{ route('quiz.start', $level->id) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button type="submit"
                                class="bg-white border-2 border-green-200 text-green-600 hover:bg-green-50 font-black px-4 py-2 rounded-xl text-sm transition-all">
                                🔄 Ulangi
                            </button>
                        </form>
                    </div>
                </div>

                @else
                {{-- ─── UNLOCKED ─── --}}
                <div class="bg-white rounded-2xl border-2 border-indigo-100 shadow-md overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="h-1.5 bg-gradient-to-r {{ $gradient }}"></div>
                    <div class="p-5 flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br {{ $gradient }} rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                            <span class="text-2xl font-black text-white">{{ $idx + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-black text-gray-800">{{ $level->name }}</h3>
                            <p class="text-sm text-gray-400 font-semibold mt-0.5">
                                ⏱ {{ $level->time_per_question }}s per soal · Siap dimulai!
                            </p>
                            <div class="flex gap-0.5 mt-1">
                                @for($s = 1; $s <= 3; $s++)
                                    <span class="text-gray-200 text-base">⭐</span>
                                @endfor
                            </div>
                        </div>
                        <form action="{{ route('quiz.start', $level->id) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button type="submit"
                                class="bg-gradient-to-r {{ $gradient }} text-white font-black px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0 text-sm whitespace-nowrap">
                                🚀 Mulai!
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        @endforeach

        @if($levels->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <div class="text-5xl mb-4">📭</div>
                <h3 class="text-lg font-bold text-gray-500">Belum ada level di kelas ini</h3>
                <p class="text-sm mt-2">Tunggu admin menambahkan soal ya!</p>
            </div>
        @endif
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('quiz.index') }}"
           class="inline-flex items-center gap-2 text-gray-400 hover:text-green-600 font-bold transition-colors text-sm">
            ← Kembali ke Menu Utama
        </a>
    </div>
</div>
@endsection
