<x-guest-layout>
    <div class="text-center">
        <div class="flex justify-center mb-6">
            <img src="/images/logo.jpeg" alt="Logo Sekolah" class="w-24 h-24 rounded-full object-cover border-4 border-secondary shadow-lg" />
        </div>
        <h2 class="text-3xl font-bold text-dark mb-2">Buat Karakter Baru 🎨</h2>
        <p class="mb-8 text-gray-500 text-lg">Pilih identitas hebatmu di sini!</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nickname -->
            <div class="mb-6 group">
                <label for="nickname" class="block text-left font-black text-dark/70 mb-2 ml-4 uppercase text-sm tracking-widest">Nama Panggilan</label>
                <input id="nickname" 
                    class="block w-full text-xl p-4 border-4 border-gray-200 rounded-[2rem] focus:border-secondary focus:ring-0 transition-all duration-300 placeholder-gray-300 bg-gray-50/50 group-hover:bg-white" 
                    type="text" 
                    name="nickname" 
                    :value="old('nickname')" 
                    required 
                    autofocus 
                    placeholder="Contoh: BudiJuara" />
                @error('nickname')
                    <p class="text-primary font-bold text-sm mt-3 animate-bounce ml-4">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grade -->
            <div class="mb-10 group">
                <label for="grade" class="block text-left font-black text-dark/70 mb-2 ml-4 uppercase text-sm tracking-widest">Kamu Kelas Berapa?</label>
                <div class="relative">
                    <select id="grade" name="grade" 
                        class="block w-full text-xl p-4 border-4 border-gray-200 rounded-[2rem] focus:border-secondary focus:ring-0 transition-all duration-300 bg-gray-50/50 group-hover:bg-white appearance-none cursor-pointer">
                        <option value="1">🏫 Kelas 1</option>
                        <option value="2">🏫 Kelas 2</option>
                        <option value="3">🏫 Kelas 3</option>
                        <option value="4">🏫 Kelas 4</option>
                        <option value="5">🏫 Kelas 5</option>
                        <option value="6">🏫 Kelas 6</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('grade')
                    <p class="text-primary font-bold text-sm mt-3 animate-bounce ml-4">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-center mt-6">
                <button type="submit" class="w-full bg-secondary hover:bg-teal-500 text-white font-black py-5 px-8 rounded-[2rem] shadow-[0_8px_0_rgb(13,148,136)] active:shadow-none active:translate-y-2 transform transition-all duration-150 text-2xl uppercase tracking-wider">
                    Simpan & Main! 🎉
                </button>
            </div>

            <div class="mt-10 pt-6 border-t border-dashed border-gray-200 text-center">
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-600 font-bold transition-colors">
                    Sudah punya akun? <span class="text-primary underline">Masuk di sini</span>
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>