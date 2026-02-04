<x-guest-layout>
    <div class="text-center">
        <h2 class="text-3xl font-bold text-dark mb-2">Halo Petualang! 👋</h2>
        <p class="mb-8 text-gray-500 text-lg">Siap untuk petualangan matematika hari ini?</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Nickname -->
            <div class="mb-8 group">
                <input id="nickname" 
                    class="block w-full text-center text-2xl p-4 border-4 border-gray-200 rounded-[2rem] focus:border-secondary focus:ring-0 transition-all duration-300 placeholder-gray-300 bg-gray-50/50 group-hover:bg-white" 
                    type="text" 
                    name="nickname" 
                    :value="old('nickname')" 
                    required 
                    autofocus 
                    placeholder="Nama Panggilanmu" />
                @error('nickname')
                    <p class="text-primary font-bold text-sm mt-3 animate-bounce">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-center mt-6">
                <button type="submit" class="w-full bg-primary hover:bg-red-500 text-white font-black py-5 px-8 rounded-[2rem] shadow-[0_8px_0_rgb(220,38,38)] active:shadow-none active:translate-y-2 transform transition-all duration-150 text-2xl uppercase tracking-wider">
                    Mulai Main! 🚀
                </button>
            </div>
            
            <div class="mt-10 pt-6 border-t border-dashed border-gray-200">
                <p class="text-gray-500 mb-2">Belum punya karakter?</p>
                <a href="{{ route('register') }}" class="text-secondary text-lg font-black hover:text-teal-600 transition-colors flex items-center justify-center gap-2">
                    <span>Ayo Buat Baru!</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>