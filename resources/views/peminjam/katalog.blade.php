@extends('layouts.peminjam')

@section('title', 'Katalog Laptop')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Cari <span class="text-cyan-600">Laptop</span> Impianmu</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Pilih laptop berkualitas tinggi sesuai kebutuhan. Gaming, Business, atau Design!</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('peminjam.katalog') }}" method="GET" class="relative group">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laptop, spesifikasi..." 
                    class="w-64 pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none transition-all font-medium hover:border-slate-300">
            </form>
        </div>
    </div>

    {{-- KATEGORI TABS --}}
    <div class="flex items-center gap-3 mb-8 overflow-x-auto pb-2 no-scrollbar">
        @php 
            $categories = ['Semua' => 'fa-th-large', 'Gaming' => 'fa-gamepad', 'Business' => 'fa-briefcase', 'Design' => 'fa-palette'];
        @endphp
        @foreach($categories as $cat => $icon)
            <a href="{{ route('peminjam.katalog', ['kategori' => $cat, 'search' => request('search')]) }}" 
               class="px-6 py-2.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2
               {{ (request('kategori', 'Semua') == $cat) ? 'bg-gradient-to-r from-cyan-500 to-cyan-600 text-white shadow-lg hover:from-cyan-600 hover:to-cyan-700' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 hover:border-cyan-200' }}">
                <i class="fas {{ $icon }}"></i> {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- GRID LAPTOP --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($alats as $alat)
        <div class="group bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-lg hover:border-cyan-200 transition-all duration-300">
            <div class="relative h-64 w-full bg-gradient-to-br from-slate-50 to-slate-100 rounded-lg overflow-hidden mb-5">
                <span class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-tight shadow-md z-10
                    {{ $alat->kondisi == 'baik' ? 'text-teal-600 border border-teal-100' : ($alat->kondisi == 'lecet' ? 'text-amber-600 border border-amber-100' : 'text-rose-600 border border-rose-100') }}">
                    {{ ucfirst($alat->kondisi) }}
                </span>

                <div class="w-full h-full flex items-center justify-center">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <i class="fas fa-laptop fa-4x text-slate-300 group-hover:text-cyan-300 transition-colors"></i>
                    @endif
                </div>

                <span class="absolute bottom-4 right-4 {{ $alat->stok_tersedia > 0 ? 'bg-teal-500 text-white' : 'bg-slate-400 text-white' }} text-[9px] font-bold px-3 py-1.5 rounded-lg shadow-lg uppercase tracking-tight">
                    {{ $alat->stok_tersedia > 0 ? '✓ Tersedia' : '✕ Kosong' }}
                </span>
            </div>

            <div class="px-1">
                <p class="text-[10px] font-bold text-cyan-600 uppercase tracking-widest mb-1 flex items-center gap-1">
                    <i class="fas fa-tag"></i> {{ $alat->kategori }}
                </p>
                <h3 class="text-lg font-bold text-slate-900 leading-tight mb-2 truncate tracking-tight">{{ $alat->nama_alat }}</h3>
                
                <p class="text-cyan-600 font-bold text-lg mb-4">
                    Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}<span class="text-[9px] text-slate-500 font-normal ml-1">/ hari</span>
                </p>

                <div class="flex items-center justify-between py-3 border-t border-slate-100">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase leading-none mb-1 tracking-wider">Tersedia</p>
                        <p class="text-lg font-bold text-slate-900">
                            {{ $alat->stok_tersedia }} <span class="text-xs font-medium text-slate-400">Unit</span>
                        </p>
                    </div>
                    
                    @if($alat->stok_tersedia > 0)
                        <a href="{{ route('peminjam.ajukan', $alat->id) }}" class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white w-14 h-14 rounded-lg shadow-md transition-all flex items-center justify-center active:scale-95 hover:shadow-lg hover:scale-105">
                            <i class="fas fa-plus text-lg"></i>
                        </a>
                    @else
                        <button class="bg-slate-100 text-slate-400 cursor-not-allowed w-14 h-14 rounded-lg flex items-center justify-center opacity-50">
                            <i class="fas fa-ban text-lg"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <i class="fas fa-search text-slate-300 text-6xl mb-4"></i>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Laptop tidak ditemukan</p>
            <p class="text-slate-400 text-xs mt-2">Coba ubah filter kategori atau cari dengan kata kunci yang berbeda</p>
        </div>
        @endforelse
    </div>
@endsection