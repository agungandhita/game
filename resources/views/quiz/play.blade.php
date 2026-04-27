@extends('layouts.quiz')

@section('content')
<div class="max-w-2xl mx-auto" x-data="quizTimer({{ $session->level->time_per_question ?? 30 }})">
    
    <!-- Progress & Header -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6 flex items-center justify-between border-2 border-indigo-100">
        <div class="font-bold text-gray-500">Soal {{ $currentIndex }} dari {{ $totalQuestions }}</div>
        <div class="w-1/2 bg-gray-200 rounded-full h-3">
            <div class="bg-indigo-500 h-3 rounded-full transition-all duration-500" style="width: {{ ($currentIndex / $totalQuestions) * 100 }}%"></div>
        </div>
    </div>

    <!-- Soal Card -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border-b-4 border-indigo-200 transition-transform">
        
        <!-- Timer Visual -->
        <div class="flex justify-center mt-6 relative">
            <div class="relative w-24 h-24 flex items-center justify-center rounded-full bg-gray-50" :class="{'animate-pulse bg-red-100': timeLeft <= 5}">
                <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                    <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200" />
                    <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent"
                        :stroke-dasharray="circumference"
                        :stroke-dashoffset="circumference - (timeLeft / totalTime) * circumference"
                        class="transition-all duration-1000 linear"
                        :class="colorClass" />
                </svg>
                <span class="text-2xl font-black z-10" :class="textColorClass" x-text="timeLeft"></span>
            </div>
        </div>

        <!-- Pertanyaan -->
        <div class="p-8 text-center">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800 leading-snug">{{ $currentQuestion->question_text }}</h2>
        </div>

        <!-- Opsi Jawaban -->
        <div class="px-6 pb-8 space-y-4">
            <form id="answerForm" action="{{ route('quiz.answer', $session->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                <input type="hidden" name="time_spent" :value="timeSpent">
                <input type="hidden" name="selected_option_id" id="selectedOptionId" value="">

                @foreach($currentQuestion->options as $option)
                <button type="button" @click="submitAnswer('{{ $option->id }}')" class="relative w-full overflow-hidden bg-blue-50 hover:bg-blue-100 text-blue-900 border-2 border-blue-200 rounded-2xl p-4 text-xl font-bold text-center transition transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-blue-300 active:scale-95 group">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition duration-300"></div>
                    <span class="mr-2 inline-block bg-white text-blue-600 rounded-full w-8 h-8 leading-8 shadow-sm text-sm border border-blue-200">{{ $option->label->value }}</span>
                    {{ $option->option_text }}
                </button>
                @endforeach
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quizTimer', (startSeconds) => ({
        totalTime: startSeconds,
        timeLeft: startSeconds,
        timeSpent: 0,
        circumference: 2 * Math.PI * 40,
        intervalId: null,
        
        init() {
            this.intervalId = setInterval(() => {
                this.timeLeft--;
                this.timeSpent++;
                
                if (this.timeLeft <= 0) {
                    clearInterval(this.intervalId);
                    // Time is up, submit form with empty selected_option_id
                    document.getElementById('answerForm').submit();
                }
            }, 1000);
        },
        
        get colorClass() {
            if (this.timeLeft > (this.totalTime * 0.5)) return 'text-green-500';
            if (this.timeLeft > (this.totalTime * 0.2)) return 'text-yellow-400';
            return 'text-red-500';
        },

        get textColorClass() {
            if (this.timeLeft > (this.totalTime * 0.5)) return 'text-green-600';
            if (this.timeLeft > (this.totalTime * 0.2)) return 'text-yellow-600';
            return 'text-red-600';
        },

        submitAnswer(optionId) {
            clearInterval(this.intervalId);
            document.getElementById('selectedOptionId').value = optionId;
            document.getElementById('answerForm').submit();
        }
    }))
})
</script>
@endsection
