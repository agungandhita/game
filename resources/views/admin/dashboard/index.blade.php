@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 mb-10 shadow-lg shadow-blue-100">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-white text-center md:text-left">
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100 text-lg max-w-xl">Semangat mengajar hari ini! Pantau aktivitas belajar siswa Anda dengan mudah di sini.</p>
            </div>
            <div class="flex items-center gap-3 text-white bg-white/20 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20">
                <div class="bg-white/30 p-2 rounded-xl">
                    <i class="far fa-calendar-alt text-xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase tracking-wider font-bold opacity-80">Hari Ini</span>
                    <span class="font-bold text-sm">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </div>
        </div>
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-48 h-48 bg-blue-400/20 rounded-full blur-2xl"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Users -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-50 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pengguna</span>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">Aktif</span>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-extrabold text-gray-800 mb-1">{{ $stats['total_users'] }}</h3>
                <p class="text-gray-500 text-sm font-medium">Siswa & Pengajar</p>
            </div>
        </div>

        <!-- Total Soal -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-50 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-book-reader text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Materi</span>
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full">Bank Soal</span>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-extrabold text-gray-800 mb-1">{{ $stats['total_questions'] }}</h3>
                <p class="text-gray-500 text-sm font-medium">Pertanyaan Tersedia</p>
            </div>
        </div>

        <!-- Total Level -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-teal-50 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="p-4 bg-teal-50 text-teal-600 rounded-2xl group-hover:bg-teal-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-gamepad text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Permainan</span>
                    <span class="px-2.5 py-1 bg-teal-50 text-teal-600 text-[10px] font-bold rounded-full">Level</span>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-extrabold text-gray-800 mb-1">{{ $stats['total_levels'] }}</h3>
                <p class="text-gray-500 text-sm font-medium">Tingkat Permainan</p>
            </div>
        </div>

        <!-- Total Attempts -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-amber-50 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Progres</span>
                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-full">Aktivitas</span>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-extrabold text-gray-800 mb-1">{{ $stats['total_attempts'] }}</h3>
                <p class="text-gray-500 text-sm font-medium">Latihan Selesai</p>
            </div>
        </div>
    </div>

    <!-- Quick Access Section -->
    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 border-b-4 border-b-blue-500">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-extrabold text-gray-800 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                    Menu Cepat Guru
                </h2>
                <p class="text-gray-400 text-sm mt-1 ml-5">Akses fitur utama dengan satu klik</p>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('admin.questions.create') }}" class="group flex flex-col items-center justify-center p-6 bg-blue-50/50 rounded-3xl hover:bg-blue-600 transition-all duration-500 border-2 border-dashed border-blue-200 hover:border-blue-600 hover:-translate-y-2">
                <div class="w-16 h-16 flex items-center justify-center bg-white rounded-2xl shadow-sm mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 text-blue-500">
                    <i class="fas fa-plus-circle text-2xl"></i>
                </div>
                <span class="text-sm font-bold text-gray-700 group-hover:text-white text-center">Buat Soal Baru</span>
            </a>

            <a href="{{ route('admin.users.create') }}" class="group flex flex-col items-center justify-center p-6 bg-teal-50/50 rounded-3xl hover:bg-teal-600 transition-all duration-500 border-2 border-dashed border-teal-200 hover:border-teal-600 hover:-translate-y-2">
                <div class="w-16 h-16 flex items-center justify-center bg-white rounded-2xl shadow-sm mb-4 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500 text-teal-500">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <span class="text-sm font-bold text-gray-700 group-hover:text-white text-center">Tambah Siswa</span>
            </a>

            <a href="{{ route('admin.grades.index') }}" class="group flex flex-col items-center justify-center p-6 bg-indigo-50/50 rounded-3xl hover:bg-indigo-600 transition-all duration-500 border-2 border-dashed border-indigo-200 hover:border-indigo-600 hover:-translate-y-2">
                <div class="w-16 h-16 flex items-center justify-center bg-white rounded-2xl shadow-sm mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 text-indigo-500">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                </div>
                <span class="text-sm font-bold text-gray-700 group-hover:text-white text-center">Rekap Nilai</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="group flex flex-col items-center justify-center p-6 bg-gray-50 rounded-3xl hover:bg-gray-800 transition-all duration-500 border-2 border-dashed border-gray-200 hover:border-gray-800 hover:-translate-y-2">
                <div class="w-16 h-16 flex items-center justify-center bg-white rounded-2xl shadow-sm mb-4 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500 text-gray-600">
                    <i class="fas fa-tools text-2xl"></i>
                </div>
                <span class="text-sm font-bold text-gray-700 group-hover:text-white text-center">Pengaturan</span>
            </a>
        </div>
    </div>
</div>
@endsection
