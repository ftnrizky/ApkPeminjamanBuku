<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Laptop Petugas | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .sidebar-active {
            background: linear-gradient(to right, #06b6d4, #0891b2);
            box-shadow: 0 4px 16px rgba(6, 182, 212, 0.25);
        }
        aside { 
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #0f172a 0%, #1a2f4a 100%);
        }
        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-link:hover {
            background: rgba(6, 182, 212, 0.1);
            transform: translateX(4px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden relative">
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 text-white flex flex-col z-50 -translate-x-full lg:translate-x-0 lg:relative lg:flex shadow-2xl">
        <div class="p-8 flex items-center justify-between lg:justify-start gap-3 border-b border-cyan-400/20">
            <div class="flex items-center gap-3">
                <div class="bg-gradient-to-br from-cyan-400 to-teal-500 p-2.5 rounded-lg shadow-lg shadow-cyan-500/30">
                    <i class="fas fa-laptop text-white text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold tracking-tight">E-Laptop</span>
                    <span class="text-[10px] font-medium text-cyan-300">Staff Panel</span>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-white/60 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <nav class="flex-1 px-5 py-6 space-y-1 overflow-y-auto">
            <p class="text-[10px] font-bold text-cyan-300/60 uppercase tracking-wider mb-4 pl-1">Menu Utama</p>
            <a href="{{ route('petugas.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('petugas.dashboard') ? 'sidebar-active text-white' : 'text-slate-300 hover:text-white' }}">
                <i class="fas fa-chart-line w-5 text-center"></i> <span class="font-medium">Dashboard</span>
            </a>
            
            <div class="pt-4">
                <p class="text-[10px] font-bold text-cyan-300/60 uppercase tracking-wider mb-4 pl-1">Transaksi & Laporan</p>
                <a href="{{ route('petugas.menyetujui_peminjaman') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('petugas.menyetujui_peminjaman') ? 'sidebar-active text-white' : 'text-slate-300 hover:text-white' }}">
                    <i class="fas fa-check-square w-5 text-center"></i> <span class="font-medium">Setujui Pinjam</span>
                </a>
                <a href="{{ route('petugas.menyetujui_kembali') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('petugas.menyetujui_kembali') ? 'sidebar-active text-white' : 'text-slate-300 hover:text-white' }}">
                    <i class="fas fa-undo-alt w-5 text-center"></i> <span class="font-medium">Verifikasi Kembali</span>
                </a>
                <a href="{{ route('petugas.laporan') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('petugas.laporan') ? 'sidebar-active text-white' : 'text-slate-300 hover:text-white' }}">
                    <i class="fas fa-file-pdf w-5 text-center"></i> <span class="font-medium">Cetak Laporan</span>
                </a>
                <a href="{{ route('petugas.overdue_list') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('petugas.overdue_list') ? 'sidebar-active text-white' : 'text-slate-300 hover:text-white' }}">
                    <i class="fas fa-exclamation-circle w-5 text-center"></i> <span class="font-medium">Belum Dikembalikan</span>
                </a>
            </div>
        </nav>

        <div class="p-5 border-t border-cyan-400/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white px-4 py-3 rounded-lg transition-all font-bold text-sm shadow-lg hover:shadow-xl active:scale-95">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto">
        <header class="lg:hidden bg-white border-b border-slate-200 p-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-1.5 rounded-lg shadow-md">
                    <i class="fas fa-laptop text-white text-sm"></i>
                </div>
                <span class="font-bold tracking-tight text-sm text-slate-900">E-Laptop <span class="text-cyan-600">Staff</span></span>
            </div>
            <button onclick="toggleSidebar()" class="text-slate-600 hover:text-slate-900 p-2 transition-colors">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </header>
        <div class="p-6 lg:p-10">
            <div class="flex justify-end mb-6">
                @include('partials.notification_dropdown')
            </div>
            @yield('content')
        </div>
    </main>
</div>

<script>
    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        const o = document.getElementById('overlay');
        if (s.classList.contains('-translate-x-full')) {
            s.classList.remove('-translate-x-full');
            o.classList.remove('hidden');
        } else {
            s.classList.add('-translate-x-full');
            o.classList.add('hidden');
        }
    }
</script>
@stack('scripts')
</body>
</html>