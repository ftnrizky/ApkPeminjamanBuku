<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Pustaka Petugas | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        * {
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.625;
        }

        .app-sidebar {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            box-shadow: 0 24px 48px -36px rgba(15, 23, 42, 0.18);
        }

        .app-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            border-radius: 1rem;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.22s ease;
        }

        .app-link i {
            width: 18px;
            text-align: center;
            color: #94a3b8;
        }

        .app-link:hover {
            background: #f8fafc;
            color: #2563eb;
            transform: translateX(2px);
        }

        .app-link:hover i,
        .app-link.active i {
            color: #3b82f6;
        }

        .app-link.active {
            background: #eff6ff;
            color: #2563eb;
            box-shadow: inset 0 0 0 1px #bfdbfe;
        }

        .app-section {
            padding: 0 0.5rem;
            margin: 1rem 0 0.5rem;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #94a3b8;
        }

        .app-topbar {
            background: rgba(248, 250, 252, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid #e2e8f0;
        }

        .app-logo-box {
            display: flex;
            height: 2.8rem;
            width: 2.8rem;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: #eff6ff;
            color: #3b82f6;
            box-shadow: inset 0 0 0 1px #dbeafe;
        }
    </style>
</head>

<body class="font-sans text-slate-800 leading-relaxed antialiased">
    <div class="relative flex h-screen overflow-hidden">
        <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-sm lg:hidden"
            onclick="toggleSidebar(true)"></div>

        <aside id="sidebar"
            class="app-sidebar fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] -translate-x-full flex-col lg:relative lg:translate-x-0">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-6">
                <div class="flex items-center gap-3">
                    <div class="app-logo-box">
                        <i class="fas fa-book-open text-lg"></i>
                    </div>
                    <div>
                        <p class="text-lg font-extrabold tracking-tight text-slate-900">E-<span class="text-blue-500">PUSTAKA</span></p>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400">Staff Panel</p>
                    </div>
                </div>

                <button onclick="toggleSidebar(true)" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 lg:hidden">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-5">
                <p class="app-section">Menu Utama</p>
                <a href="{{ route('petugas.dashboard') }}"
                    class="app-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>

                <p class="app-section">Transaksi & Laporan</p>
                <a href="{{ route('petugas.menyetujui_peminjaman') }}"
                    class="app-link {{ request()->routeIs('petugas.menyetujui_peminjaman') ? 'active' : '' }}">
                    <i class="fas fa-check-square"></i>
                    <span>Setujui Pinjam</span>
                </a>
                <a href="{{ route('petugas.menyetujui_kembali') }}"
                    class="app-link {{ request()->routeIs('petugas.menyetujui_kembali') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt"></i>
                    <span>Verifikasi Kembali</span>
                </a>
                <a href="{{ route('petugas.denda') }}"
                    class="app-link {{ request()->routeIs('petugas.denda') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Kelola Denda</span>
                    @php
                        $pendingDenda = \App\Models\Denda::where('is_denda_lunas', false)->count();
                    @endphp
                    @if ($pendingDenda > 0)
                        <span class="ml-auto inline-flex min-w-[20px] items-center justify-center rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white">
                            {{ $pendingDenda }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('petugas.laporan') }}"
                    class="app-link {{ request()->routeIs('petugas.laporan') ? 'active' : '' }}">
                    <i class="fas fa-file-pdf"></i>
                    <span>Cetak Laporan</span>
                </a>
                <a href="{{ route('petugas.overdue_list') }}"
                    class="app-link {{ request()->routeIs('petugas.overdue_list') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Belum Dikembalikan</span>
                </a>
            </nav>

            <div class="border-t border-slate-200 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-500 px-4 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-rose-400 hover:shadow-md">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="app-topbar sticky top-0 z-30 flex items-center justify-between px-4 py-4 shadow-sm lg:hidden">
                <div class="flex items-center gap-3">
                    <div class="app-logo-box h-10 w-10 rounded-xl">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold tracking-tight text-slate-900">E-<span class="text-blue-500">PUSTAKA</span></p>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-400">Petugas</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </header>

            <div class="app-topbar sticky top-0 z-20 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="hidden lg:block">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Panel Petugas</p>
                    </div>
                    <div class="ml-auto flex justify-end">
                        @include('partials.notification_dropdown')
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-5 sm:px-6 lg:px-8 lg:py-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar(forceClose = false) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                return;
            }

            const willOpen = forceClose ? false : sidebar.classList.contains('-translate-x-full');

            if (willOpen) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                toggleSidebar(true);
                document.getElementById('sidebar').classList.remove('-translate-x-full');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                toggleSidebar(true);
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
