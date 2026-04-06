<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Alat | SportRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
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
            <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-emerald-100/70 hover:text-white">
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

                <a href="/admin/pengembalian" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600 shadow-lg shadow-emerald-900/20 text-white transition-all">
                    <i class="fas fa-file-import w-5"></i> Pengembalian
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-emerald-900/50">
            <form method="POST" action="/logout">
                @csrf
                <button class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-xl transition-all font-bold text-sm shadow-lg shadow-orange-900/20">
                    <i class="fas fa-power-off"></i> LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-10 no-scrollbar">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase italic">Riwayat <span class="text-emerald-600">Pengembalian</span></h1>
                <p class="text-gray-500 font-medium">Laporan alat yang telah selesai disewa dan dikembalikan.</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-white border border-gray-200 text-gray-600 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fas fa-file-excel text-emerald-600"></i> Export Excel
                </button>
            </div>
        </div>

        <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari riwayat berdasarkan nama atau kode..." class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-sm font-semibold">
            </div>
            <select class="bg-gray-50 border border-gray-200 px-6 py-3 rounded-2xl text-sm font-bold text-gray-600 outline-none focus:ring-4 focus:ring-emerald-500/10">
                <option>Semua Kondisi</option>
                <option>Baik</option>
                <option>Rusak</option>
            </select>
        </div>

        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-20">NO</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Alat & Atlet</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tgl Kembali</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kondisi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Denda</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($riwayat as $index => $data)
                        <tr class="hover:bg-emerald-50/30 transition-colors group">
                            <td class="px-6 py-4 text-center font-bold text-gray-400 italic">
                                #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-history text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 leading-none mb-1">{{ $data->alat->nama_alat }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $data->user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-600">
                                <div class="flex flex-col">
                                    <span class="font-bold">{{ $data->updated_at->translatedFormat('d M Y') }}</span>
                                    <span class="text-[9px] text-emerald-500 font-black">{{ $data->updated_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($data->kondisi == 'normal')
                                    <span class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center w-fit gap-1">
                                        <i class="fas fa-check-circle"></i> Normal
                                    </span>
                                @elseif($data->kondisi == 'rusak')
                                    <span class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 text-[9px] font-black uppercase tracking-widest border border-rose-100 flex items-center w-fit gap-1">
                                        <i class="fas fa-exclamation-triangle"></i> Rusak
                                    </span>
                                @else
                                    <span class="text-gray-300 italic text-[10px] font-bold uppercase tracking-widest">Tidak Dicatat</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-gray-400 text-center">
                                {{-- Logika Denda (Opsional) --}}
                                @if($data->kondisi == 'rusak')
                                    <span class="text-rose-600 font-black text-[10px]">Cek Kerusakan!</span>
                                @else
                                    <span class="text-gray-200">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.peminjaman.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus permanen riwayat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-300 hover:text-rose-500 transition-all hover:scale-110">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-folder-open text-gray-200 text-xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-bold uppercase italic tracking-widest text-[10px]">Belum ada riwayat pengembalian alat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-6 bg-gray-50/50 flex items-center justify-between border-t border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Showing 2 of 24 records</p>
                <div class="flex gap-2">
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-white transition-all"><i class="fas fa-chevron-left text-xs"></i></button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#062c21] text-white shadow-lg shadow-emerald-900/20 font-bold text-xs">1</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-white transition-all"><i class="fas fa-chevron-right text-xs"></i></button>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>