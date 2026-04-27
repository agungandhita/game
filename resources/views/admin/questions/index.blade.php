@extends('admin.layouts.main')

@section('title', 'Bank Soal')

@section('container')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bank Soal</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua soal kuis per level</p>
        </div>
        <a href="{{ route('admin.questions.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-all text-sm">
            <i class="fas fa-plus"></i> Tambah Soal
        </a>
    </div>

    {{-- Filter --}}
    <form action="{{ route('admin.questions.index') }}" method="GET"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-wrap gap-3">
        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari teks soal..."
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-800 placeholder-gray-300 focus:outline-none focus:border-indigo-300 focus:bg-white transition-all">
        </div>

        {{-- Filter Kelas --}}
        <select name="grade_id" id="grade_id"
                class="py-2.5 px-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-300 transition-all min-w-[140px]"
                onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }}
                </option>
            @endforeach
        </select>

        {{-- Filter Level --}}
        @if($levels->isNotEmpty())
        <select name="level_id"
                class="py-2.5 px-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-300 transition-all min-w-[140px]">
            <option value="">Semua Level</option>
            @foreach($levels as $level)
                <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>
        @endif

        <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
            Filter
        </button>
        @if(request()->hasAny(['search','grade_id','level_id']))
            <a href="{{ route('admin.questions.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-all">
                Reset
            </a>
        @endif
    </form>

    {{-- Stats --}}
    <div class="text-sm text-gray-500 font-medium">
        Menampilkan <strong class="text-gray-800">{{ $questions->total() }}</strong> soal
    </div>

    {{-- Questions List --}}
    <div class="space-y-3">
        @forelse($questions as $question)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        {{-- Meta --}}
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2.5 py-1 rounded-full">
                                {{ $question->level->grade->name ?? '-' }}
                            </span>
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $question->level->name ?? '-' }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">Soal #{{ $question->order }}</span>
                        </div>

                        {{-- Pertanyaan --}}
                        <p class="text-gray-800 font-medium leading-relaxed mb-3 line-clamp-2">
                            {{ $question->question_text }}
                        </p>

                        {{-- Pilihan jawaban --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach($question->options->sortBy('label') as $option)
                                <span class="text-xs px-3 py-1 rounded-lg font-semibold
                                    {{ $option->is_correct
                                        ? 'bg-green-100 text-green-700 border border-green-200'
                                        : 'bg-gray-50 text-gray-500 border border-gray-100' }}">
                                    {{ $option->label->value ?? $option->label }}.
                                    {{ Str::limit($option->option_text, 30) }}
                                    @if($option->is_correct)
                                        <i class="fas fa-check ml-1"></i>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('admin.questions.edit', $question->id) }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST"
                              onsubmit="return confirm('Hapus soal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="text-gray-200 text-5xl mb-4"><i class="fas fa-circle-question"></i></div>
                <p class="font-semibold text-gray-500 mb-1">Belum ada soal</p>
                <a href="{{ route('admin.questions.create') }}" class="text-indigo-600 text-sm font-semibold hover:underline">
                    Tambah soal pertama →
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($questions->hasPages())
        <div>{{ $questions->links() }}</div>
    @endif

</div>
@endsection
