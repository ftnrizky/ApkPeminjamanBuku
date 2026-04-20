<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Laptop Admin | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #f8fafc 50%, #f5f7fa 100%);
        }
        
        /* Sidebar Premium Styling */
        aside {
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(135deg, #0f172a 0%, #1a2f4a 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Subtle grid pattern background */
        aside::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(34, 211, 238, 0.08) 0%, transpaE-Laptop 50%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.06) 0%, transpaE-Laptop 50%);
            pointer-events: none;
        }
        
        /* Logo box styling */
        .logo-box {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
            transition: all 0.3s ease;
        }
        
        .logo-box:hover {
            transform: scale(1.08) rotate(-2deg);
            box-shadow: 0 12px 32px rgba(6, 182, 212, 0.4);
        }
        
        /* Navigation items */
        .nav-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0.25rem 0;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transpaE-Laptop, rgba(34, 211, 238, 0.2), transpaE-Laptop);
            transition: left 0.5s ease;
        }
        
        .nav-item i {
            width: 20px;
            text-align: center;
            color: #06b6d4;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .nav-item:hover {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .nav-item:hover i {
            color: #22d3ee;
            transform: scale(1.15);
        }
        
        /* Active nav item */
        .sidebar-active {
            background: linear-gradient(135deg, rgba(34, 211, 238, 0.25) 0%, rgba(6, 182, 212, 0.2) 100%);
            color: #ffffff;
            border-left: 3px solid #22d3ee;
            box-shadow: inset 0 0 12px rgba(6, 182, 212, 0.2), 0 4px 12px rgba(6, 182, 212, 0.25);
        }
        
        .sidebar-active i {
            color: #22d3ee;
        }
        
        .sidebar-active:hover {
            box-shadow: inset 0 0 16px rgba(6, 182, 212, 0.3), 0 6px 16px rgba(6, 182, 212, 0.3);
        }
        
        /* Section label */
        .section-label {
            color: rgba(34, 211, 238, 0.6);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            padding-left: 1rem;
        }
        
        /* Logout button */
        .logout-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            color: white;
            border: 1px solid rgba(34, 211, 238, 0.3);
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #0e7490 0%, #0891b2 100%);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
            transform: translateY(-2px);
            border-color: rgba(34, 211, 238, 0.5);
        }
        
        .logout-btn:active {
            transform: translateY(0);
        }
        
        /* Header styling */
        header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(6, 182, 212, 0.1);
        }
        
        .mobile-logo-box {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 text-white flex flex-col shadow-2xl z-50 -translate-x-full lg:translate-x-0 lg:relative lg:flex">
        <!-- Logo Section -->
        <div class="p-6 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-3">
                <div class="logo-box p-2.5 rounded-[10px]">
                    <i class="fas fa-laptop text-white text-lg"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight">E-<span class="text-cyan-300">LAPTOP</span></span>
                    <p class="text-[9px] text-cyan-200/60 font-semibold">Admin Console</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-cyan-300 transition-colors p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 overflow-y-auto relative z-10">
            <p class="section-label">Dashboard</p>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <p class="section-label">Management</p>
            
            <a href="{{ route('admin.kelola_user') }}" class="nav-item {{ request()->routeIs('admin.kelola_user') ? 'sidebar-active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Kelola User</span>
            </a>

            <a href="{{ route('admin.kategori') }}" class="nav-item {{ request()->routeIs('admin.kategori') ? 'sidebar-active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Kategori Alat</span>
            </a>
            
            <a href="{{ route('admin.alat') }}" class="nav-item {{ request()->routeIs('admin.alat') ? 'sidebar-active' : '' }}">
                <i class="fas fa-laptop"></i>
                <span>Kelola Alat</span>
            </a>

            <p class="section-label">Transactions</p>
            
            <a href="{{ route('admin.peminjaman') }}" class="nav-item {{ request()->routeIs('admin.peminjaman') ? 'sidebar-active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Peminjaman</span>
            </a>
            
            <a href="{{ route('admin.pengembalian') }}" class="nav-item {{ request()->routeIs('admin.pengembalian') ? 'sidebar-active' : '' }}">
                <i class="fas fa-undo-alt"></i>
                <span>Pengembalian</span>
            </a>

            <p class="section-label">Monitoring</p>
            
            <a href="{{ route('admin.overdue_list') }}" class="nav-item {{ request()->routeIs('admin.overdue_list') ? 'sidebar-active' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span>Laptop Belum Kembali</span>
            </a>

            <a href="{{ route('admin.activity_log') }}" class="nav-item {{ request()->routeIs('admin.activity_log') ? 'sidebar-active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Activity Log</span>
            </a>
        </nav>

        <!-- Logout Section -->
        <div class="p-4 border-t border-cyan-900/30 relative z-10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <!-- Mobile Header -->
        <header class="lg:hidden p-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-2">
                <div class="mobile-logo-box p-2 rounded-lg">
                    <i class="fas fa-laptop text-white text-sm"></i>
                </div>
                <span class="font-bold text-slate-900">E-<span class="text-cyan-600">LAPTOP</span></span>
            </div>
            <button onclick="toggleSidebar()" class="text-slate-600 hover:text-cyan-600 transition-colors p-2">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </header>

        <div class="px-6 py-5 border-b border-slate-200 bg-white/80 sticky top-0 z-20">
            <div class="flex justify-end">
                @include('partials.notification_dropdown')
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6 lg:p-10">
            @yield('content')
        </div>
    </main>
</div>

<script>
    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        const o = document.getElementById('sidebarOverlay');
        if (s.classList.contains('-translate-x-full')) {
            s.classList.remove('-translate-x-full');
            o.classList.remove('hidden');
        } else {
            s.classList.add('-translate-x-full');
            o.classList.add('hidden');
        }
    }
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')
</body>
</html>