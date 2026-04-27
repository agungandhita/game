@extends('admin.layouts.main')

@section('title', 'Laporan Skor')

@section('container')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Skor</h1>
            <p class="text-sm text-gray-500 mt-0.5">Riwayat hasil kuis semua siswa</p>
        </div>
        <a href="{{ route('admin.scores.export', request()->query()) }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-all text-sm">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
    </div>

    {{-- Filter --}}
    <form action="{{ route('admin.scores.index') }}" method="GET"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-wrap gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama siswa..."
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all">
        </div>
        <select name="grade_id"
                class="py-2.5 px-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-300 transition-all"
                onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }}
                </option>
            @endforeach
        </select>
        @if($levels->isNotEmpty())
            <select name="level_id"
                    class="py-2.5 px-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-300 transition-all">
                <option value="">Semua Level</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        @endif
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="py-2.5 px-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-300 transition-all">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="py-2.5 px-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-300 transition-all">
        <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
            Filter
        </button>
        @if(request()->hasAny(['search','grade_id','level_id','date_from','date_to']))
            <a href="{{ route('admin.scores.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-all">
                Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas / Level</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Skor</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Bintang</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Jawaban</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($scores as $score)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ substr($score->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $score->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $score->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800 text-xs">{{ $score->level->grade->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $score->level->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-lg {{ $score->score >= 70 ? 'text-green-600' : ($score->score >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                                    {{ number_format($score->score, 1) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-0.5">
                                    @for($s = 1; $s <= 3; $s++)
                                        <span class="text-sm {{ $s <= ($score->stars ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-gray-600 font-semibold">
                                <span class="text-green-600">{{ $score->total_correct ?? 0 }}</span>
                                <span class="text-gray-300 mx-1">/</span>
                                <span>{{ $score->total_questions ?? 0 }}</span>
                                @if(($score->total_timeout ?? 0) > 0)
                                    <span class="text-red-400 ml-1">({{ $score->total_timeout }}⏱)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">
                                {{ $score->completed_at ? $score->completed_at->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="text-gray-200 text-4xl mb-3"><i class="fas fa-chart-bar"></i></div>
                                <p class="font-semibold text-gray-500">Belum ada data skor</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($scores->hasPages())
            <div class="px-5 py-4 border-t border-gray-50 bg-gray-50/30">
                {{ $scores->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
