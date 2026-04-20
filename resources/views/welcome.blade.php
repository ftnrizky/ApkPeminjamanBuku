<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Laptop - Sistem Informasi Peminjaman Laptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        html { scroll-padding-top: 80px; }
        
        body {
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f3f4f6 100%);
            color: #1f2937;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative elements */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Navbar */
        .navbar-fixed {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid #e5e7eb;
            backdrop-filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #1f2937;
        }

        .nav-logo-box {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 8px 10px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .nav-logo-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .nav-link {
            color: #6b7280;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.25s ease;
            position: relative;
            padding-bottom: 2px;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #3b82f6;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        .btn-outline {
            background: white;
            color: #3b82f6;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: 2px solid #3b82f6;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: #f0f9ff;
        }

        /* Glass Cards */
        .glass-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: #d1d5db;
        }

        .icon-wrap {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #f0f9ff 0%, #dbeafe 100%);
            border: 2px solid #bfdbfe;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #3b82f6;
            transition: all 0.3s ease;
        }

        .glass-card:hover .icon-wrap {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-color: #93c5fd;
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            position: relative;
            z-index: 1;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            color: #1f2937;
            margin-bottom: 24px;
        }

        .hero-content .highlight {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content p {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 32px;
            line-height: 1.6;
            max-width: 500px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 32px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
            font-weight: 500;
        }

        .feature-item i {
            color: #3b82f6;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
        }

        /* Section */
        .section {
            padding: 80px 0;
            position: relative;
            z-index: 1;
        }

        .section.alt-bg {
            background: #f9fafb;
        }

        .section h2 {
            font-size: 2.5rem;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .section .section-subtitle {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 48px;
            max-width: 500px;
        }

        /* Stats */
        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.12);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: #3b82f6;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #6b7280;
            font-weight: 500;
        }

        /* Footer */
        footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 40px 0;
            position: relative;
            z-index: 1;
        }

        footer a {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        footer a:hover {
            color: #2563eb;
        }

        /* Hamburger */
        .hamburger {
            cursor: pointer;
            width: 28px;
            height: 22px;
            position: relative;
            display: none;
        }

        .hamburger span {
            display: block;
            position: absolute;
            height: 2.5px;
            width: 100%;
            background: #1f2937;
            border-radius: 3px;
            left: 0;
            transform: rotate(0deg);
            transition: .25s ease-in-out;
        }

        .hamburger span:nth-child(1) { top: 0px; }
        .hamburger span:nth-child(2) { top: 9px; }
        .hamburger span:nth-child(3) { top: 18px; }

        .hamburger.active span:nth-child(1) { top: 9px; transform: rotate(135deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; left: -60px; }
        .hamburger.active span:nth-child(3) { top: 9px; transform: rotate(-135deg); }

        @media (max-width: 768px) {
            .hamburger { display: block; }
            #mobileMenu { display: none; }
            #mobileMenu.active { display: block; }
        }
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-fixed">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="nav-logo">
                <div class="nav-logo-box">
                    <i class="fas fa-laptop" style="font-size: 18px;"></i>
                </div>
                <span style="font-weight: 700; font-size: 18px;">E-<span style="color: #3b82f6;">LAPTOP</span></span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="nav-link">Fitur</a>
                <a href="#how-it-works" class="nav-link">Cara Kerja</a>
                <a href="#stats" class="nav-link">Statistik</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-outline">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                    @endif
                @endif
            </div>

            <!-- Mobile Hamburger -->
            <div class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-6 py-4 space-y-4">
                <a href="#features" class="block text-gray-700 hover:text-blue-600 font-medium">Fitur</a>
                <a href="#how-it-works" class="block text-gray-700 hover:text-blue-600 font-medium">Cara Kerja</a>
                <a href="#stats" class="block text-gray-700 hover:text-blue-600 font-medium">Statistik</a>
                <div class="flex gap-3 pt-4">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn-outline flex-1 text-center">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary flex-1 text-center">Daftar</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="hero-content">
                    <h1>
                        Sistem Peminjaman <span class="highlight">Laptop</span> Modern
                    </h1>
                    <p>
                        Platform mudah dan efisien untuk mengelola peminjaman laptop sekolah atau kampus. Kelola stok, tracking peminjaman, dan return laporan dalam satu sistem terintegrasi.
                    </p>
                    <div class="feature-list">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Manajemen stok laptop real-time</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Sistem approval peminjaman otomatis</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Tracking kondisi dan denda otomatis</span>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        @if (Route::has('login'))
                            <button onclick="window.location.href='{{ route('login') }}'" class="btn-primary">
                                <i class="fas fa-arrow-right"></i>
                                <span>Mulai Sekarang</span>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <div class="w-full max-w-md rounded-3xl p-12 flex items-center justify-center" style="aspect-ratio: 1;">
                        <img src="{{ asset('image/dashboard.png') }}" alt="Hero Image" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section alt-bg">
        <div class="max-w-7xl mx-auto px-6">
            <h2>Fitur Unggulan</h2>
            <p class="section-subtitle">Semua yang Anda butuhkan untuk mengelola peminjaman laptop dengan efisien</p>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card">
                    <div class="icon-wrap mb-4">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Manajemen Stok</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Kelola stok laptop dengan mudah. Pantau jumlah tersedia, rusak, dan dalam peminjaman secara real-time.
                    </p>
                </div>

                <div class="glass-card">
                    <div class="icon-wrap mb-4">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Approval Sistem</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Sistem approval bertingkat untuk memastikan semua peminjaman sesuai dengan kebijakan yang berlaku.
                    </p>
                </div>

                <div class="glass-card">
                    <div class="icon-wrap mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Laporan Detail</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Buat laporan peminjaman, pengembalian, dan kondisi unit dalam format PDF yang rapi dan profesional.
                    </p>
                </div>

                <div class="glass-card">
                    <div class="icon-wrap mb-4">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Notifikasi Real-Time</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Dapatkan pemberitahuan real-time untuk setiap perubahan status peminjaman dan pengingat pengembalian.
                    </p>
                </div>

                <div class="glass-card">
                    <div class="icon-wrap mb-4">
                        <i class="fas fa-ban"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Sistem Blacklist</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Kontrol akses dengan sistem blacklist untuk pengguna yang memiliki riwayat buruk atau tunggakan.
                    </p>
                </div>

                <div class="glass-card">
                    <div class="icon-wrap mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Multi-Role Access</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Akses multi-role untuk admin, petugas, dan peminjam dengan permission yang dapat disesuaikan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section">
        <div class="max-w-7xl mx-auto px-6">
            <h2>Cara Kerja</h2>
            <p class="section-subtitle">Proses peminjaman yang mudah dan cepat dalam beberapa langkah sederhana</p>

            <div class="grid md:grid-cols-4 gap-6">
                <div class="glass-card text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-lg font-bold">1</div>
                    <h3 class="font-bold mb-2 text-gray-900">Ajukan Permintaan</h3>
                    <p class="text-sm text-gray-600">Peminjam mengajukan permintaan peminjaman laptop melalui sistem</p>
                </div>

                <div class="glass-card text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-lg font-bold">2</div>
                    <h3 class="font-bold mb-2 text-gray-900">Proses Approval</h3>
                    <p class="text-sm text-gray-600">Petugas meninjau dan menyetujui atau menolak permintaan</p>
                </div>

                <div class="glass-card text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-lg font-bold">3</div>
                    <h3 class="font-bold mb-2 text-gray-900">Ambil Laptop</h3>
                    <p class="text-sm text-gray-600">Peminjam mengambil laptop yang sudah disetujui di lokasi yang ditentukan</p>
                </div>

                <div class="glass-card text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-lg font-bold">4</div>
                    <h3 class="font-bold mb-2 text-gray-900">Kembalikan & Verifikasi</h3>
                    <p class="text-sm text-gray-600">Kembalikan laptop dan petugas verifikasi kondisi serta jumlah yang dikembalikan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="section alt-bg">
        <div class="max-w-7xl mx-auto px-6">
            <h2>Statistik Sistem</h2>
            <p class="section-subtitle">Lihat data real-time tentang penggunaan sistem</p>

            <div class="grid md:grid-cols-4 gap-6">
                <div class="stat-card">
                    <div class="stat-number">{{ env('APP_NAME') ? '1000+' : '0' }}</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ env('APP_NAME') ? '500+' : '0' }}</div>
                    <div class="stat-label">Laptop Tersedia</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ env('APP_NAME') ? '5000+' : '0' }}</div>
                    <div class="stat-label">Transaksi Peminjaman</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ env('APP_NAME') ? '99%' : '0' }}</div>
                    <div class="stat-label">Tingkat Kepuasan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2>Siap Memulai?</h2>
            <p class="section-subtitle">Bergabunglah dengan ribuan pengguna yang telah merasakan kemudahan E-Laptop</p>
            <div class="flex gap-4 justify-center">
                @if (Route::has('register'))
                    <button onclick="window.location.href='{{ route('register') }}'" class="btn-primary">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Gratis</span>
                    </button>
                @endif
                @if (Route::has('login'))
                    <button onclick="window.location.href='{{ route('login') }}'" class="btn-outline">
                        Sudah Punya Akun? Login
                    </button>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-gray-200">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 8px 10px; border-radius: 8px; color: white; font-weight: bold;">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <span style="font-weight: 700;">E-<span style="color: #3b82f6;">LAPTOP</span></span>
                    </div>
                    <p class="text-gray-600 text-sm">Sistem manajemen peminjaman laptop modern dan efisien</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#how-it-works">Cara Kerja</a></li>
                        <li><a href="#stats">Statistik</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#">Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Lisensi</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center py-8">
                <p class="text-gray-600 text-sm">&copy; 2026 E-Laptop. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburger = document.getElementById('hamburger');
            mobileMenu.classList.toggle('hidden');
            hamburger.classList.toggle('active');
        }

        // Close menu when link is clicked
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
                document.getElementById('hamburger').classList.remove('active');
            });
        });
    </script>
</body>
</html>
