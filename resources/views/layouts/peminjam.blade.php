<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | E-Pustaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'DM Sans', sans-serif;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
        }

        .app-navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px -26px rgba(15, 23, 42, 0.18);
        }

        .nav-link {
            position: relative;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            padding-bottom: 0.5rem;
            text-decoration: none;
            transition: color 0.24s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.24s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #2563eb;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
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

        .icon-btn {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.95rem;
            background: #ffffff;
            color: #64748b;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 10px 24px -20px rgba(15, 23, 42, 0.28);
        }

        .icon-btn:hover {
            color: #2563eb;
            border-color: #bfdbfe;
            background: #f8fbff;
            transform: translateY(-1px) scale(1.02);
        }

        .bottom-nav-shell {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 -14px 32px -26px rgba(15, 23, 42, 0.26);
        }

        .bottom-nav-link {
            position: relative;
            display: flex;
            min-height: 62px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.32rem;
            border-radius: 1rem;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.24s ease;
        }

        .bottom-nav-link::before {
            content: '';
            position: absolute;
            inset: 5px 10px auto;
            height: 30px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.22) 0%, rgba(96, 165, 250, 0.08) 58%, transparent 100%);
            opacity: 0;
            transform: translateY(8px) scale(0.88);
            transition: all 0.24s ease;
        }

        .bottom-nav-link i {
            font-size: 1rem;
            transition: transform 0.24s ease;
        }

        .bottom-nav-link .bottom-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.03em;
            line-height: 1;
        }

        .bottom-nav-link:hover,
        .bottom-nav-link.active {
            color: #3b82f6;
        }

        .bottom-nav-link:hover::before,
        .bottom-nav-link.active::before {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .bottom-nav-link:hover i,
        .bottom-nav-link.active i {
            transform: translateY(-1px) scale(1.08);
        }

        .bottom-nav-link:active {
            transform: scale(0.95);
        }

        .bottom-nav-badge {
            position: absolute;
            top: 5px;
            right: 16px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 999px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 18px rgba(239, 68, 68, 0.24);
            border: 2px solid rgba(255, 255, 255, 0.95);
        }
    </style>
</head>

<body>
    @php
        $jumlahDendaBelumLunas = \App\Models\Denda::whereHas(
            'peminjaman',
            fn($q) => $q->where('user_id', Auth::id()),
        )
            ->where('is_denda_lunas', false)
            ->count();
    @endphp

    <nav class="app-navbar sticky top-0 z-50">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="app-logo-box">
                    <i class="fas fa-book-open text-lg"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-900">E-<span class="text-blue-500">PUSTAKA</span></span>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400">Borrower Portal</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('peminjam.dashboard') }}"
                    class="nav-link {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('peminjam.katalog') }}"
                    class="nav-link {{ request()->routeIs('peminjam.katalog') ? 'active' : '' }}">Katalog</a>
                <a href="{{ route('peminjam.kembali') }}"
                    class="nav-link {{ request()->routeIs('peminjam.kembali') ? 'active' : '' }}">Pengembalian</a>
                <a href="{{ route('peminjam.riwayat') }}"
                    class="nav-link {{ request()->routeIs('peminjam.riwayat') ? 'active' : '' }}">Riwayat</a>
                <a href="{{ route('peminjam.denda') }}"
                    class="nav-link relative {{ request()->routeIs('peminjam.denda*') ? 'active' : '' }}">
                    Denda
                    @if ($jumlahDendaBelumLunas > 0)
                        <span class="absolute -right-3 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white">
                            {{ $jumlahDendaBelumLunas }}
                        </span>
                    @endif
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Peminjam</p>
                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                </div>

                <div>
                    @include('partials.notification_dropdown')
                </div>

                <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                    @csrf
                    <button type="submit" class="icon-btn">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit" class="icon-btn">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-6 pb-28 md:px-6 md:py-10 md:pb-10">
        @yield('content')
    </main>

    <div class="fixed inset-x-0 bottom-0 z-50 px-3 md:hidden"
        style="padding-bottom: calc(env(safe-area-inset-bottom) + 0.75rem);">
        <div class="bottom-nav-shell mx-auto max-w-xl rounded-t-[28px] rounded-b-[26px] px-2 pt-2">
            <div class="grid grid-cols-5 gap-1">
                <a href="{{ route('peminjam.dashboard') }}"
                    class="bottom-nav-link {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="bottom-label">Dashboard</span>
                </a>
                <a href="{{ route('peminjam.katalog') }}"
                    class="bottom-nav-link {{ request()->routeIs('peminjam.katalog') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span class="bottom-label">Katalog</span>
                </a>
                <a href="{{ route('peminjam.kembali') }}"
                    class="bottom-nav-link {{ request()->routeIs('peminjam.kembali') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt"></i>
                    <span class="bottom-label">Kembali</span>
                </a>
                <a href="{{ route('peminjam.riwayat') }}"
                    class="bottom-nav-link {{ request()->routeIs('peminjam.riwayat') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span class="bottom-label">Riwayat</span>
                </a>
                <a href="{{ route('peminjam.denda') }}"
                    class="bottom-nav-link {{ request()->routeIs('peminjam.denda*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="bottom-label">Denda</span>
                    @if ($jumlahDendaBelumLunas > 0)
                        <span class="bottom-nav-badge">{{ $jumlahDendaBelumLunas }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('extra-script')
</body>

</html>
