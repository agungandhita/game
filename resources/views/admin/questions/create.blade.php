@extends('admin.layouts.main')

@section('title', 'Tambah Soal')

@section('container')
<div class="max-w-2xl mx-auto space-y-6" x-data="{ selectedGrade: '{{ old('grade_id') }}', levels: [] }"
     x-init="
        if (selectedGrade) {
            fetch('/admin/levels-by-grade/' + selectedGrade)
                .then(r => r.json())
                .then(data => levels = data)
                .catch(() => {});
        }
     ">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.questions.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Soal Baru</h1>
            <p class="text-sm text-gray-500 mt-0.5">Soal pilihan ganda dengan 4 opsi jawaban</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Kelas --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas <span class="text-red-400">*</span></label>
                <select name="grade_id" x-model="selectedGrade"
                        @change="
                            levels = [];
                            if (selectedGrade) {
                                fetch(`{{ url('/admin/levels-by-grade/') }}/` + selectedGrade)
                                    .then(r => r.json())
                                    .then(data => levels = data);
                            }
                        "
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                        required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                    @endforeach
                </select>
                @error('grade_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Level --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Level <span class="text-red-400">*</span></label>
                <select name="level_id"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                        required>
                    <option value="">-- Pilih Level --</option>
                    <template x-for="level in levels" :key="level.id">
                        <option :value="level.id" x-text="level.name"
                                :selected="level.id === '{{ old('level_id') }}'"></option>
                    </template>
                    {{-- Fallback jika ada old value dan levels belum di-load --}}
                    @if(old('level_id'))
                        @php $oldLevel = \App\Models\Quiz\Level::find(old('level_id')); @endphp
                        @if($oldLevel)
                            <option value="{{ $oldLevel->id }}" selected>{{ $oldLevel->name }}</option>
                        @endif
                    @endif
                </select>
                @error('level_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Pertanyaan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Teks Pertanyaan <span class="text-red-400">*</span></label>
                <textarea name="question_text" rows="3"
                          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all resize-none"
                          placeholder="Masukkan teks pertanyaan di sini..." required>{{ old('question_text') }}</textarea>
                @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Opsi Jawaban --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilihan Jawaban <span class="text-red-400">*</span></label>
                <div class="space-y-3">
                    @foreach(['A', 'B', 'C', 'D'] as $index => $label)
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer flex-shrink-0">
                                <input type="radio" name="correct_option" value="{{ $index }}"
                                       {{ old('correct_option') == $index ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 accent-indigo-600" required>
                                <span class="w-7 h-7 bg-indigo-50 text-indigo-600 font-bold text-xs rounded-lg flex items-center justify-center">
                                    {{ $label }}
                                </span>
                            </label>
                            <input type="text" name="options[]" value="{{ old("options.$index") }}"
                                   class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                                   placeholder="Opsi {{ $label }}..." required>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-2 font-medium">
                    <i class="fas fa-info-circle mr-1"></i>
                    Pilih radio button di sebelah kiri untuk menandai jawaban yang benar
                </p>
                @error('correct_option') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('options') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2 border-t border-gray-50">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-all text-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Soal
                </button>
                <a href="{{ route('admin.questions.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2.5 px-5 rounded-xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
