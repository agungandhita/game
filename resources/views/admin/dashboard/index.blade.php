@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('container')
<div class="space-y-6">

    {{-- ─── Header ─── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">Halo, <strong>{{ auth()->user()->name }}</strong>! Selamat datang kembali. 👋</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-4 py-2.5 shadow-sm text-sm text-gray-600">
            <i class="far fa-calendar-alt text-indigo-500"></i>
            <span class="font-medium">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    {{-- ─── Stats Cards ─── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Siswa --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-500"></i>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Siswa</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_students'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Total Terdaftar</p>
        </div>

        {{-- Total Soal --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-circle-question text-indigo-500"></i>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">Soal</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_questions'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Bank Soal</p>
        </div>

        {{-- Total Level --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gamepad text-purple-500"></i>
                </div>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Level</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_levels'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Level Tersedia</p>
        </div>

        {{-- Sesi Hari Ini --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bolt text-amber-500"></i>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Hari Ini</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['today_sessions'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Sesi Berjalan</p>
        </div>
    </div>

    {{-- ─── Chart + Top Students ─── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-bold text-gray-800">Aktivitas Kuis</h2>
                    <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
                </div>
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            </div>
            <div style="height: 220px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        {{-- Top Students --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h2 class="font-bold text-gray-800 mb-1">🏆 Top Siswa</h2>
            <p class="text-xs text-gray-400 mb-5">Berdasarkan rata-rata skor</p>

            @forelse($topStudents as $idx => $student)
                <div class="flex items-center gap-3 mb-3 {{ !$loop->last ? 'pb-3 border-b border-gray-50' : '' }}">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                        {{ $idx === 0 ? 'bg-yellow-100 text-yellow-700' : ($idx === 1 ? 'bg-gray-100 text-gray-600' : ($idx === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">
                        {{ $idx + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $student->name }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <div class="h-1.5 rounded-full bg-gray-100 flex-1 overflow-hidden">
                                <div class="h-full rounded-full bg-indigo-400" style="width: {{ min(100, $student->avg_score ?? 0) }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-indigo-600 flex-shrink-0">{{ number_format($student->avg_score ?? 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-users text-2xl mb-2"></i>
                    <p class="text-sm">Belum ada data siswa</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ─── Quick Access ─── --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <h2 class="font-bold text-gray-800 mb-5">Menu Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('admin.questions.create') }}"
               class="flex flex-col items-center gap-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-xl text-blue-700 transition-all group">
                <i class="fas fa-plus-circle text-2xl group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-bold text-center">Tambah Soal</span>
            </a>
            <a href="{{ route('admin.users.create') }}"
               class="flex flex-col items-center gap-3 p-4 bg-teal-50 hover:bg-teal-100 rounded-xl text-teal-700 transition-all group">
                <i class="fas fa-user-plus text-2xl group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-bold text-center">Tambah Siswa</span>
            </a>
            <a href="{{ route('admin.grades.index') }}"
               class="flex flex-col items-center gap-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-xl text-purple-700 transition-all group">
                <i class="fas fa-graduation-cap text-2xl group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-bold text-center">Kelola Kelas</span>
            </a>
            <a href="{{ route('admin.scores.index') }}"
               class="flex flex-col items-center gap-3 p-4 bg-amber-50 hover:bg-amber-100 rounded-xl text-amber-700 transition-all group">
                <i class="fas fa-chart-bar text-2xl group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-bold text-center">Laporan Skor</span>
            </a>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('activityChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Sesi Kuis',
                data: @json($chartData),
                backgroundColor: 'rgba(99, 102, 241, 0.15)',
                borderColor: 'rgba(99, 102, 241, 0.8)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e1b4b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => `${ctx.raw} sesi`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, family: 'Inter' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { stepSize: 1, font: { size: 11, family: 'Inter' } }
                }
            }
        }
    });
</script>
@endsection
