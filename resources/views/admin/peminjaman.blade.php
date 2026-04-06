<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat | SportRent</title>
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
                <a href="/admin/peminjaman" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600 shadow-lg shadow-emerald-900/20 text-white transition-all mb-2">
                    <i class="fas fa-calendar-plus w-5"></i> Sewa Alat
                </a>
                <a href="/admin/pengembalian" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-emerald-100/70 hover:text-white">
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
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase italic">Transaksi <span class="text-emerald-600">Sewa Alat</span></h1>
                <p class="text-gray-500 font-medium">Kelola peminjaman alat yang sedang berlangsung.</p>
            </div>
            <button onclick="toggleModal('modal-tambah-pinjam')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> Input Peminjaman Baru
            </button>
        </div>

        <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari berdasarkan nama atlet atau alat..." class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-sm font-semibold">
            </div>
            <select class="bg-gray-50 border border-gray-200 px-6 py-3 rounded-2xl text-sm font-bold text-gray-600 outline-none focus:ring-4 focus:ring-emerald-500/10">
                <option>Semua Status</option>
                <option>Sedang Dipinjam</option>
                <option>Terlambat</option>
            </select>
        </div>

        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-20">NO</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Atlet / Peminjam</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Alat & Jumlah</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Batas Kembali</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($peminjamanBerlangsung as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-6 py-4 text-center font-bold text-gray-400 group-hover:text-emerald-600 transition-colors italic">
                            #{{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->user->name) }}&background=062c21&color=fff" class="w-10 h-10 rounded-xl object-cover" alt="Avatar">
                                <div>
                                    <p class="font-bold text-gray-900 leading-none mb-1">{{ $item->user->name }}</p>
                                    <p class="text-[10px] text-emerald-600 font-black tracking-widest uppercase italic">Member Atlet</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-black text-gray-700 uppercase italic">{{ $item->alat->nama_alat }}</p>
                            <p class="text-xs text-gray-400 font-bold">Qty: {{ $item->jumlah }} Unit</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-[10px] font-black bg-orange-50 text-orange-700 border border-orange-100 uppercase tracking-tighter">
                                <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openReturnModal('{{ $item->id }}', '{{ $item->user->name }}', '{{ $item->alat->nama_alat }}')" 
                                    class="bg-[#062c21] text-white text-[10px] font-black px-5 py-2.5 rounded-xl hover:bg-emerald-600 transition-all uppercase tracking-widest shadow-lg shadow-gray-200 italic">
                                Selesai
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</div>

<div id="modal-tambah-pinjam" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-[#062c21]/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-tambah-pinjam')"></div>
    
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-10 relative z-10 shadow-2xl">
        <div class="flex justify-between items-start mb-8">
            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">
                Input <span class="text-emerald-500 underline decoration-emerald-100">Pinjaman</span>
            </h2>
            <button onclick="toggleModal('modal-tambah-pinjam')" class="text-slate-300 hover:text-rose-500 transition-colors">
                <i class="fas fa-times-circle text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Member</label>
                <select name="user_id" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-semibold text-sm">
                    <option value="">-- Cari Nama Atlet --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Alat</label>
                <select name="alat_id" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-semibold text-sm">
                    <option value="">-- Nama Alat Olahraga --</option>
                    @foreach($alats as $alat)
                        <option value="{{ $alat->id }}">{{ $alat->nama_alat }} (Stok: {{ $alat->stok_tersedia }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Jumlah</label>
                    <input type="number" name="jumlah" value="1" min="1" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl font-semibold text-sm">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Durasi (Hari)</label>
                    <input type="number" name="durasi" required min="1" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl font-semibold text-sm">
                </div>
            </div>
            
            <div class="flex gap-3 pt-6">
                <button type="button" onclick="toggleModal('modal-tambah-pinjam')" class="flex-1 px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Batal</button>
                <button type="submit" class="flex-[2] bg-[#062c21] text-white px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-900/20 hover:bg-emerald-800 transition-all italic">Proses Sekarang</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-kembali" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-kembali')"></div>
    <div class="bg-white rounded-[2.5rem] w-full max-w-sm p-10 relative z-10 shadow-2xl text-center">
        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-file-import fa-2x"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-2">Konfirmasi</h2>
        <p class="text-sm text-gray-500 font-medium mb-8 italic">Alat yang dikembalikan <span id="m-member" class="text-emerald-600 font-bold"></span> sudah dicek kondisinya?</p>
        
        <form id="form-kembali" method="POST" action="">
            @csrf @method('PATCH')
            <div class="flex flex-col gap-2">
                <button type="submit" class="w-full bg-emerald-600 text-white px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-900/20 hover:bg-emerald-700 transition-all italic">Ya, Sudah Kembali</button>
                <button type="button" onclick="toggleModal('modal-kembali')" class="w-full px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Nanti Dulu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }

    function openReturnModal(id, member, alat) {
        document.getElementById('form-kembali').action = `/admin/peminjaman/kembalikan/${id}`;
        document.getElementById('m-member').innerText = member;
        toggleModal('modal-kembali');
    }
</script>

</body>
</html>