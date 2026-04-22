<x-app-layout>
    <div class="py-6" x-data="gameLogic()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Level -->
            <div class="flex justify-between items-center mb-8 bg-gradient-to-r from-primary to-pink-400 p-6 rounded-3xl shadow-lg text-white">
                <a href="{{ route('world.show', $level->world->slug) }}" class="flex items-center bg-white/20 hover:bg-white/30 px-4 py-2 rounded-2xl transition font-bold backdrop-blur-sm">
                    <span class="text-2xl mr-2">🏠</span> Beranda
                </a>
                <div class="text-center">
                    <h2 class="text-2xl font-bold tracking-wide">{{ $level->title }}</h2>
                    <p class="text-sm opacity-80">{{ $level->world->name }}</p>
                </div>
                <div class="bg-accent px-6 py-2 rounded-2xl shadow-inner font-extrabold text-dark text-xl transform -rotate-2">
                    ✨ <span x-text="score">0</span>
                </div>
            </div>

            <!-- Game Container -->
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden min-h-[500px] relative border-8 border-white">
                
                <!-- Loading State -->
                <div x-show="isLoading" class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center backdrop-blur-sm">
                    <div class="relative w-24 h-24">
                        <div class="absolute inset-0 border-8 border-gray-100 rounded-full"></div>
                        <div class="absolute inset-0 border-8 border-primary rounded-full animate-spin border-t-transparent"></div>
                    </div>
                    <p class="mt-6 text-2xl font-bold text-primary animate-bounce">Sabar ya, lagi loading... 🚀</p>
                </div>

                <!-- Result Screen -->
                <div x-show="showResult" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-90 opacity-0" x-transition:enter-end="scale-100 opacity-100" class="relative w-full h-full bg-white z-40 flex flex-col items-center justify-center p-8 py-16 text-center" style="display: none;">
                    <div class="text-[8rem] mb-4 animate-bounce" x-text="resultEmoji">🎉</div>
                    <h2 class="text-5xl font-black text-dark mb-4" x-text="resultTitle">Hebat!</h2>
                    <p class="text-2xl text-gray-500 mb-8 font-medium" x-text="resultMessage">Kamu berhasil menyelesaikan level ini.</p>
                    
                    <div class="flex justify-center space-x-4 mb-12 bg-yellow-50 p-6 rounded-3xl border-4 border-yellow-200 shadow-inner">
                        <template x-for="i in 3">
                            <span class="text-7xl transition-all duration-700 transform" 
                                  :class="i <= stars ? 'text-yellow-400 scale-125 drop-shadow-lg' : 'text-gray-200 scale-100'"
                                  :style="'transition-delay: ' + (i * 200) + 'ms'">★</span>
                        </template>
                    </div>

                    <div class="flex space-x-6">
                        <a href="{{ route('world.show', $level->world->slug) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-black py-4 px-10 rounded-[2rem] text-xl transition-all hover:scale-105 active:scale-95 shadow-lg border-b-8 border-gray-300">
                            Pilih Level 🗺️
                        </a>
                        <button @click="restartLevel()" class="bg-secondary hover:bg-teal-400 text-white font-black py-4 px-10 rounded-[2rem] text-xl transition-all hover:scale-105 active:scale-95 shadow-xl border-b-8 border-teal-600">
                            Ulangi 🔄
                        </button>
                    </div>
                </div>

                <!-- Question Card -->
                <div x-show="!isLoading && !showResult" class="p-10">
                    <!-- Progress Bar -->
                    <div class="w-full bg-blue-50 rounded-full h-8 mb-10 shadow-inner overflow-hidden border-4 border-blue-100 p-1">
                        <div class="bg-gradient-to-r from-secondary to-blue-400 h-full rounded-full transition-all duration-1000 relative" 
                             :style="'width: ' + ((currentQuestionIndex + 1) / questions.length * 100) + '%'">
                            <div class="absolute right-0 top-0 bottom-0 w-8 bg-white/30 animate-pulse"></div>
                        </div>
                    </div>

                    <template x-if="questions.length > 0">
                        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="mb-10 text-center">
                                <span class="inline-block bg-primary/10 text-primary px-4 py-1 rounded-full text-sm font-bold mb-4">
                                    Pertanyaan <span x-text="currentQuestionIndex + 1"></span> dari <span x-text="questions.length"></span>
                                </span>
                                <h3 class="text-3xl font-black text-gray-800 leading-tight mb-6" x-text="questions[currentQuestionIndex].content"></h3>
                                
                                <template x-if="questions[currentQuestionIndex].image_path">
                                    <div class="relative inline-block mt-4">
                                        <img :src="questions[currentQuestionIndex].image_path" class="mx-auto max-h-64 rounded-3xl shadow-xl border-8 border-white transform hover:rotate-1 transition">
                                        <div class="absolute -bottom-4 -right-4 bg-accent w-12 h-12 rounded-full flex items-center justify-center text-2xl shadow-lg border-4 border-white">🎨</div>
                                    </div>
                                </template>
                            </div>

                            <!-- Multiple Choice -->
                            <template x-if="questions[currentQuestionIndex].type === 'multiple_choice'">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <template x-for="(option, index) in questions[currentQuestionIndex].options" :key="option.id">
                                        <button @click="userAnswer = option.id" 
                                                class="group p-6 rounded-[2rem] border-4 text-2xl font-black transition-all transform hover:-translate-y-2 relative overflow-hidden text-left"
                                                :class="userAnswer === option.id ? 'border-primary bg-primary text-white shadow-xl rotate-1' : 'border-blue-50 hover:border-blue-200 bg-blue-50/50 text-gray-700'">
                                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl mr-4 text-xl"
                                                  :class="userAnswer === option.id ? 'bg-white/20' : 'bg-white shadow-sm'">
                                                <span x-text="String.fromCharCode(65 + index)"></span>
                                            </span>
                                            <span x-text="option.content"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>

                            <!-- Essay -->
                            <template x-if="questions[currentQuestionIndex].type === 'essay'">
                                <div class="max-w-2xl mx-auto">
                                    <textarea x-model="userAnswer" 
                                              placeholder="Tulis jawabanmu di sini ya..."
                                              class="w-full p-8 text-2xl font-bold rounded-[2rem] border-4 border-dashed border-blue-200 focus:border-secondary focus:ring-0 bg-blue-50/30 min-h-[200px] transition-all"
                                              rows="4"></textarea>
                                    <p class="mt-4 text-gray-400 font-medium text-center italic">Ayo tulis apa saja yang kamu tahu! ✨</p>
                                </div>
                            </template>

                            <!-- Counting / Math -->
                            <template x-if="questions[currentQuestionIndex].type === 'counting'">
                                <div class="flex flex-col items-center">
                                    <div class="relative group">
                                        <input type="text" x-model="userAnswer" 
                                               placeholder="???"
                                               class="w-64 p-8 text-6xl text-center font-black rounded-3xl border-8 border-accent focus:border-secondary focus:ring-0 bg-yellow-50 shadow-inner transform transition hover:scale-105">
                                        <div class="absolute -top-6 -right-6 bg-secondary text-white w-14 h-14 rounded-full flex items-center justify-center text-3xl shadow-lg border-4 border-white animate-pulse">🧮</div>
                                    </div>
                                    <p class="mt-8 text-xl font-bold text-gray-400">Tulis angka jawabannya di atas ya!</p>
                                </div>
                            </template>

                            <!-- Matching -->
                            <template x-if="questions[currentQuestionIndex].type === 'matching'">
                                <div class="space-y-4 max-w-xl mx-auto">
                                    <template x-for="option in questions[currentQuestionIndex].options" :key="option.id">
                                        <div class="flex items-center space-x-4 bg-purple-50 p-4 rounded-2xl border-4 border-purple-100">
                                            <div class="flex-1 text-xl font-bold text-purple-700" x-text="option.label || 'Opsi ' + option.id"></div>
                                            <div class="text-2xl">➡️</div>
                                            <select x-model="userAnswer[option.id]" 
                                                    class="flex-1 p-3 text-lg font-bold rounded-xl border-2 border-purple-200 focus:border-purple-400 focus:ring-0 bg-white shadow-sm">
                                                <option value="">Pilih...</option>
                                                <template x-for="opt in questions[currentQuestionIndex].options">
                                                    <option :value="opt.content" x-text="opt.content"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <div class="mt-12 flex justify-center">
                                <button @click="submitCurrentAnswer()" 
                                        :disabled="isAnswerEmpty()"
                                        class="group relative bg-primary hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black py-5 px-16 rounded-[2.5rem] shadow-xl text-2xl transition-all transform hover:scale-110 active:scale-95 border-b-8 border-red-700">
                                    <span class="flex items-center">
                                        Jawab 🚀
                                    </span>
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="questions.length === 0">
                        <div class="text-center py-20 bg-blue-50 rounded-[3rem] border-4 border-dashed border-blue-200">
                            <div class="text-8xl mb-6">🏝️</div>
                            <p class="text-2xl font-bold text-gray-500 mb-8">Wah, belum ada soal di sini!</p>
                            <a href="{{ route('dashboard') }}" class="bg-secondary text-white font-black py-4 px-10 rounded-[2rem] text-xl shadow-lg hover:scale-105 transition active:scale-95 inline-block">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function gameLogic() {
            return {
                isLoading: false,
                questions: @json($level->questions),
                currentQuestionIndex: 0,
                userAnswer: null, // For MC, Essay, Counting
                answers: {}, // Final map question_id -> answer
                score: 0,
                showResult: false,
                resultTitle: '',
                resultMessage: '',
                resultEmoji: '',
                stars: 0,

                init() {
                    this.initCurrentQuestion();
                },

                initCurrentQuestion() {
                    const q = this.questions[this.currentQuestionIndex];
                    if (!q) return;

                    if (q.type === 'matching') {
                        this.userAnswer = {};
                        q.options.forEach(opt => {
                            this.userAnswer[opt.id] = '';
                        });
                    } else {
                        this.userAnswer = null;
                    }
                },

                isAnswerEmpty() {
                    if (this.userAnswer === null || this.userAnswer === '') return true;
                    if (typeof this.userAnswer === 'object') {
                        return Object.values(this.userAnswer).some(v => v === '');
                    }
                    return false;
                },

                submitCurrentAnswer() {
                    const currentQuestion = this.questions[this.currentQuestionIndex];
                    this.answers[currentQuestion.id] = this.userAnswer;

                    if (this.currentQuestionIndex < this.questions.length - 1) {
                        this.currentQuestionIndex++;
                        this.initCurrentQuestion();
                        // Scroll to top of question card
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        this.finishGame();
                    }
                },

                finishGame() {
                    this.isLoading = true;

                    fetch('{{ route("level.submit", $level->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ answers: this.answers })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.isLoading = false;
                        this.showResult = true;
                        this.score = data.score;
                        this.stars = data.stars;
                        
                        if (data.is_completed) {
                            this.resultTitle = 'Luar Biasa!';
                            this.resultMessage = data.message || 'Kamu pintar sekali! 🏆';
                            this.resultEmoji = '🏆';
                        } else {
                            this.resultTitle = 'Hebat!';
                            this.resultMessage = data.message || 'Ayo coba lagi untuk skor sempurna! 💪';
                            this.resultEmoji = '✨';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.isLoading = false;
                        alert('Terjadi kesalahan saat menyimpan jawaban.');
                    });
                },

                restartLevel() {
                    this.currentQuestionIndex = 0;
                    this.answers = {};
                    this.initCurrentQuestion();
                    this.showResult = false;
                    this.score = 0;
                }
            }
        }
    </script>
</x-app-layout>