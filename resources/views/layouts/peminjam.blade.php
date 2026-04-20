<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | E-Laptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #f8fafc 50%, #f5f7fa 100%);
        }
        
        /* Navbar Premium Styling */
        nav {
            background: linear-gradient(135deg, #0f172a 0%, #1a2f4a 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(6, 182, 212, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            position: relative;
            
        }
        
        nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(34, 211, 238, 0.08) 0%, transpaE-Laptop 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.06) 0%, transpaE-Laptop 50%);
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
        
        /* Nav links */
        .nav-link {
            position: relative;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: rgba(203, 213, 225, 0.7);
            padding-bottom: 0.5rem;
            border-b-2 border-transpaE-Laptop;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #06b6d4, #22d3ee);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover {
            color: #22d3ee;
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        .nav-link.active {
            color: #22d3ee;
            border-b-2 border-cyan-400;
            text-shadow: 0 0 12px rgba(34, 211, 238, 0.3);
        }
        
        /* Mobile menu */
        #mobileMenu {
            transition: all 0.3s ease-in-out;
            background: linear-gradient(135deg, rgba(10, 25, 41, 0.95) 0%, rgba(26, 47, 74, 0.95) 100%);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(6, 182, 212, 0.2);
        }
        
        .mobile-link {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            color: rgba(203, 213, 225, 0.7);
            padding: 0.5rem 0;
            border-left: 3px solid transpaE-Laptop;
            padding-left: 0.75rem;
        }
        
        .mobile-link:hover {
            color: #22d3ee;
            border-left-color: #22d3ee;
            padding-left: 1rem;
        }
        
        .mobile-link.active {
            color: #22d3ee;
            border-left-color: #22d3ee;
        }
        
        /* User info section */
        .user-info {
            font-size: 9px;
            font-weight: 700;
            color: rgba(34, 211, 238, 0.8);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        
        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
        }
        
        /* Icon button styling */
        .icon-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
            border: 1px solid rgba(6, 182, 212, 0.2);
        }
        
        .icon-btn:hover {
            background: rgba(6, 182, 212, 0.2);
            color: #22d3ee;
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
            border-color: rgba(34, 211, 238, 0.3);
        }
        
        .logout-btn {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);
            color: #06b6d4;
            border: 1px solid rgba(6, 182, 212, 0.2);
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            color: white;
            box-shadow: 0 8px 16px rgba(6, 182, 212, 0.3);
            transform: translateY(-2px);
        }
        
        /* Card shadow for consistency */
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        
        /* Navigation wrapper for z-index */
        nav > * {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <nav class="sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="logo-box p-2.5 rounded-[10px]">
                    <i class="fas fa-laptop text-white text-lg"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-white">E-<span class="text-cyan-300">LAPTOP</span></span>
                    <p class="text-[9px] text-cyan-200/60 font-semibold">Borrower Portal</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('peminjam.dashboard') }}" class="nav-link {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('peminjam.katalog') }}" class="nav-link {{ request()->routeIs('peminjam.katalog') ? 'active' : '' }}">
                    Katalog
                </a>
                <a href="{{ route('peminjam.kembali') }}" class="nav-link {{ request()->routeIs('peminjam.kembali') ? 'active' : '' }}">
                    Pengembalian
                </a>
                <a href="{{ route('peminjam.riwayat') }}" class="nav-link {{ request()->routeIs('peminjam.riwayat') ? 'active' : '' }}">
                    Riwayat
                </a>
            </div>

            <!-- Right Section -->
            <div class="flex items-center gap-4">
                <!-- User Info (Desktop) -->
                <div class="hidden sm:block text-right">
                    <p class="user-info">Peminjam</p>
                    <p class="user-name">{{ Auth::user()->name }}</p>
                </div>

                <!-- Notification Dropdown -->
                <div class="hidden sm:block">
                    @include('partials.notification_dropdown')
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMenu()" class="md:hidden icon-btn">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Logout Button (Desktop) -->
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit" class="icon-btn logout-btn">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden">
            <div class="px-6 py-6 flex flex-col gap-4 max-w-7xl mx-auto">
                <a href="{{ route('peminjam.dashboard') }}" class="mobile-link {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line mr-2"></i>Dashboard
                </a>
                <a href="{{ route('peminjam.katalog') }}" class="mobile-link {{ request()->routeIs('peminjam.katalog') ? 'active' : '' }}">
                    <i class="fas fa-book mr-2"></i>Katalog
                </a>
                <a href="{{ route('peminjam.kembali') }}" class="mobile-link {{ request()->routeIs('peminjam.kembali') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt mr-2"></i>Pengembalian
                </a>
                <a href="{{ route('peminjam.riwayat') }}" class="mobile-link {{ request()->routeIs('peminjam.riwayat') ? 'active' : '' }}">
                    <i class="fas fa-history mr-2"></i>Riwayat
                </a>
                <hr class="border-white/5 my-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-link" style="color: #f87171;">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-10">
        @yield('content')
    </main>

    <script>
        function toggleMenu() {
            const m = document.getElementById('mobileMenu');
            m.classList.toggle('hidden');
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('extra-script')
</body>
</html>