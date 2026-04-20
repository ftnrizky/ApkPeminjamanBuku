@extends('layouts.peminjam')

@section('title', 'Pengembalian Laptop')

@section('content')
    {{-- HEADER --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight leading-none">
                Pengembalian <span class="text-cyan-600">Laptop</span>
            </h1>
            <p class="text-slate-500 text-[10px] font-medium mt-2 uppercase tracking-wider">Selesaikan peminjaman aktif dan cek denda Anda</p>
        </div>
        
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md animate-pulse flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- INFO PANEL --}}
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-xl border border-amber-200 flex flex-col md:flex-row gap-6 mb-10 shadow-sm">
        <div class="bg-amber-500 text-white w-12 h-12 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
            <i class="fas fa-alert-circle text-lg"></i>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
            <div>
                <p class="text-[10px] text-amber-900 font-bold uppercase tracking-wider">Aturan Dasar</p>
                <p class="text-xs text-amber-800 font-medium uppercase mt-1">Maksimal pinjam 3 hari kerja.</p>
            </div>
            <div>
                <p class="text-[10px] text-amber-900 font-bold uppercase tracking-wider">Denda Terlambat</p>
                <p class="text-xs text-rose-600 font-bold uppercase mt-1">Rp 5.000 per hari.</p>
            </div>
            <div>
                <p class="text-[10px] text-amber-900 font-bold uppercase tracking-wider">Denda Kondisi</p>
                <p class="text-xs text-amber-800 font-medium uppercase mt-1">Rusak/Hilang sesuai nilai laptop.</p>
            </div>
        </div>
    </div>

    {{-- LIST LAPTOP AKTIF --}}
    <div class="space-y-6">
        @forelse($peminjamans as $pinjam)
            <div class="bg-white rounded-2xl p-8 border {{ $pinjam->estimasi_denda > 0 ? 'border-rose-200 bg-rose-50/5' : 'border-slate-200' }} shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-cyan-500/5 to-teal-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>

                <div class="flex flex-col lg:flex-row items-center gap-8 relative z-10">
                    <div class="w-40 h-40 {{ $pinjam->estimasi_denda > 0 ? 'bg-gradient-to-br from-rose-100 to-orange-100' : 'bg-gradient-to-br from-cyan-100 to-teal-100' }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:scale-105 transition-transform duration-300 border {{ $pinjam->estimasi_denda > 0 ? 'border-rose-200' : 'border-cyan-200' }}">
                        @if($pinjam->alat->foto)
                            <img src="{{ asset('storage/' . $pinjam->alat->foto) }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <i class="fas fa-laptop {{ $pinjam->estimasi_denda > 0 ? 'text-rose-400' : 'text-cyan-400' }} text-6xl opacity-40"></i>
                        @endif
                    </div>

                    <div class="flex-1 text-center lg:text-left">
                        <div class="flex flex-wrap justify-center lg:justify-start gap-2 mb-3">
                            <span class="bg-slate-900 text-white text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-tight">
                                PJM-{{ str_pad($pinjam->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="bg-cyan-100 text-cyan-700 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-tight flex items-center gap-1">
                                <i class="fas fa-laptop"></i> {{ $pinjam->alat->kategori }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">{{ $pinjam->alat->nama_alat }}</h3>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jumlah</p>
                                <p class="text-lg font-bold text-slate-900">{{ $pinjam->jumlah }} <span class="text-xs text-slate-400 font-medium">Unit</span></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Batas Kembali</p>
                                <p class="text-lg font-bold {{ $pinjam->estimasi_denda > 0 ? 'text-rose-600' : 'text-teal-600' }}">
                                    {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Denda</p>
                                <p class="text-lg font-bold {{ $pinjam->estimasi_denda > 0 ? 'text-rose-600' : 'text-slate-900' }}">
                                    Rp {{ number_format($pinjam->estimasi_denda, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pembayaran</p>
                                <p class="text-lg font-bold text-teal-700">Tunai ke Petugas</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-shrink-0 w-full lg:w-auto pt-6 lg:pt-0 border-t lg:border-t-0 lg:border-l border-slate-200 lg:pl-10">
                        <form action="{{ route('peminjam.proses_kembali', $pinjam->id) }}" method="POST" onsubmit="return confirm('Sudah siap mengembalikan laptop?')">
                            @csrf
                            <button type="submit" class="w-full lg:w-auto bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white font-bold px-10 py-4 rounded-lg shadow-lg hover:shadow-xl transition-all hover:scale-105 active:scale-95 text-xs tracking-wider uppercase flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Ajukan Pengembalian
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-2xl border-2 border-dashed border-cyan-200 shadow-sm">
                <i class="fas fa-check-double text-cyan-300 text-5xl mb-4"></i>
                <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Tidak Ada Peminjaman Aktif</h3>
                <p class="text-slate-500 text-[10px] font-medium uppercase tracking-wider mt-2">Semua laptop sudah dikembalikan. Mantap!</p>
                <a href="{{ route('peminjam.katalog') }}" class="inline-block mt-6 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded-lg hover:from-cyan-600 hover:to-cyan-700 transition-all hover:shadow-lg">
                    Pinjam Laptop Lagi →
                </a>
            </div>
        @endforelse
    </div>
@endsection