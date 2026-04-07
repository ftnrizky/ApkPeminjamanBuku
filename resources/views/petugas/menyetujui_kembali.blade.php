<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Kembali | SportRent</title>
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
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-xl transition-all font-bold text-sm shadow-lg shadow-orange-900/20 active:scale-95">
                    <i class="fas fa-power-off"></i> LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-10">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Persetujuan Kembali</h1>
                <p class="text-gray-500 font-medium uppercase text-[10px] tracking-widest mt-1">Verifikasi kondisi alat & tutup transaksi</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-blue-50 px-6 py-3 rounded-2xl border border-blue-100 shadow-sm">
                    <span class="text-xs font-black text-blue-700 uppercase tracking-widest">8 Alat Perlu Dicek</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-8 px-2">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-tighter italic">Daftar Pengembalian</h2>
                <div class="relative">
                    <input type="text" placeholder="Cari ID Peminjaman..." class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-emerald-500/20 outline-none w-64">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                            <th class="pb-5 px-4">ID Transaksi</th>
                            <th class="pb-5">Atlet</th>
                            <th class="pb-5">Alat Olahraga</th>
                            <th class="pb-5 text-center">Status Fisik</th>
                            <th class="pb-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pengembalians as $data)
                            <tr class="group hover:bg-gray-50/50 transition-all text-sm">
                                <td class="py-6 px-4">
                                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md tracking-widest">
                                        #PJN-{{ $data->id }}
                                    </span>
                                </td>
                                <td class="py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-[#062c21] rounded-lg flex items-center justify-center text-emerald-400 font-black shadow-md text-xs italic text-sm">
                                            {{ strtoupper(substr($data->user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-black text-gray-900 uppercase italic tracking-tight">
                                            {{ $data->user->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-6 font-medium text-gray-600">
                                    <p class="font-bold">{{ $data->alat->nama_alat }}</p>
                                    <p class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">{{ $data->jumlah }} Unit</p>
                                </td>
                                <td class="py-6">
                                    <form action="{{ route('petugas.kembali.proses', $data->id) }}" method="POST" id="form-kembali-{{ $data->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex justify-center gap-2">
                                            <label class="cursor-pointer group/radio">
                                                <input type="radio" name="kondisi" value="normal" class="hidden peer" checked>
                                                <span class="text-[10px] font-black border border-gray-200 px-3 py-1.5 rounded-lg text-gray-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 transition-all uppercase tracking-tighter">Normal</span>
                                            </label>
                                            <label class="cursor-pointer group/radio">
                                                <input type="radio" name="kondisi" value="rusak" class="hidden peer">
                                                <span class="text-[10px] font-black border border-gray-200 px-3 py-1.5 rounded-lg text-gray-400 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all uppercase tracking-tighter">Rusak</span>
                                            </label>
                                        </div>
                                    </form>
                                </td>
                                <td class="py-6 text-center">
                                    <button type="submit" form="form-kembali-{{ $data->id }}" class="bg-[#062c21] hover:bg-emerald-800 text-white text-[10px] font-black px-5 py-2.5 rounded-xl transition-all shadow-lg uppercase tracking-widest active:scale-95">
                                        Konfirmasi Kembali
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-check-double text-emerald-500 text-xl"></i>
                                        </div>
                                        <p class="text-gray-400 font-black uppercase italic tracking-widest text-[10px]">Gudang Aman! Tidak ada alat yang perlu dicek.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between text-xs font-bold text-gray-400 uppercase italic">
                <p>Menunggu verifikasi fisik petugas lapangan</p>
                <div class="flex gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-500 transition-all"><i class="fas fa-chevron-left"></i></button>
                    <button class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-500 transition-all"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

    </main>

</div>

</body>
</html>