<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-dark">Pilih Petualanganmu! 🌍</h2>
                <p class="text-gray-600 mt-2">Dunia mana yang ingin kamu jelajahi hari ini?</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($worlds as $world)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2 border-b-8 border-secondary">
                        <div class="h-40 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
                            <span class="text-6xl">🗺️</span>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-2xl font-bold text-gray-800">{{ $world->name }}</h3>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded-full">Kelas {{ $world->class }}</span>
                            </div>
                            <p class="text-gray-600 mb-6">{{ $world->description }}</p>
                            
                            <a href="{{ route('world.show', $world->slug) }}" class="block w-full text-center bg-primary hover:bg-red-500 text-white font-bold py-3 px-4 rounded-xl shadow-md transition">
                                Masuk Dunia 🚀
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>