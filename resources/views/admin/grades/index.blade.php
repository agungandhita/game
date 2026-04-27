@extends('admin.layouts.main')

@section('title', 'Manajemen Kelas')

@section('container')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Kelas</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pantau soal dan aktivitas per kelas</p>
    </div>

    {{-- Grade Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($grades as $grade)
            <a href="{{ route('admin.grades.show', $grade->id) }}"
               class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-indigo-500 text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">
                            Urutan {{ $grade->order }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $grade->name }}</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-indigo-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-indigo-600">{{ $grade->total_questions }}</p>
                            <p class="text-xs text-indigo-400 font-medium mt-0.5">Soal</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-green-600">{{ $grade->levels->count() }}</p>
                            <p class="text-xs text-green-400 font-medium mt-0.5">Level</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3 flex items-center justify-between border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-500">
                        <i class="fas fa-users mr-1 text-gray-300"></i>
                        {{ $grade->total_students }} siswa aktif
                    </span>
                    <span class="text-indigo-500 text-xs font-semibold">Lihat Detail →</span>
                </div>
            </a>
        @endforeach

        @if($grades->isEmpty())
            <div class="col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="text-gray-200 text-5xl mb-4"><i class="fas fa-graduation-cap"></i></div>
                <p class="font-semibold text-gray-500">Belum ada data kelas</p>
            </div>
        @endif
    </div>
</div>
@endsection
