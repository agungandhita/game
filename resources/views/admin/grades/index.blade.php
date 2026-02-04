@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12 px-2">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100 border-l-8 border-l-teal-500">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Daftar Nilai Siswa 🎓</h1>
                <p class="text-gray-500 text-sm">Lihat pencapaian belajar dan bintang yang dikoleksi para siswa.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.grades.export') }}" class="bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white font-bold py-2.5 px-6 rounded-2xl transition-all flex items-center shadow-sm border border-teal-100">
                    <i class="fas fa-file-download mr-2"></i> Download CSV
                </a>
            </div>
        </div>
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-8 border border-gray-100">
        <form action="{{ route('admin.grades.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-5 relative group">
                <label for="search" class="sr-only">Cari Siswa</label>
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-300 group-focus-within:text-teal-500 transition-colors"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Cari nama siswa..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:border-teal-500 focus:bg-white focus:ring-0 transition-all text-sm font-medium">
            </div>

            <div class="md:col-span-4 relative group">
                <label for="level_id" class="sr-only">Pilih Level</label>
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-filter text-gray-300 group-focus-within:text-teal-500 transition-colors"></i>
                </div>
                <select name="level_id" id="level_id" 
                        class="w-full pl-11 pr-10 py-3 rounded-2xl bg-gray-50 border-transparent focus:border-teal-500 focus:bg-white focus:ring-0 appearance-none transition-all text-sm font-medium">
                    <option value="">Semua Level Permainan</option>
                    @foreach(App\Models\Level::all() as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-teal-100 transition-all">
                    Cari Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Tingkatan</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Hasil Akhir</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Bintang</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status Selesai</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-teal-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center text-teal-600 font-bold text-sm mr-4 group-hover:bg-teal-500 group-hover:text-white transition-all">
                                    {{ substr($grade->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $grade->user->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Total Poin: {{ $grade->user->total_points }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[11px] font-bold border border-blue-100">
                                {{ $grade->level->title }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-xl font-extrabold text-teal-600">{{ $grade->score }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center items-center gap-1">
                                @for($i = 0; $i < 3; $i++)
                                    <i class="fas fa-star {{ $i < $grade->stars ? 'text-amber-400' : 'text-gray-200' }} text-xs transition-colors duration-500"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($grade->is_completed)
                                <span class="bg-teal-100 text-teal-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-teal-200">
                                    Selesai ✨
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-amber-200">
                                    Kurang Sedikit ⏳
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <a href="{{ route('admin.grades.show', $grade->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-teal-500 hover:text-white hover:rotate-12 transition-all shadow-sm">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                                    <i class="fas fa-folder-open text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-500">Belum ada nilai yang tercatat</h3>
                                <p class="text-xs text-gray-400 mt-1">Siswa mungkin belum mengerjakan level ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($grades->hasPages())
        <div class="px-6 py-6 bg-gray-50/50 border-t border-gray-100">
            {{ $grades->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
