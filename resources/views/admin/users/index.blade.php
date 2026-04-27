@extends('admin.layouts.main')

@section('title', 'Kelola Pengguna')

@section('container')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manajemen data admin dan siswa</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-all text-sm">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    {{-- Search --}}
    <form action="{{ route('admin.users.index') }}" method="GET"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-800 placeholder-gray-300 focus:outline-none focus:border-indigo-300 focus:bg-white transition-all">
        </div>
        <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.users.index') }}"
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
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Pengguna</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Bergabung</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 text-gray-400 text-xs font-medium">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br
                                        {{ $user->role->value === 'admin' ? 'from-purple-400 to-indigo-500' : 'from-teal-400 to-cyan-500' }}
                                        flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        @if($user->nickname)
                                            <p class="text-xs text-gray-400">@{{ $user->nickname }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @if($user->role->value === 'admin')
                                    <span class="bg-purple-50 text-purple-600 text-xs font-bold px-2.5 py-1 rounded-full">Admin</span>
                                @else
                                    <span class="bg-teal-50 text-teal-600 text-xs font-bold px-2.5 py-1 rounded-full">Siswa</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-xs">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.scores', $user->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all"
                                       title="Lihat Skor">
                                        <i class="fas fa-chart-line text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all"
                                       title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all"
                                                title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="text-gray-300 text-4xl mb-3"><i class="fas fa-users-slash"></i></div>
                                <p class="font-semibold text-gray-500">Belum ada pengguna</p>
                                <a href="{{ route('admin.users.create') }}" class="text-indigo-600 text-sm font-semibold hover:underline mt-1 inline-block">
                                    Tambah pengguna baru →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-50 bg-gray-50/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
