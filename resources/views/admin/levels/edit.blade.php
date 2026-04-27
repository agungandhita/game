@extends('admin.layouts.main')

@section('title', 'Edit Level')

@section('container')
<div class="max-w-lg mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.grades.show', $level->grade_id) }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Level</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $level->grade->name }} — {{ $level->name }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.levels.update', $level->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Level --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Level</label>
                <input type="text" name="name" id="name" value="{{ old('name', $level->name) }}"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Waktu per soal --}}
            <div>
                <label for="time_per_question" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Waktu per Soal (detik) <span class="text-red-400">*</span>
                </label>
                <input type="number" name="time_per_question" id="time_per_question"
                       value="{{ old('time_per_question', $level->time_per_question) }}"
                       min="5" max="300"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                       required>
                @error('time_per_question') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Minimum 5 detik, maksimum 300 detik</p>
            </div>

            {{-- Status Aktif --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Level</label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $level->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 accent-indigo-600">
                    <span class="text-sm font-medium text-gray-700">Level aktif (dapat diakses siswa)</span>
                </label>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Info --}}
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500">
                <p class="font-semibold text-gray-700 mb-2">Informasi Level Saat Ini:</p>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <p class="text-xs font-medium text-gray-400">Kelas</p>
                        <p class="font-semibold text-gray-700">{{ $level->grade->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400">Urutan</p>
                        <p class="font-semibold text-gray-700">Level {{ $level->order }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400">Jumlah Soal</p>
                        <p class="font-semibold text-gray-700">{{ $level->questions()->count() }} soal</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400">Skor Minimum</p>
                        <p class="font-semibold text-gray-700">{{ $level->min_score }}</p>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2 border-t border-gray-50">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-all text-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.grades.show', $level->grade_id) }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2.5 px-5 rounded-xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
