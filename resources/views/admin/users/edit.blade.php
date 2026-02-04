@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12 px-2">
    <!-- Header Section -->
    <div class="flex items-center mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <a href="{{ route('admin.users.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-gray-50 text-gray-400 hover:bg-teal-50 hover:text-teal-600 transition-all mr-4 shadow-inner">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ubah Data Akun ✏️</h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi profil pengguna di bawah ini.</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 border-b-8 border-b-teal-500 max-w-2xl mx-auto">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="nickname" class="block text-sm font-bold text-gray-700 ml-1">Nama Panggilan / Nickname <span class="text-red-400">*</span></label>
                    <input type="text" name="nickname" id="nickname" value="{{ old('nickname') ?? $user->nickname }}" 
                           class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-teal-500 focus:bg-white focus:ring-0 transition-all text-sm font-medium" 
                           placeholder="Contoh: Budi Santoso" required>
                    @error('nickname') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label for="grade" class="block text-sm font-bold text-gray-700 ml-1">Pilih Kelas <span class="text-red-400">*</span></label>
                        <div class="relative group">
                            <select name="grade" id="grade" class="w-full pl-4 pr-10 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-teal-500 focus:bg-white focus:ring-0 appearance-none transition-all text-sm font-medium" required>
                                <option value="">-- Pilih Kelas --</option>
                                <option value="3" {{ (old('grade') ?? $user->grade) == '3' ? 'selected' : '' }}>Kelas 3</option>
                                <option value="4" {{ (old('grade') ?? $user->grade) == '4' ? 'selected' : '' }}>Kelas 4</option>
                                <option value="5" {{ (old('grade') ?? $user->grade) == '5' ? 'selected' : '' }}>Kelas 5</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none text-xs"></i>
                        </div>
                        @error('grade') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-bold text-gray-700 ml-1">Jenis Akun <span class="text-red-400">*</span></label>
                        <div class="relative group">
                            <select name="role" id="role" class="w-full pl-4 pr-10 py-3.5 rounded-2xl bg-gray-50 border-transparent focus:border-teal-500 focus:bg-white focus:ring-0 appearance-none transition-all text-sm font-medium" required>
                                <option value="student" {{ (old('role') ?? $user->role) == 'student' ? 'selected' : '' }}>Siswa</option>
                                <option value="admin" {{ (old('role') ?? $user->role) == 'admin' ? 'selected' : '' }}>Administrator / Guru</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none text-xs"></i>
                        </div>
                        @error('role') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="w-full md:w-auto bg-teal-600 hover:bg-teal-700 text-white font-bold py-4 px-12 rounded-2xl shadow-xl shadow-teal-100 transition-all flex items-center justify-center transform hover:-translate-y-1 active:scale-95">
                        <i class="fas fa-sync-alt mr-3"></i> Perbarui Akun User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

