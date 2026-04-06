<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat | SportRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .card-shadow { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-[#062c21] text-white sticky top-0 z-50 shadow-xl">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-500 p-2 rounded-lg rotate-3 shadow-lg">
                    <i class="fas fa-running text-white"></i>
                </div>
                <span class="text-xl font-black italic tracking-tighter uppercase">SPORT<span class="text-emerald-400">RENT</span></span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="/peminjam/dashboard" class="text-xs font-black uppercase tracking-widest text-emerald-400 border-b-2 border-emerald-400 pb-1">Katalog</a>
                <a href="/peminjam/pengembalian" class="text-xs font-black uppercase tracking-widest text-emerald-100/50 hover:text-white transition-all">Riwayat</a>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-black text-emerald-400 uppercase leading-none">Atlet</p>
                    <p class="text-xs font-bold text-white uppercase tracking-tight">{{ Auth::user()->name }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center font-black shadow-lg border-2 border-emerald-400/20 uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="w-10 h-10 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl transition-all flex items-center justify-center">
                        <i class="fas fa-power-off text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic uppercase">Mau Latihan <span class="text-emerald-600">Apa Hari Ini?</span></h1>
                <p class="text-gray-500 text-sm font-medium">Pilih alat olahraga kualitas terbaik untuk performamu.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="/peminjam/dashboard" method="GET" class="relative group">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari raket, bola..." 
                        class="w-64 pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-2xl text-xs focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all card-shadow font-semibold">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-3 mb-8 overflow-x-auto pb-2 no-scrollbar">
            @php 
                $categories = ['Semua' => 'fa-th-large', 'Bola' => 'fa-volleyball-ball', 'Raket' => 'fa-table-tennis', 'Fitness' => 'fa-dumbbell'];
            @endphp
            @foreach($categories as $cat => $icon)
                <a href="/peminjam/dashboard?kategori={{ $cat }}&search={{ request('search') }}" 
                   class="px-6 py-2.5 rounded-full text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2
                   {{ (request('kategori', 'Semua') == $cat) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    <i class="fas {{ $icon }}"></i> {{ $cat }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($alats as $alat)
            <div class="group bg-white rounded-[2.5rem] p-5 border border-gray-100 card-shadow hover:border-emerald-300 transition-all duration-300">
                <div class="relative h-64 w-full bg-gray-50 rounded-[2rem] overflow-hidden mb-5">
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-tighter shadow-sm z-10
                        {{ $alat->kondisi == 'baik' ? 'text-emerald-600' : ($alat->kondisi == 'lecet' ? 'text-amber-500' : 'text-rose-600') }}">
                        Kondisi: {{ $alat->kondisi }}
                    </span>

                    <div class="w-full h-full flex items-center justify-center">
                        @if($alat->foto)
                            <img src="{{ asset('storage/' . $alat->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <i class="fas fa-running fa-4x text-gray-200 group-hover:text-emerald-200 transition-colors"></i>
                        @endif
                    </div>

                    <span class="absolute bottom-4 right-4 {{ $alat->stok_tersedia > 0 ? 'bg-emerald-500' : 'bg-gray-800' }} text-white text-[9px] font-black px-3 py-1 rounded-lg shadow-lg">
                        {{ $alat->stok_tersedia > 0 ? 'TERSEDIA' : 'KOSONG' }}
                    </span>
                </div>

                <div class="px-2 pb-2">
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">{{ $alat->kategori }}</p>
                    <h3 class="text-xl font-extrabold text-gray-900 leading-tight mb-2 truncate uppercase italic tracking-tighter">{{ $alat->nama_alat }}</h3>
                    
                    <p class="text-emerald-600 font-black text-base mb-4">
                        Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-normal"> / hari</span>
                    </p>

                    <div class="flex items-center justify-between py-3 border-t border-gray-50">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">Stok Unit</p>
                            <p class="text-lg font-black text-gray-900">
                                {{ $alat->stok_tersedia }} <span class="text-xs font-medium text-gray-300">Unit</span>
                            </p>
                        </div>
                        
                        @if($alat->stok_tersedia > 0)
                            <a href="{{ route('peminjam.ajukan', $alat->id) }}" class="bg-[#062c21] hover:bg-emerald-600 text-white w-14 h-14 rounded-2xl shadow-lg transition-all flex items-center justify-center active:scale-95 shadow-emerald-900/20">
                                <i class="fas fa-plus text-lg"></i>
                            </a>
                        @else
                            <button class="bg-gray-100 text-gray-300 cursor-not-allowed w-14 h-14 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-ban text-lg"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <i class="fas fa-search text-gray-200 text-6xl mb-4"></i>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Alat tidak ditemukan</p>
            </div>
            @endforelse
        </div>
    </main>

</body>
</html>