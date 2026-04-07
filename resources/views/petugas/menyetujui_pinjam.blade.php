<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Pinjam | SportRent</title>
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
            <div class="bg-emerald-500 p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-running text-white text-xl"></i>
            </div>
            <span class="text-xl font-extrabold tracking-tight italic">SPORT<span class="text-emerald-400">RENT</span></span>
        </div>

        <nav class="flex-1 px-6 space-y-2">
            <p class="text-[10px] font-bold text-emerald-500/50 uppercase tracking-[0.2em] mb-4">Staff Menu</p>
            
            <a href="/petugas/dashboard" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::is('petugas/dashboard') ? 'sidebar-active text-white' : 'hover:bg-white/10 text-emerald-100/70 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5"></i> Dashboard
            </a>

            <div class="pt-6">
                <p class="text-[10px] font-bold text-emerald-500/50 uppercase tracking-[0.2em] mb-4">Transaksi & Laporan</p>
                
                <a href="/petugas/menyetujui_peminjaman" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all mb-2 {{ Request::is('petugas/menyetujui_peminjaman') ? 'sidebar-active text-white' : 'hover:bg-white/10 text-emerald-100/70 hover:text-white' }}">
                    <i class="fas fa-clipboard-check w-5"></i> Menyetujui Pinjam
                </a>
                
                <a href="/petugas/menyetujui_pengembalian" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all mb-2 {{ Request::is('petugas/menyetujui_pengembalian') ? 'sidebar-active text-white' : 'hover:bg-white/10 text-emerald-100/70 hover:text-white' }}">
                    <i class="fas fa-file-import w-5"></i> Menyetujui Kembali
                </a>

                <a href="/petugas/laporan" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::is('petugas/laporan') ? 'sidebar-active text-white' : 'hover:bg-white/10 text-emerald-100/70 hover:text-white' }}">
                    <i class="fas fa-print w-5"></i> Cetak Laporan
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-emerald-900/50 bg-emerald-950/20">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-xl transition-all font-bold text-sm shadow-lg shadow-orange-900/20">
                    <i class="fas fa-power-off"></i> LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-10">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Persetujuan Peminjaman</h1>
                <p class="text-gray-500 font-medium uppercase text-[10px] tracking-widest mt-1">Validasi permintaan alat dari atlet</p>
            </div>
            <div class="bg-emerald-50 px-6 py-3 rounded-2xl border border-emerald-100 shadow-sm">
                <span class="text-xs font-black text-emerald-700 uppercase tracking-widest">
                    {{ $peminjamans->count() }} Permintaan Menunggu
                </span>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg font-bold text-sm animate-bounce">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-tighter italic">Daftar Permintaan</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                            <th class="pb-5 px-4 text-center w-16">No</th>
                            <th class="pb-5">Data Atlet</th>
                            <th class="pb-5 text-center">Alat Olahraga</th>
                            <th class="pb-5 text-center">Durasi</th>
                            <th class="pb-5 text-center">Tgl Pengajuan</th>
                            <th class="pb-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($peminjamans as $index => $item)
                        <tr class="group hover:bg-gray-50/50 transition-all">
                            <td class="py-6 text-center font-bold text-gray-400 text-sm">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-6 px-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-[#062c21] rounded-xl flex items-center justify-center text-emerald-400 font-black shadow-lg">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900 uppercase italic">{{ $item->user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                            {{ $item->user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 text-center">
                                <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                                    {{ $item->alat->nama_alat }} <span class="text-emerald-600">({{ $item->jumlah }}x)</span>
                                </span>
                            </td>
                            <td class="py-6 text-center font-black text-gray-900 text-xs italic">
                                {{ \Carbon\Carbon::parse($item->tgl_pinjam)->diffInDays($item->tgl_kembali) }} Hari
                            </td>
                            <td class="py-6 text-center text-[10px] font-bold text-gray-400">
                                {{ $item->created_at->format('d M, H:i') }}
                            </td>
                            <td class="py-6">
                                <div class="flex items-center justify-center gap-3">
                                    {{-- TOMBOL SETUJU --}}
                                    <form action="{{ route('petugas.pinjam.proses', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition-all active:scale-90">
                                            Setuju
                                        </button>
                                    </form>

                                    {{-- TOMBOL TOLAK --}}
                                    <form action="{{ route('petugas.pinjam.proses', $item->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center text-gray-400 font-bold italic uppercase text-xs tracking-widest">
                                <i class="fas fa-inbox text-4xl mb-4 block opacity-20"></i>
                                Tidak ada permintaan peminjaman saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-tighter italic">
                <p>Data dikelola secara real-time</p>
                <div class="flex gap-4">
                    <span class="text-emerald-500 underline">Refresh Halaman</span>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>