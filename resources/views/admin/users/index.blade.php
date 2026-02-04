@extends('admin.layouts.main')

@section('container')
<div class="mt-24 pb-12 px-2">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100 border-l-8 border-l-teal-500">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Manajemen User Guru & Siswa 👥</h1>
                <p class="text-gray-500 text-sm">Kelola data seluruh pengguna sistem dalam satu tempat.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-teal-100 transition-all flex items-center transform hover:-translate-y-1">
                <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
            </a>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-8 border border-gray-100">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-9 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-300 group-focus-within:text-teal-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama, email, atau nickname siswa..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:border-teal-500 focus:bg-white focus:ring-0 transition-all text-sm font-medium">
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition-all">
                    Cari Pengguna
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">No</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Nickname & Info</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Kelas</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Role</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Poin Belajar</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Bergabung</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-teal-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-gray-300 group-hover:text-teal-400">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $user->role == 'admin' ? 'from-purple-500 to-indigo-600' : 'from-teal-400 to-cyan-500' }} flex items-center justify-center text-white shadow-sm font-bold">
                                    {{ substr($user->nickname, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $user->nickname }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium">{{ $user->email ?? 'Tidak ada email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($user->grade)
                                <span class="bg-orange-50 text-orange-600 text-[10px] font-bold px-3 py-1 rounded-full border border-orange-100 uppercase">Kelas {{ $user->grade }}</span>
                            @else
                                <span class="text-gray-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($user->role == 'admin')
                                <span class="bg-purple-50 text-purple-600 text-[10px] font-bold px-3 py-1 rounded-full border border-purple-100 uppercase">Administrator</span>
                            @else
                                <span class="bg-teal-50 text-teal-600 text-[10px] font-bold px-3 py-1 rounded-full border border-teal-100 uppercase">Siswa</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-100">
                                <i class="fas fa-star text-[10px]"></i> {{ number_format($user->total_points) }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs text-gray-500 font-medium">
                                <i class="far fa-calendar-alt mr-1 opacity-50"></i> {{ $user->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm border border-amber-100">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <button type="button" onclick="confirmDelete('{{ $user->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm border border-red-100">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                                <i class="fas fa-users-slash text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Belum Ada Pengguna</h3>
                            <p class="text-gray-400 text-sm mt-1 mb-6 max-w-xs mx-auto">Data guru dan siswa belum tersedia. Silakan tambahkan data baru!</p>
                            <a href="{{ route('admin.users.create') }}" class="inline-flex bg-teal-600 text-white font-bold py-2 px-6 rounded-xl hover:bg-teal-700 transition-all shadow-lg shadow-teal-100">
                                Buat Akun Baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '<h3 class="font-bold text-gray-800">Hapus Akun Pengguna?</h3>',
            html: '<p class="text-sm text-gray-500">Akses pengguna ini akan dicabut secara permanen, lho.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Akun',
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

