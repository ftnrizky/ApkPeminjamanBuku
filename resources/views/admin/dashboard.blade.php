<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportRent Admin | Peminjaman Alat Olahraga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-active {
            background: linear-gradient(to right, #10b981, #059669);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex h-screen overflow-hidden">

    <aside class="w-72 bg-[#062c21] text-white flex flex-col shadow-2xl">
        <div class="p-8 flex items-center gap-3">
            <div class="bg-emerald-500 p-2 rounded-xl rotate-3">
                <i class="fas fa-running text-white text-xl"></i>
            </div>
            <span class="text-xl font-extrabold tracking-tight italic">SPORT<span class="text-emerald-400">RENT</span></span>
        </div>

        <nav class="flex-1 px-6 space-y-2">
            <p class="text-[10px] font-bold text-emerald-500/50 uppercase tracking-[0.2em] mb-4">Main Menu</p>
            
            <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl sidebar-active text-white transition-all">
                <i class="fas fa-chart-pie w-5"></i> Dashboard
            </a>
            <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-emerald-100/70 hover:text-white">
                <i class="fas fa-user-friends w-5"></i> Member Atlet
            </a>
            <a href="/admin/alat" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-emerald-100/70 hover:text-white">
                <i class="fas fa-volleyball-ball w-5"></i> Katalog Alat
            </a>
            
            <div class="pt-6">
                <p class="text-[10px] font-bold text-emerald-500/50 uppercase tracking-[0.2em] mb-4">Transaksi</p>
                <a href="/admin/peminjaman" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-emerald-100/70 hover:text-white mb-2">
                    <i class="fas fa-calendar-plus w-5"></i> Sewa Alat
                </a>
                <a href="/admin/pengembalian" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-emerald-100/70 hover:text-white">
                    <i class="fas fa-file-import w-5"></i> Pengembalian
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-emerald-900/50">
            <form method="POST" action="/logout">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-xl transition-all font-bold text-sm shadow-lg shadow-orange-900/20">
                    <i class="fas fa-power-off"></i> LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-10">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Admin</h1>
                <p class="text-gray-500">Kelola peralatan olahraga dan aktivitas member hari ini.</p>
            </div>
            <div class="flex gap-4">
                <div class="bg-white px-4 py-2 rounded-full border border-gray-200 shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-gray-600 uppercase">System Online</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group">
                <div class="absolute right-0 top-0 h-full w-1 bg-blue-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Member</p>
                        <h3 class="text-4xl font-black mt-1 text-gray-900">10</h3>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl text-blue-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group">
                <div class="absolute right-0 top-0 h-full w-1 bg-emerald-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Alat</p>
                        <h3 class="text-4xl font-black mt-1 text-gray-900">20</h3>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-2xl text-emerald-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-basketball-ball fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group">
                <div class="absolute right-0 top-0 h-full w-1 bg-orange-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sewa Aktif</p>
                        <h3 class="text-4xl font-black mt-1 text-gray-900">5</h3>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-2xl text-orange-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-stopwatch fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group">
                <div class="absolute right-0 top-0 h-full w-1 bg-purple-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dikembalikan</p>
                        <h3 class="text-4xl font-black mt-1 text-gray-900">3</h3>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-2xl text-purple-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-history fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-3 gap-6">
            <div class="col-span-2 bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <i class="fas fa-list text-emerald-500"></i> Aktivitas Terbaru
                </h2>
                <div class="border-t border-gray-50 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center font-bold">R</div>
                        <div>
                            <p class="font-bold">Raket Badminton Yonex</p>
                            <p class="text-sm text-gray-500">Dipinjam oleh Rian - 2 jam yang lalu</p>
                        </div>
                    </div>
                    <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold uppercase">Dipinjam</span>
                </div>
                <div class="border-t border-gray-50 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center font-bold">A</div>
                        <div>
                            <p class="font-bold">Bola Basket Spalding</p>
                            <p class="text-sm text-gray-500">Dikembalikan oleh Andi - 5 jam yang lalu</p>
                        </div>
                    </div>
                    <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold uppercase">Kembali</span>
                </div>
            </div>
            
            <div class="bg-[#062c21] rounded-3xl p-8 text-white relative overflow-hidden">
                <i class="fas fa-dumbbell absolute -right-4 -bottom-4 text-white/10 text-9xl -rotate-12"></i>
                <h2 class="text-lg font-bold mb-2">Tips Perawatan</h2>
                <p class="text-emerald-200 text-sm leading-relaxed mb-6">Jangan lupa mengecek tekanan angin pada bola setelah dikembalikan!</p>
                <button class="bg-emerald-500 text-white px-5 py-2 rounded-xl text-sm font-bold">Lihat Panduan</button>
            </div>
        </div>
    </main>

</div>

</body>
</html>