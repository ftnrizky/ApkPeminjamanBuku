<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pustaka - Sistem Informasi Peminjaman Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        html {
            scroll-padding-top: 96px;
        }

        body {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 30%, #f8fafc 100%);
            color: #1e293b;
        }

        .nav-link {
            position: relative;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 100%;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #2563eb;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
        }

        .btn-primary-ui {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.9rem;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            padding: 0.9rem 1.3rem;
            color: white;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 14px 30px -18px rgba(59, 130, 246, 0.7);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }

        .btn-primary-ui:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 18px 34px -18px rgba(59, 130, 246, 0.8);
            filter: brightness(1.03);
        }

        .btn-secondary-ui {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.9rem;
            border: 1px solid #dbe3ee;
            background: white;
            padding: 0.9rem 1.3rem;
            color: #334155;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, background 0.25s ease;
        }

        .btn-secondary-ui:hover {
            transform: translateY(-2px);
            border-color: #bfdbfe;
            background: #f8fbff;
            box-shadow: 0 14px 26px -22px rgba(15, 23, 42, 0.35);
        }

        .surface-card {
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px -34px rgba(15, 23, 42, 0.18);
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        }

        .surface-card:hover {
            transform: translateY(-4px);
            border-color: #bfdbfe;
            box-shadow: 0 28px 52px -34px rgba(15, 23, 42, 0.24);
        }

        .mobile-bottom-nav {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 -14px 34px -26px rgba(15, 23, 42, 0.26);
        }

        .mobile-bottom-link {
            position: relative;
            display: flex;
            min-height: 60px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.28rem;
            border-radius: 1rem;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.24s ease;
        }

        .mobile-bottom-link::before {
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

        .mobile-bottom-link i {
            font-size: 1rem;
            transition: transform 0.24s ease;
        }

        .mobile-bottom-link span {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .mobile-bottom-link:hover,
        .mobile-bottom-link.active {
            color: #3b82f6;
        }

        .mobile-bottom-link:hover::before,
        .mobile-bottom-link.active::before {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .mobile-bottom-link:hover i,
        .mobile-bottom-link.active i {
            transform: translateY(-1px) scale(1.08);
        }

        .mobile-bottom-link:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body id="top" class="min-h-screen antialiased">
    <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6">
            <a href="/" class="flex items-center gap-3 text-slate-900 no-underline">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-sm ring-1 ring-blue-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
                    <i class="fas fa-book-open text-lg"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight">E-<span class="text-blue-500">PUSTAKA</span></span>
                    <p class="text-[11px] font-medium uppercase tracking-[0.18em] text-slate-400">Digital Lending System</p>
                </div>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="#features" class="nav-link">Fitur</a>
                <a href="#how-it-works" class="nav-link">Cara Kerja</a>
                <a href="#stats" class="nav-link">Statistik</a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-secondary-ui">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary-ui">Daftar</a>
                    @endif
                @endif
            </div>

            <div class="flex items-center gap-2 md:hidden">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:bg-slate-50">
                        Login
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <main>
        <section class="relative overflow-hidden px-4 pb-20 pt-14 sm:px-6 sm:pt-20">
            <div class="absolute inset-x-0 top-0 -z-10 mx-auto h-72 max-w-5xl rounded-full bg-blue-100/50 blur-3xl"></div>
            <div class="absolute right-0 top-20 -z-10 h-48 w-48 rounded-full bg-emerald-100/40 blur-3xl"></div>

            <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="max-w-2xl">
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Platform Perpustakaan Modern
                    </div>

                    <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                        Kelola peminjaman buku dengan antarmuka yang <span class="text-blue-500">bersih, cepat, dan profesional</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-8 text-slate-500 sm:text-lg">
                        E-Pustaka membantu sekolah dan kampus mengelola koleksi buku, alur peminjaman, pengembalian, hingga laporan secara rapi dalam satu dashboard modern.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-primary-ui">
                                <i class="fas fa-arrow-right"></i>
                                <span>Mulai Sekarang</span>
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-secondary-ui">
                                <i class="fas fa-user-plus"></i>
                                <span>Buat Akun</span>
                            </a>
                        @endif
                    </div>

                    <div class="mt-10 grid gap-3 sm:grid-cols-3">
                        <div class="surface-card p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Realtime</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">Tracking stok & status buku</p>
                        </div>
                        <div class="surface-card p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Terintegrasi</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">Peminjaman, denda, laporan</p>
                        </div>
                        <div class="surface-card p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Responsif</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">Nyaman di desktop dan mobile</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="surface-card relative overflow-hidden p-5 sm:p-6">
                        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-r from-blue-50 via-white to-emerald-50"></div>

                        <div class="relative rounded-[28px] border border-slate-200 bg-slate-50 p-4 shadow-sm">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Overview</p>
                                    <h3 class="mt-2 text-xl font-bold text-slate-900">Dashboard Peminjaman</h3>
                                </div>
                                <div class="rounded-2xl bg-white px-3 py-2 text-xs font-semibold text-emerald-600 shadow-sm ring-1 ring-slate-200">
                                    Sistem Aktif
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-4">
                                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Koleksi</p>
                                    <p class="mt-3 text-3xl font-extrabold text-slate-900">500+</p>
                                    <p class="mt-2 text-sm text-slate-500">Buku siap dipinjam</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Transaksi</p>
                                    <p class="mt-3 text-3xl font-extrabold text-blue-500">5K+</p>
                                    <p class="mt-2 text-sm text-slate-500">Riwayat peminjaman</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Approval Peminjaman</p>
                                        <p class="mt-1 text-xs text-slate-500">Status verifikasi permintaan terbaru</p>
                                    </div>
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">Live</span>
                                </div>

                                <div class="mt-4 space-y-3">
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">Pemrograman Dasar</p>
                                                <p class="text-xs text-slate-500">Menunggu verifikasi petugas</p>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold text-amber-600">Pending</span>
                                    </div>

                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                                                <i class="fas fa-clipboard-check"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">Sastra Indonesia</p>
                                                <p class="text-xs text-slate-500">Disetujui dan siap diambil</p>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold text-emerald-600">Approved</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="px-4 py-20 sm:px-6">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500 shadow-sm">
                        Core Features
                    </div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Fitur yang dibuat untuk operasional harian yang lebih efisien</h2>
                    <p class="mt-4 text-base leading-8 text-slate-500">
                        Setiap modul dirancang agar mudah dipakai, cepat dipahami, dan membantu tim perpustakaan bekerja lebih rapi.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <div class="surface-card p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Manajemen Koleksi</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Pantau ketersediaan, stok, dan status buku secara real-time tanpa proses manual yang melelahkan.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Approval Terstruktur</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Permintaan peminjaman dapat diverifikasi dengan alur yang jelas untuk admin dan petugas.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-xl text-emerald-600">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Laporan Siap Unduh</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Buat laporan peminjaman dan pengembalian yang rapi untuk kebutuhan monitoring maupun audit.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Notifikasi Langsung</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Update status peminjaman, pengingat, dan info penting tampil cepat tanpa membuat pengguna bingung.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-xl text-rose-600">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Kontrol Akses</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Kelola akun aktif dan blacklist dengan aman untuk menjaga ketertiban penggunaan sistem.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-xl text-emerald-600">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Multi Role Access</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Hak akses admin, petugas, dan peminjam dipisahkan dengan jelas agar pengalaman lebih fokus dan aman.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="bg-slate-50 px-4 py-20 sm:px-6">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-blue-500 shadow-sm">
                        Workflow
                    </div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Alur kerja sederhana yang mudah diikuti semua pengguna</h2>
                    <p class="mt-4 text-base leading-8 text-slate-500">
                        Proses dibuat singkat agar pengguna bisa fokus meminjam, mengelola, dan mengembalikan buku dengan nyaman.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div class="surface-card p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white shadow-md">1</div>
                        <h3 class="mt-5 text-lg font-bold text-slate-900">Ajukan Peminjaman</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Peminjam memilih buku dan mengirim permintaan melalui dashboard dengan cepat.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white shadow-md">2</div>
                        <h3 class="mt-5 text-lg font-bold text-slate-900">Verifikasi Petugas</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Petugas meninjau permintaan dan memastikan proses berjalan sesuai aturan.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white shadow-md">3</div>
                        <h3 class="mt-5 text-lg font-bold text-slate-900">Pengambilan Buku</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Setelah disetujui, buku dapat diambil dan status peminjaman langsung tercatat.</p>
                    </div>

                    <div class="surface-card p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white shadow-md">4</div>
                        <h3 class="mt-5 text-lg font-bold text-slate-900">Pengembalian & Denda</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Kondisi pengembalian diverifikasi dan denda dihitung otomatis bila diperlukan.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="stats" class="px-4 py-20 sm:px-6">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500 shadow-sm">
                        Statistik
                    </div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Metrik yang membantu melihat pertumbuhan dan aktivitas</h2>
                    <p class="mt-4 text-base leading-8 text-slate-500">
                        Semua angka disajikan sederhana dan mudah dibaca agar keputusan operasional bisa diambil lebih cepat.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div class="surface-card p-6">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Total Pengguna</p>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900">{{ env('APP_NAME') ? '1000+' : '0' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Admin, petugas, dan peminjam aktif</p>
                    </div>
                    <div class="surface-card p-6">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Koleksi Buku</p>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight text-blue-500">{{ env('APP_NAME') ? '500+' : '0' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Judul buku siap dikelola</p>
                    </div>
                    <div class="surface-card p-6">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Transaksi</p>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900">{{ env('APP_NAME') ? '5000+' : '0' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Riwayat peminjaman tercatat</p>
                    </div>
                    <div class="surface-card p-6">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Kepuasan</p>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight text-emerald-500">{{ env('APP_NAME') ? '99%' : '0' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Pengalaman pengguna yang stabil</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4 pb-24 pt-6 sm:px-6">
            <div class="mx-auto max-w-4xl">
                <div class="surface-card overflow-hidden p-8 sm:p-10">
                    <div class="rounded-3xl bg-gradient-to-r from-blue-50 via-white to-emerald-50 px-6 py-8 text-center ring-1 ring-slate-200">
                        <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-blue-500">Get Started</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Siap memulai pengelolaan perpustakaan yang lebih modern?</h2>
                        <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-slate-500">
                            Buat akun atau masuk sekarang untuk mulai menggunakan E-Pustaka dengan pengalaman yang lebih bersih, cepat, dan nyaman.
                        </p>

                        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary-ui">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Daftar Gratis</span>
                                </a>
                            @endif
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="btn-secondary-ui">
                                    <i class="fas fa-right-to-bracket"></i>
                                    <span>Sudah Punya Akun?</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white px-4 py-10 sm:px-6">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-8 md:grid-cols-4">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-sm ring-1 ring-blue-100">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="text-lg font-extrabold tracking-tight text-slate-900">E-<span class="text-blue-500">PUSTAKA</span></span>
                    </div>
                    <p class="mt-4 text-sm leading-7 text-slate-500">
                        Sistem peminjaman buku modern untuk perpustakaan sekolah, kampus, dan institusi pendidikan.
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-900">Produk</h4>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500">
                        <li><a href="#features" class="transition hover:text-blue-500">Fitur</a></li>
                        <li><a href="#how-it-works" class="transition hover:text-blue-500">Cara Kerja</a></li>
                        <li><a href="#stats" class="transition hover:text-blue-500">Statistik</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-900">Perpustakaan</h4>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500">
                        <li><a href="#" class="transition hover:text-blue-500">Tentang Kami</a></li>
                        <li><a href="#" class="transition hover:text-blue-500">Kontak</a></li>
                        <li><a href="#" class="transition hover:text-blue-500">Dukungan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-900">Legal</h4>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500">
                        <li><a href="#" class="transition hover:text-blue-500">Privasi</a></li>
                        <li><a href="#" class="transition hover:text-blue-500">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="transition hover:text-blue-500">Lisensi</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 border-t border-slate-200 pt-6 text-center text-sm text-slate-500">
                &copy; 2026 E-Pustaka. Semua hak dilindungi.
            </div>
        </div>
    </footer>

    <div class="fixed inset-x-0 bottom-0 z-50 px-3 md:hidden" style="padding-bottom: calc(env(safe-area-inset-bottom) + 0.75rem);">
        <div class="mobile-bottom-nav mx-auto max-w-xl rounded-t-[28px] rounded-b-[26px] px-2 pt-2">
            <div class="grid grid-cols-5 gap-1">
                <a href="#top" class="mobile-bottom-link active">
                    <i class="fas fa-house"></i>
                    <span>Home</span>
                </a>
                <a href="#features" class="mobile-bottom-link">
                    <i class="fas fa-grid-2"></i>
                    <span>Fitur</span>
                </a>
                <a href="#how-it-works" class="mobile-bottom-link">
                    <i class="fas fa-shuffle"></i>
                    <span>Alur</span>
                </a>
                <a href="#stats" class="mobile-bottom-link">
                    <i class="fas fa-chart-column"></i>
                    <span>Stat</span>
                </a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="mobile-bottom-link">
                        <i class="fas fa-right-to-bracket"></i>
                        <span>Login</span>
                    </a>
                @else
                    <a href="#top" class="mobile-bottom-link">
                        <i class="fas fa-compass"></i>
                        <span>Menu</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        const mobileLinks = document.querySelectorAll('.mobile-bottom-link[href^="#"]');
        const sections = ['top', 'features', 'how-it-works', 'stats']
            .map(id => document.getElementById(id))
            .filter(Boolean);

        function setActiveBottomLink() {
            const scrollY = window.scrollY + 140;
            let activeId = 'top';

            sections.forEach(section => {
                if (scrollY >= section.offsetTop) {
                    activeId = section.id;
                }
            });

            mobileLinks.forEach(link => {
                const target = link.getAttribute('href').replace('#', '');
                link.classList.toggle('active', target === activeId);
            });
        }

        window.addEventListener('scroll', setActiveBottomLink, {
            passive: true
        });
        window.addEventListener('load', setActiveBottomLink);
    </script>
</body>

</html>
