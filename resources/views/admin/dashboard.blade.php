@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Dashboard Admin</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Kelola data utama perpustakaan, pantau statistik peminjaman, dan ekspor laporan dengan tampilan yang lebih bersih.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-100">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                System online
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total User</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalMember }}</p>
                        <p class="mt-2 text-sm text-slate-500">Active members</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Buku</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalAlat }}</p>
                        <p class="mt-2 text-sm text-slate-500">Available items</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Peminjaman Aktif</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $sewaAktif }}</p>
                        <p class="mt-2 text-sm text-slate-500">Ongoing loans</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dikembalikan</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $dikembalikan }}</p>
                        <p class="mt-2 text-sm text-slate-500">Completed loans</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                </div>
            </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Denda</p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">
                        Rp{{ number_format($totalDendaInPeriod, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Akumulasi denda pengguna</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
            <a href="{{ route('admin.denda') }}"
                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-rose-400 hover:shadow-md">
                Kelola Denda
            </a>
        </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-xl">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Laporan</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">Cetak Laporan Peminjaman</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Ekspor laporan transaksi sesuai periode. Data diambil langsung dari database aktif.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="rounded-lg bg-slate-100 px-3 py-2 text-sm text-slate-700">
                            <strong class="font-semibold text-slate-900">Total Transaksi:</strong> {{ $totalPeminjaman }}
                        </span>
                        <span class="rounded-lg bg-slate-100 px-3 py-2 text-sm text-slate-700">
                            <strong class="font-semibold text-slate-900">Total Unit:</strong> {{ $totalUnit }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('admin.dashboard.pdf') }}" method="GET" class="grid gap-4 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                        <i class="fas fa-file-arrow-down text-xs"></i>
                        Export PDF
                    </button>
                </form>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Analitik</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">Statistik Peminjaman</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $chartTitle }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'hari']) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold transition duration-200 {{ $timeframe === 'hari' ? 'bg-blue-500 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }}">
                        7 Hari
                    </a>
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'minggu']) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold transition duration-200 {{ $timeframe === 'minggu' ? 'bg-blue-500 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }}">
                        4 Minggu
                    </a>
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'bulan']) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold transition duration-200 {{ $timeframe === 'bulan' ? 'bg-blue-500 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }}">
                        30 Hari
                    </a>
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'tahun']) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold transition duration-200 {{ $timeframe === 'tahun' ? 'bg-blue-500 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }}">
                        12 Bulan
                    </a>
                </div>
            </div>

            <div class="mt-6 h-80">
                <canvas id="loanChart"></canvas>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
    <script>
        const ctx = document.getElementById('loanChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.22)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($counts),
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 12,
                                weight: '600',
                                family: 'DM Sans'
                            },
                            color: '#64748b',
                            padding: 20,
                            usePointStyle: true,
                            pointStyleWidth: 10
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: {
                            family: 'DM Sans',
                            size: 13,
                            weight: '700'
                        },
                        bodyFont: {
                            family: 'DM Sans',
                            size: 12
                        }
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
                            font: {
                                size: 12,
                                family: 'DM Sans'
                            },
                            color: '#94a3b8',
                            stepSize: 1
                        },
                        grid: {
                            color: '#e2e8f0',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                family: 'DM Sans'
                            },
                            color: '#94a3b8'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
