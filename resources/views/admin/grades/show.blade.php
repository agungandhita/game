@extends('admin.layouts.main')

@section('container')
<div class="font-sans">
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.grades.index') }}" class="bg-white border-2 border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-500 font-bold py-3 px-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center mr-6 group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-certificate text-yellow-500 mr-3"></i> Detail Pencapaian
            </h1>
            <p class="text-gray-500 mt-1 text-lg">Lihat hasil belajar siswa secara detail.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Student Info -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Student Card -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 relative">
                <div class="bg-gradient-to-r from-blue-400 to-indigo-500 h-24"></div>
                <div class="px-6 pb-6 text-center relative">
                    <div class="w-24 h-24 bg-white rounded-full p-1 mx-auto -mt-12 shadow-md flex items-center justify-center">
                        <div class="w-full h-full bg-blue-100 rounded-full flex items-center justify-center text-blue-500 text-4xl">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mt-4">{{ $grade->user->nickname }}</h2>
                    <p class="text-gray-500">{{ $grade->user->email ?? 'Tidak ada email' }}</p>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-500 font-medium">Level Game</span>
                            <span class="bg-purple-100 text-purple-700 py-1 px-3 rounded-full text-sm font-bold">{{ $grade->level->title }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Dunia</span>
                            <span class="text-gray-800 font-bold">{{ $grade->level->world->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 flex flex-col items-center text-center">
                <h3 class="text-gray-500 font-bold uppercase text-sm tracking-wider mb-4">Status Pengerjaan</h3>
                @if($grade->is_completed)
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-green-500 text-4xl mb-3 animate-bounce">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-2xl font-bold text-green-600">Lulus Level!</span>
                    <p class="text-green-600/70 text-sm mt-1">Siswa telah menyelesaikan level ini.</p>
                @else
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center text-orange-500 text-4xl mb-3">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <span class="text-2xl font-bold text-orange-600">Belum Selesai</span>
                    <p class="text-orange-600/70 text-sm mt-1">Siswa masih berjuang di level ini.</p>
                @endif
            </div>
        </div>

        <!-- Right Column: Stats & History -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Score -->
                <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-blue-500 flex flex-col items-center">
                    <span class="text-gray-400 font-bold text-sm uppercase mb-2">Total Skor</span>
                    <span class="text-5xl font-extrabold text-blue-600">{{ $grade->score }}</span>
                    <span class="text-blue-200 text-xs mt-2 font-bold bg-blue-50 px-2 py-1 rounded">POIN</span>
                </div>

                <!-- Stars -->
                <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-yellow-400 flex flex-col items-center">
                    <span class="text-gray-400 font-bold text-sm uppercase mb-2">Perolehan Bintang</span>
                    <div class="flex gap-1 my-2">
                        @for($i = 0; $i < $grade->stars; $i++)
                            <i class="fas fa-star text-4xl text-yellow-400 drop-shadow-sm transform hover:scale-110 transition-transform"></i>
                        @endfor
                        @for($i = $grade->stars; $i < 3; $i++)
                            <i class="far fa-star text-4xl text-gray-200"></i>
                        @endfor
                    </div>
                    <span class="text-yellow-600/70 text-xs mt-1 font-bold">{{ $grade->stars }} dari 3 Bintang</span>
                </div>

                <!-- Attempts -->
                <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-purple-500 flex flex-col items-center">
                    <span class="text-gray-400 font-bold text-sm uppercase mb-2">Percobaan</span>
                    <div class="flex items-baseline">
                        <span class="text-5xl font-extrabold text-purple-600">{{ $grade->attempts }}</span>
                        <span class="text-xl text-gray-400 ml-1 font-bold">x</span>
                    </div>
                    <span class="text-purple-300 text-xs mt-2 font-bold bg-purple-50 px-2 py-1 rounded">KALI MAIN</span>
                </div>
            </div>

            <!-- History Section (Placeholder) -->
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center">
                        <i class="fas fa-history text-gray-400 mr-2"></i> Riwayat Aktivitas
                    </h3>
                </div>
                <div class="p-10 flex flex-col items-center justify-center text-center min-h-[200px]">
                    <div class="bg-blue-50 p-6 rounded-full mb-4">
                        <i class="fas fa-clipboard-list text-blue-300 text-5xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-700">Detail Jawaban Belum Tersedia</h4>
                    <p class="text-gray-500 max-w-md mt-2">Fitur untuk melihat detail jawaban per soal sedang dikembangkan. Pantau terus pembaruannya!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

