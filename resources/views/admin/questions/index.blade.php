@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12 px-2">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100 border-l-8 border-l-indigo-500">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Bank Soal Guru 📚</h1>
                <p class="text-gray-500 text-sm">Kelola semua pertanyaan kuis untuk para siswa di sini.</p>
            </div>
            <a href="{{ route('admin.questions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-indigo-100 transition-all flex items-center transform hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i> Tambah Soal Baru
            </a>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-8 border border-gray-100">
        <form action="{{ route('admin.questions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-5 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Cari materi atau bahasan soal..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all text-sm font-medium">
            </div>

            <div class="md:col-span-4 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-layer-group text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <select name="level_id" id="level_id" 
                        class="w-full pl-11 pr-10 py-3 rounded-2xl bg-gray-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 appearance-none transition-all text-sm font-medium">
                    <option value="">Semua Level Permainan</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>{{ $level->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition-all">
                    Saring Soal
                </button>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse($questions as $question)
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:shadow-indigo-50/50 transition-all duration-300 group border-l-8 {{ $question->type == 'multiple_choice' ? 'border-l-blue-400' : 'border-l-purple-400' }}">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fas fa-tag mr-1 opacity-50"></i> {{ $question->level->title ?? 'Tanpa Level' }}
                        </span>
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fas fa-list-ul mr-1 opacity-50"></i> {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                        </span>
                    </div>
                    
                    <div class="bg-gray-50/50 rounded-2xl p-6 mb-4 border border-gray-100 text-gray-700 leading-relaxed font-medium">
                        {!! $question->content !!}
                    </div>

                    @if($question->image_path)
                        <div class="mt-4 ring-8 ring-gray-50 rounded-2xl inline-block overflow-hidden transition-all group-hover:ring-indigo-50">
                            <img src="{{ asset('storage/' . $question->image_path) }}" alt="Gambar Soal" class="max-h-48 rounded-xl object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    @endif
                </div>

                <div class="flex flex-row md:flex-col gap-2 w-full md:w-auto">
                    <a href="{{ route('admin.questions.edit', $question->id) }}" class="flex-1 md:flex-none inline-flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white font-bold py-3 px-4 rounded-xl transition-all shadow-sm border border-amber-100">
                        <i class="fas fa-pencil-alt mr-2 md:mr-0 text-sm"></i> <span class="md:hidden">Edit</span>
                    </a>
                    <button type="button" onclick="confirmDelete('{{ $question->id }}')" class="flex-1 md:flex-none inline-flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white font-bold py-3 px-4 rounded-xl transition-all shadow-sm border border-red-100">
                        <i class="fas fa-trash-alt mr-2 md:mr-0 text-sm"></i> <span class="md:hidden text-sm">Hapus</span>
                    </button>
                    <form id="delete-form-{{ $question->id }}" action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl shadow-sm p-16 text-center border border-gray-100">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                <i class="fas fa-cloud-moon text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Bank Soal Masih Kosong</h3>
            <p class="text-gray-400 mt-2 mb-8 max-w-xs mx-auto text-sm">Silakan buat pertanyaan baru agar siswa bisa mulai belajar sambil bermain!</p>
            <a href="{{ route('admin.questions.create') }}" class="inline-flex bg-indigo-600 text-white font-bold py-3 px-8 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                Buat Soal Sekarang
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $questions->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '<h3 class="font-bold text-gray-800">Hapus Soal ini?</h3>',
            html: '<p class="text-sm text-gray-500">Data yang sudah dihapus tidak bisa dikembalikan lagi, lho.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Saja',
            cancelButtonText: 'Batal',
            padding: '2rem',
            borderRadius: '2rem'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection

