@extends('admin.layouts.main')

@section('title', $grade->name)

@section('container')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.grades.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $grade->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola level dan soal di kelas ini</p>
        </div>
    </div>

    {{-- Levels --}}
    <div class="space-y-4">
        @foreach($levels as $level)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-indigo-600">{{ $level->order }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $level->name }}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-gray-400 font-medium">
                                    <i class="fas fa-circle-question mr-1"></i>{{ $level->total_questions }} soal
                                </span>
                                <span class="text-xs text-gray-400 font-medium">
                                    <i class="fas fa-clock mr-1"></i>{{ $level->time_per_question }}s/soal
                                </span>
                                <span class="text-xs {{ $level->avg_score >= 70 ? 'text-green-600' : 'text-amber-600' }} font-semibold">
                                    avg {{ number_format($level->avg_score, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs {{ $level->is_active ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-400' }} font-semibold px-2.5 py-1 rounded-full">
                            {{ $level->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <a href="{{ route('admin.levels.edit', $level->id) }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <a href="{{ route('admin.questions.index', ['level_id' => $level->id]) }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-500 hover:bg-indigo-500 hover:text-white transition-all">
                            <i class="fas fa-list text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        @if($levels->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <p class="text-gray-400 font-semibold">Belum ada level di kelas ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
