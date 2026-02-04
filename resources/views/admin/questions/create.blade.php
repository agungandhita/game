@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12 px-2">
    <!-- Header Section -->
    <div class="flex items-center mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <a href="{{ route('admin.questions.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-gray-50 text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all mr-4 shadow-inner">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Soal Baru ✨</h1>
            <p class="text-gray-500 text-sm mt-1">Buat tantangan seru untuk para siswa.</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 border-b-8 border-b-indigo-500">
        <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-2">
                    <label for="level_id" class="block text-sm font-bold text-gray-700 ml-1">Pilih Level Game <span class="text-red-400">*</span></label>
                    <div class="relative group">
                        <select name="level_id" id="level_id" class="w-full pl-4 pr-10 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 appearance-none transition-all text-sm font-medium" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->title }} ({{ $level->world->name }})</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none text-xs"></i>
                    </div>
                    @error('level_id') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="type" class="block text-sm font-bold text-gray-700 ml-1">Tipe Pertanyaan <span class="text-red-400">*</span></label>
                    <div class="relative group">
                        <select name="type" id="type" class="w-full pl-4 pr-10 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 appearance-none transition-all text-sm font-medium" required onchange="toggleOptions()">
                            <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>Essay / Isian</option>
                            <option value="counting" {{ old('type') == 'counting' ? 'selected' : '' }}>Berhitung</option>
                            <option value="matching" {{ old('type') == 'matching' ? 'selected' : '' }}>Menjodohkan</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none text-xs"></i>
                    </div>
                    @error('type') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-8 space-y-2">
                <label for="content" class="block text-sm font-bold text-gray-700 ml-1">Isi Pertanyaan <span class="text-red-400">*</span></label>
                <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-inner">
                    <textarea name="content" id="content" rows="4" class="froala-editor w-full p-4 bg-gray-50 focus:bg-white transition-colors border-none ring-0" required>{{ old('content') }}</textarea>
                </div>
                @error('content') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8 space-y-2">
                <label for="image" class="block text-sm font-bold text-gray-700 ml-1">Gambar Pendukung (Opsional)</label>
                <div class="flex items-center justify-center w-full">
                    <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-indigo-50 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Pilih file gambar (Max: 2MB)</p>
                        </div>
                        <input type="file" name="image" id="image" class="hidden">
                    </label>
                </div>
                @error('image') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div id="options-container" class="mb-10 bg-indigo-50/50 rounded-3xl p-8 border-2 border-dashed border-indigo-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900">Opsi Jawaban</h3>
                        <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Tandai satu lingkaran untuk jawaban yang benar</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @for($i = 0; $i < 4; $i++)
                    <div class="relative group bg-white p-4 rounded-2xl border border-indigo-100 shadow-sm transition-all focus-within:ring-2 focus-within:ring-indigo-400">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option') == $i ? 'checked' : ($i == 0 ? 'checked' : '') }} class="w-6 h-6 text-indigo-600 bg-gray-50 border-gray-200 focus:ring-indigo-500 cursor-pointer transition-transform hover:scale-110">
                            <input type="text" name="options[{{ $i }}][content]" placeholder="Tulis jawaban ke-{{ $i + 1 }}..." value="{{ old('options.'.$i.'.content') }}" class="flex-1 bg-transparent border-none focus:ring-0 text-gray-700 font-medium placeholder-gray-300">
                        </div>
                    </div>
                    @endfor
                </div>
                @error('correct_option') <p class="text-red-500 text-[10px] font-bold mt-4 ml-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-12 rounded-2xl shadow-xl shadow-indigo-100 transition-all flex items-center transform hover:-translate-y-1 active:scale-95">
                    <i class="fas fa-save mr-3"></i> Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleOptions() {
        const type = document.getElementById('type').value;
        const container = document.getElementById('options-container');
        if (type === 'multiple_choice') {
            container.style.display = 'block';
            const inputs = container.querySelectorAll('input[type="text"]');
            inputs.forEach(input => input.required = true);
        } else {
            container.style.display = 'none';
            const inputs = container.querySelectorAll('input[type="text"]');
            inputs.forEach(input => input.required = false);
        }
    }

    document.addEventListener('DOMContentLoaded', toggleOptions);
</script>
@endsection

