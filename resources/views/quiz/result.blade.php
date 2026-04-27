@extends('layouts.quiz')

@section('content')
<div class="max-w-3xl mx-auto space-y-8" x-data="resultConfetti({{ $result->score }})">
    
    <!-- Hero / Score Section -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 {{ $result->score >= 60 ? 'border-green-400' : 'border-red-400' }}">
        <div class="p-8 text-center bg-gradient-to-b from-blue-50 to-white">
            <h1 class="text-4xl font-black mb-2 {{ $result->score >= 60 ? 'text-green-600' : 'text-red-500' }}">
                {{ $result->score >= 60 ? 'Luar Biasa!' : 'Tetap Semangat!' }}
            </h1>
            <p class="text-gray-500 font-semibold">Kamu telah menyelesaikan {{ $result->level->name }}</p>

            <!-- Stars -->
            <div class="flex justify-center space-x-2 my-8 h-20">
                @for($i = 1; $i <= 3; $i++)
                    <div class="text-6xl transform origin-bottom transition-all duration-700 opacity-0 translate-y-4" 
                         style="animation: bounceIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) {{ ($i * 0.3) + 0.5 }}s forwards;"
                         :class="'{{ $i <= $result->stars ? 'text-yellow-400 drop-shadow-md' : 'text-gray-200 grayscale' }}'">
                        ⭐
                    </div>
                @endfor
            </div>

            <!-- Score -->
            <div class="mb-6">
                <p class="text-8xl font-black text-blue-600 drop-shadow-sm">{{ $result->score }}</p>
                <p class="text-sm text-gray-400 mt-2 font-bold uppercase tracking-widest">NILAI KAMU</p>
            </div>
            
            <div class="flex justify-center gap-6 mt-4 opacity-0 animate-fade-in delay-1000">
                <div class="bg-green-100 rounded-2xl p-4 min-w-[120px]">
                    <p class="text-3xl font-bold text-green-600">{{ $result->total_correct }}</p>
                    <p class="text-xs font-bold text-green-700 uppercase">Benar</p>
                </div>
                <div class="bg-red-100 rounded-2xl p-4 min-w-[120px]">
                    <p class="text-3xl font-bold text-red-600">{{ $result->total_questions - $result->total_correct }}</p>
                    <p class="text-xs font-bold text-red-700 uppercase">Salah</p>
                </div>
                @if(isset($unansweredCount) && $unansweredCount > 0)
                <div class="bg-yellow-100 rounded-2xl p-4 min-w-[120px]">
                    <p class="text-3xl font-bold text-yellow-600">{{ $unansweredCount }}</p>
                    <p class="text-xs font-bold text-yellow-700 uppercase">Timeout</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 p-6 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('quiz.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-full text-center transition">
                🏠 Menu Utama
            </a>
            
            <form action="{{ route('quiz.start', $result->level_id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full text-center transition shadow-md">
                    🔄 Ulangi
                </button>
            </form>

            @php
                // Cari level berikutnya jika ada
                $nextLevel = $result->level->grade->levels()->where('order', '>', $result->level->order)->orderBy('order')->first();
            @endphp
            
            @if($nextLevel && $result->score >= 60)
                <form action="{{ route('quiz.start', $nextLevel->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-black py-3 px-6 rounded-full text-center transition shadow-lg animate-pulse hover:animate-none">
                        Lanjut Level {{ $nextLevel->order }} ➡️
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Review Section -->
    <div class="bg-white rounded-3xl shadow-lg p-6 opacity-0 animate-fade-in animation-delay-1500 mt-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-100 pb-2">🧐 Rekap Jawaban</h3>
        
        <div class="space-y-4">
            @foreach($session->answers as $index => $answer)
                <div class="flex items-start bg-gray-50 rounded-xl p-4 border {{ $answer->is_correct ? 'border-green-200' : 'border-red-200' }}">
                    <div class="flex-shrink-0 text-3xl mr-4 pt-1">
                        @if($answer->is_correct)
                            ✅
                        @elseif(is_null($answer->selected_option_id))
                            ⏳
                        @else
                            ❌
                        @endif
                    </div>
                    <div class="flex-grow">
                        <p class="font-bold text-gray-800 mb-2">{{ $index + 1 }}. {{ $answer->question->question_text }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <div class="bg-white p-2 rounded-lg border border-gray-200">
                                <span class="text-gray-500 text-xs uppercase font-bold block mb-1">Jawaban Kamu:</span>
                                @if(is_null($answer->selected_option_id))
                                    <span class="text-yellow-600 italic">Kehabisan Waktu</span>
                                @else
                                    <span class="{{ $answer->is_correct ? 'text-green-600 font-bold' : 'text-red-500 line-through' }}">
                                        {{ $answer->selectedOption->option_text ?? '-' }}
                                    </span>
                                @endif
                            </div>
                            @if(!$answer->is_correct)
                                <div class="bg-green-50 p-2 rounded-lg border border-green-100">
                                    <span class="text-green-700 text-xs uppercase font-bold block mb-1">Jawaban Benar:</span>
                                    @php
                                        $correctOpt = $answer->question->options->firstWhere('is_correct', true);
                                    @endphp
                                    <span class="text-green-700 font-bold">{{ $correctOpt->option_text ?? '?' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3) translateY(20px); }
        50% { opacity: 1; transform: scale(1.1) translateY(-5px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.8s ease forwards; }
    .animation-delay-1500 { animation-delay: 1.5s; }
</style>

<!-- Confetti Canvas -->
<canvas id="confetti" class="fixed inset-0 pointer-events-none z-50 w-full h-full"></canvas>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resultConfetti', (score) => ({
        init() {
            if (score >= 90) {
                setTimeout(() => {
                    this.fireConfetti();
                }, 800);
            }
        },
        fireConfetti() {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 100 };

            function randomInRange(min, max) {
              return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                var particleCount = 50 * (timeLeft / duration);
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
        }
    }))
})
</script>
@endsection
