@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12 px-2">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100 border-l-8 border-l-slate-400">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Pengaturan & Pemeliharaan ⚙️</h1>
                <p class="text-gray-500 text-sm">Sesuaikan konfigurasi aplikasi dan amankan data Anda.</p>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- General Settings Card -->
        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 relative group">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-sliders-h text-6xl text-slate-300"></i>
            </div>
            
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-600 shadow-inner">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Pengaturan Umum</h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Identitas Aplikasi</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 ml-1">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ config('app.name', 'Gamifikasi') }}" 
                           class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-slate-500 focus:bg-white focus:ring-0 transition-all text-sm font-medium">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 ml-1">Tahun Ajaran Aktif</label>
                    <div class="relative">
                        <input type="text" name="academic_year" value="2025/2026" 
                               class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-slate-500 focus:bg-white focus:ring-0 transition-all text-sm font-medium">
                        <i class="far fa-calendar-alt absolute right-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 px-6 rounded-2xl shadow-lg transition-all flex items-center justify-center transform hover:-translate-y-1">
                        <i class="fas fa-save mr-3"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Backup & Maintenance Card -->
        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 flex flex-col h-full">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Keamanan Data</h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Backup & Log Sistem</p>
                </div>
            </div>

            <div class="mb-8">
                <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-6 mb-6">
                    <div class="flex gap-4">
                        <div class="text-emerald-500 mt-1">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <p class="text-xs text-emerald-800 leading-relaxed">
                            <strong>Penting:</strong> Selalu lakukan backup database sebelum melakukan update besar untuk menghindari kehilangan data siswa.
                        </p>
                    </div>
                </div>
                
                <form action="{{ route('admin.settings.backup') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-emerald-100 transition-all flex justify-center items-center transform hover:-translate-y-1">
                        <i class="fas fa-database mr-3"></i> Amankan Data Sekarang
                    </button>
                </form>
            </div>

            <div class="flex-1">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-history mr-2 text-slate-300"></i> Log Aktivitas Guru
                </h3>
                <div class="bg-gray-900 text-gray-400 p-6 rounded-2xl h-48 overflow-y-auto text-[10px] font-mono border border-gray-800 shadow-inner custom-scrollbar">
                    <p class="mb-2"><span class="text-emerald-500 opacity-60">[{{ now()->format('Y-m-d H:i') }}]</span> User Admin masuk ke dashboard.</p>
                    <p class="mb-2"><span class="text-blue-500 opacity-60">[{{ now()->subMinutes(5)->format('Y-m-d H:i') }}]</span> Pengaturan umum diperbarui.</p>
                    <p class="mb-2"><span class="text-amber-500 opacity-60">[{{ now()->subMinutes(10)->format('Y-m-d H:i') }}]</span> Proses pembersihan cache selesai.</p>
                    <p class="mb-2"><span class="text-emerald-500 opacity-60">[{{ now()->subMinutes(12)->format('Y-m-d H:i') }}]</span> Backup data berhasil dilakukan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 10px;
    }
</style>
@endsection

