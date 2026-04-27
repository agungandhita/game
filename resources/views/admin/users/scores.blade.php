@extends('admin.layouts.main')

@section('title', 'Skor ' . $user->name)

@section('container')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Skor: {{ $user->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $scores->total() }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Total Kuis</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ number_format($scores->avg('score') ?? 0, 1) }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Rata-rata Skor</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-yellow-500">{{ $scores->sum('stars') }} ⭐</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Total Bintang</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Level</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Skor</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Bintang</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Benar</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($scores as $score)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800">{{ $score->level->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $score->level->grade->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-lg {{ $score->score >= 70 ? 'text-green-600' : ($score->score >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                                    {{ number_format($score->score, 1) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-0.5">
                                    @for($s = 1; $s <= 3; $s++)
                                        <span class="{{ $s <= ($score->stars ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center text-xs font-semibold text-gray-600">
                                {{ $score->total_correct ?? 0 }}/{{ $score->total_questions ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">
                                {{ $score->completed_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400 font-semibold">
                                Siswa ini belum mengerjakan kuis
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
