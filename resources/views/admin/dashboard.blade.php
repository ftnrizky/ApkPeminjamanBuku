@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
        <div>
            <h1 class="text-4xl font-900 text-slate-900 mb-2">Dashboard Admin</h1>
            <p class="text-slate-600 font-500">Kelola laptop E-Laptop dan monitoring aktivitas peminjaman</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-400/30">
            <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span>
            <span class="text-xs font-700 text-cyan-600 uppercase tracking-wider">System Online</span>
        </div>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="group relative overflow-hidden rounded-xl bg-white p-6 border border-slate-200 hover:border-cyan-400/50 transition-all duration-300 shadow-sm hover:shadow-md">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transpaE-Laptop opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-xs font-700 text-slate-500 uppercase tracking-wider">Total User</p>
                <h3 class="text-3xl font-900 text-slate-900 mt-2">{{ $totalMember }}</h3>
                <p class="text-xs text-slate-400 mt-1">Active members</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-400"></div>
    </div>

    <!-- Total Laptops Card -->
    <div class="group relative overflow-hidden rounded-xl bg-white p-6 border border-slate-200 hover:border-cyan-400/50 transition-all duration-300 shadow-sm hover:shadow-md">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transpaE-Laptop opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-xs font-700 text-slate-500 uppercase tracking-wider">Total Laptop</p>
                <h3 class="text-3xl font-900 text-slate-900 mt-2">{{ $totalAlat }}</h3>
                <p class="text-xs text-slate-400 mt-1">Available items</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                <i class="fas fa-laptop text-xl"></i>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-400"></div>
    </div>

    <!-- Active E-Laptops Card -->
    <div class="group relative overflow-hidden rounded-xl bg-white p-6 border border-slate-200 hover:border-cyan-400/50 transition-all duration-300 shadow-sm hover:shadow-md">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transpaE-Laptop opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-xs font-700 text-slate-500 uppercase tracking-wider">Peminjaman Aktif</p>
                <h3 class="text-3xl font-900 text-slate-900 mt-2">{{ $sewaAktif }}</h3>
                <p class="text-xs text-slate-400 mt-1">Ongoing E-Laptops</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-400"></div>
    </div>

    <!-- Returned Card -->
    <div class="group relative overflow-hidden rounded-xl bg-white p-6 border border-slate-200 hover:border-cyan-400/50 transition-all duration-300 shadow-sm hover:shadow-md">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transpaE-Laptop opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-xs font-700 text-slate-500 uppercase tracking-wider">Dikembalikan</p>
                <h3 class="text-3xl font-900 text-slate-900 mt-2">{{ $dikembalikan }}</h3>
                <p class="text-xs text-slate-400 mt-1">Completed E-Laptops</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                <i class="fas fa-undo-alt text-xl"></i>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-400"></div>
    </div>
</div>

<!-- Export Report Section -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
        <div>
            <p class="text-sm font-semibold text-slate-700">Cetak laporan peminjaman sesuai periode.</p>
            <p class="text-xs text-slate-400 mt-1">Semua data diambil langsung dari transaksi yang tersimpan di database.</p>
            <div class="mt-4 text-slate-500 text-xs flex flex-wrap gap-4">
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-2 text-slate-600">
                    <strong class="text-slate-900">Total transaksi</strong>: {{ $totalPeminjaman }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-2 text-slate-600">
                    <strong class="text-slate-900">Total unit</strong>: {{ $totalUnit }}
                </span>
            </div>
        </div>

        <form action="{{ route('admin.dashboard.pdf') }}" method="GET" class="grid w-full gap-4 md:grid-cols-[1fr_1fr_auto] lg:w-auto">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.3em] text-slate-500">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" required class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-cyan-500" />
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.3em] text-slate-500">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" required class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-cyan-500" />
            </div>
            <button type="submit" class="h-14 rounded-xl bg-cyan-600 px-6 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-700">Export PDF</button>
        </form>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    <!-- Header dengan Filter -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8 pb-6 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-800 text-slate-900 flex items-center gap-3">
                <i class="fas fa-chart-line text-cyan-500"></i>
                Statistik Peminjaman
            </h2>
            <p class="text-sm text-slate-500 mt-1">{{ $chartTitle }}</p>
        </div>

        <!-- Filter Buttons -->
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('admin.dashboard', ['timeframe' => 'hari']) }}" 
               class="px-4 py-2 rounded-lg font-600 text-sm transition-all duration-200 {{ $timeframe === 'hari' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-calendar-day mr-1.5"></i>7 Hari
            </a>
            <a href="{{ route('admin.dashboard', ['timeframe' => 'minggu']) }}" 
               class="px-4 py-2 rounded-lg font-600 text-sm transition-all duration-200 {{ $timeframe === 'minggu' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-calendar-week mr-1.5"></i>4 Minggu
            </a>
            <a href="{{ route('admin.dashboard', ['timeframe' => 'bulan']) }}" 
               class="px-4 py-2 rounded-lg font-600 text-sm transition-all duration-200 {{ $timeframe === 'bulan' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-calendar-alt mr-1.5"></i>30 Hari
            </a>
            <a href="{{ route('admin.dashboard', ['timeframe' => 'tahun']) }}" 
               class="px-4 py-2 rounded-lg font-600 text-sm transition-all duration-200 {{ $timeframe === 'tahun' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-calendar mr-1.5"></i>12 Bulan
            </a>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="relative h-[350px]">
        <canvas id="loanChart"></canvas>
    </div>
</div>

@endsection

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
<script>
    const ctx = document.getElementById('loanChart').getContext('2d');
    
    // Gradient untuk chart
    const gradient = ctx.createLinearGradient(0, 0, 0, 350);
    gradient.addColorStop(0, 'rgba(6, 182, 212, 0.25)');
    gradient.addColorStop(1, 'rgba(6, 182, 212, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: @json($counts),
                borderColor: '#06b6d4',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06b6d4',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBorderWidth: 2,
                pointShadowColor: 'rgba(6, 182, 212, 0.3)',
                pointShadowBlur: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: { size: 13, weight: '600' },
                        color: '#475569',
                        padding: 20,
                        usePointStyle: true
                    }
                },
                filler: {
                    propagate: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 12, weight: '500' },
                        color: '#94a3b8',
                        stepSize: 1
                    },
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: { size: 12, weight: '500' },
                        color: '#94a3b8'
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
</script>
@endpush