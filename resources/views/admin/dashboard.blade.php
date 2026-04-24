@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-slate-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Dashboard <span class="text-indigo-600">Admin</span></h1>
                <p class="mt-3 max-w-2xl text-sm font-medium leading-relaxed text-slate-500">
                    Kelola data utama perpustakaan, pantau statistik peminjaman, dan ekspor laporan dengan tampilan yang lebih bersih.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-emerald-700 ring-1 ring-emerald-100">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                System online
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Total User</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ $totalMember }}</p>
                        <p class="mt-1 text-[11px] font-medium text-slate-400 italic">Active members</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Total Buku</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ $totalAlat }}</p>
                        <p class="mt-1 text-[11px] font-medium text-slate-400 italic">Available items</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <i class="fas fa-book text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Loan Aktif</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ $sewaAktif }}</p>
                        <p class="mt-1 text-[11px] font-medium text-slate-400 italic">Ongoing loans</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Return</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ $dikembalikan }}</p>
                        <p class="mt-1 text-[11px] font-medium text-slate-400 italic">Completed</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="fas fa-undo-alt text-sm"></i>
                    </div>
                </div>
            </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Total Denda</p>
                    <p class="mt-2 text-xl font-black tracking-tight text-slate-900">
                        Rp{{ number_format($totalDendaInPeriod, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-[11px] font-medium text-slate-400 italic">Accumulated</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <i class="fas fa-file-invoice-dollar text-sm"></i>
                </div>
            </div>
            <a href="{{ route('admin.denda') }}"
                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-white transition hover:bg-slate-800">
                Kelola Denda
            </a>
        </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-xl">
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-indigo-500">Analytics & Reports</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Cetak Laporan Peminjaman</h2>
                    <p class="mt-2 text-sm font-medium leading-relaxed text-slate-500">
                        Ekspor laporan transaksi sesuai periode. Data diambil langsung dari database aktif untuk keperluan dokumentasi dan arsip perpustakaan.
                    </p>
                </div>
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
                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400 ml-1">Mulai</label>
                        <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-semibold text-slate-700 outline-none transition duration-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5">
                    </div>
                    <div>
                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400 ml-1">Selesai</label>
                        <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-semibold text-slate-700 outline-none transition duration-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5">
                    </div>
                    <button type="submit"
                        class="inline-flex h-[46px] items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 text-xs font-bold uppercase tracking-widest text-white shadow-lg shadow-indigo-100 transition duration-200 hover:-translate-y-0.5 hover:bg-indigo-700 active:scale-95">
                        <i class="fas fa-file-pdf"></i>
                        Export Laporan
                    </button>
                </form>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-indigo-500">Visual Insights</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Statistik Peminjaman</h2>
                    <p class="mt-1 text-xs font-medium text-slate-400">{{ $chartTitle }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'hari']) }}"
                        class="rounded-xl px-4 py-2 text-[11px] font-bold uppercase tracking-widest transition duration-200 {{ $timeframe === 'hari' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'border border-slate-200 bg-white text-slate-500 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        Daily
                    </a>
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'minggu']) }}"
                        class="rounded-xl px-4 py-2 text-[11px] font-bold uppercase tracking-widest transition duration-200 {{ $timeframe === 'minggu' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'border border-slate-200 bg-white text-slate-500 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        Weekly
                    </a>
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'bulan']) }}"
                        class="rounded-xl px-4 py-2 text-[11px] font-bold uppercase tracking-widest transition duration-200 {{ $timeframe === 'bulan' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'border border-slate-200 bg-white text-slate-500 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        Monthly
                    </a>
                    <a href="{{ route('admin.dashboard', ['timeframe' => 'tahun']) }}"
                        class="rounded-xl px-4 py-2 text-[11px] font-bold uppercase tracking-widest transition duration-200 {{ $timeframe === 'tahun' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'border border-slate-200 bg-white text-slate-500 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        Yearly
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
