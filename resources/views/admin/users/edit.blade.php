@extends('admin.layouts.main')

@section('title', 'Edit Pengguna')

@section('container')
<div class="max-w-lg mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Pengguna</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                       required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                       required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-1.5">Role</label>
                <select name="role" id="role"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all">
                    <option value="student" {{ old('role', $user->role->value) === 'student' ? 'selected' : '' }}>Siswa</option>
                    <option value="admin" {{ old('role', $user->role->value) === 'admin' ? 'selected' : '' }}>Admin / Guru</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password (opsional) --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                       placeholder="Minimal 8 karakter">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-300 focus:bg-white transition-all"
                       placeholder="Ulangi password baru">
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-all text-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2.5 px-5 rounded-xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
