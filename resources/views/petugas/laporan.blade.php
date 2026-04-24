@extends('layouts.petugas')

@section('title', 'Cetak Laporan')

@section('content')
<div class="no-print flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Laporan <span class="text-cyan-600">Aktivitas</span></h1>
        <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">Export data peminjaman buku untuk arsip dan dokumentasi</p>
    </div>
</div>

<div class="no-print bg-white rounded-2xl p-8 border border-slate-200 shadow-sm mb-10 hover:shadow-md transition-shadow duration-300">
    <h2 class="text-lg font-bold text-slate-900 uppercase tracking-tight mb-6 flex items-center gap-2">
        <i class="fas fa-filter text-cyan-600"></i> Konfigurasi Laporan
    </h2>
    
    <form action="{{ route('petugas.laporan.pdf') }}" method="GET" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Dari Tanggal</label>
                <div class="relative">
                    <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    <input type="date" name="tgl_mulai" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none text-xs font-medium hover:border-slate-300 transition-all">
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Sampai Tanggal</label>
                <div class="relative">
                    <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    <input type="date" name="tgl_selesai" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none text-xs font-medium hover:border-slate-300 transition-all">
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105 active:scale-95 flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>
    </form>
</div>

<div class="card-report bg-white rounded-2xl p-10 border border-slate-200 shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="flex flex-col items-center justify-center text-center border-b-2 border-slate-200 pb-8 mb-10">
        <div class="inline-block bg-gradient-to-r from-cyan-100 to-teal-100 px-4 py-2 rounded-lg mb-3">
            <i class="fas fa-buku text-cyan-600 text-lg"></i>
        </div>
        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight leading-none">Rekapitulasi Peminjaman buku</h3>
        <p class="text-[11px] font-bold text-cyan-600 uppercase tracking-wider mt-3">E-PUSTAKA Management System - Tahun 2026</p>
    </div>

    {{-- Table Container --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-600 uppercase tracking-widest border-b-2 border-slate-200">
                    <th class="pb-4 px-3 text-center w-12">No</th>
                    <th class="pb-4 px-3">Tanggal</th>
                    <th class="pb-4 px-3">Peminjam</th>
                    <th class="pb-4 px-3">buku</th>
                    <th class="pb-4 px-3 text-center">Qty</th>
                    <th class="pb-4 px-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($laporans as $index => $data)
                <tr class="hover:bg-cyan-50/50 transition-colors duration-200 group">
                    <td class="py-4 px-3 text-xs font-bold text-slate-500 text-center">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="py-4 px-3 text-xs font-semibold text-slate-900">
                        {{ $data->created_at->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-4 px-3">
                        <p class="text-xs font-bold text-slate-900">{{ $data->user->name }}</p>
                        <p class="text-[9px] text-slate-500 font-medium uppercase">{{ $data->user->role ?? 'Peminjam' }}</p>
                    </td>
                    <td class="py-4 px-3">
                        <p class="text-xs font-semibold text-slate-700">{{ $data->alat->nama_alat }}</p>
                        <p class="text-[9px] text-slate-500 font-medium">{{ $data->alat->kategoris ?? '' }}</p>
                    </td>
                    <td class="py-4 px-3 text-center">
                        <span class="inline-block bg-cyan-100 text-cyan-700 px-2 py-1 rounded text-xs font-bold">{{ $data->jumlah }} Unit</span>
                    </td>
                    <td class="py-4 px-3 text-center">
                        @if($data->status == 'kembali' || $data->status == 'selesai')
                            <span class="inline-block bg-teal-100 text-teal-700 border border-teal-200 px-3 py-1 rounded-lg text-[9px] font-bold uppercase tracking-tight">
                                <i class="fas fa-check-circle mr-1"></i> Selesai
                            </span>
                        @elseif($data->status == 'pinjam' || $data->status == 'disetujui')
                            <span class="inline-block bg-cyan-100 text-cyan-700 border border-cyan-200 px-3 py-1 rounded-lg text-[9px] font-bold uppercase tracking-tight">
                                <i class="fas fa-hourglass-half mr-1"></i> Aktif
                            </span>
                        @elseif($data->status == 'pending')
                            <span class="inline-block bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1 rounded-lg text-[9px] font-bold uppercase tracking-tight">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @elseif($data->status == 'ditolak')
                            <span class="inline-block bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1 rounded-lg text-[9px] font-bold uppercase tracking-tight">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-block bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-lg text-[9px] font-bold uppercase tracking-tight">
                                {{ ucfirst($data->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <i class="fas fa-inbox text-slate-300 text-4xl mb-3 block"></i>
                        <p class="text-slate-500 font-bold uppercase text-xs tracking-wide">Tidak ada data peminjaman ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Summary Stats --}}
    @if($laporans->count() > 0)
    <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl border border-cyan-200">
        <div class="text-center">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Total Transaksi</p>
            <p class="text-2xl font-black text-cyan-600 mt-1">{{ $laporans->count() }}</p>
        </div>
        <div class="text-center">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Total Unit</p>
            <p class="text-2xl font-black text-teal-600 mt-1">{{ $laporans->sum('jumlah') }}</p>
        </div>
        <div class="text-center">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-black text-green-600 mt-1">{{ $laporans->whereIn('status', ['selesai', 'kembali'])->count() }}</p>
        </div>
        <div class="text-center">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Masih Aktif</p>
            <p class="text-2xl font-black text-orange-600 mt-1">{{ $laporans->whereIn('status', ['pinjam', 'disetujui'])->count() }}</p>
        </div>
    </div>
    @endif

    {{-- Signature Section --}}
    <div class="mt-16 grid grid-cols-2 gap-10 text-center">
        <div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-20">Admin / Petugas,</p>
            <div class="border-t-2 border-slate-400 pt-3">
                <p class="text-xs font-bold text-slate-900 uppercase">Nama Petugas</p>
                <p class="text-[9px] font-medium text-slate-500 mt-1">Staff E-PUSTAKA</p>
            </div>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-20">Mengetahui</p>
            <div class="border-t-2 border-slate-400 pt-3">
                <p class="text-xs font-bold text-slate-900 uppercase">Kepala Bagian</p>
                <p class="text-[9px] font-medium text-slate-500 mt-1">E-PUSTAKA Management</p>
            </div>
        </div>
    </div>

    {{-- Print Info --}}
    <div class="mt-8 pt-6 border-t border-slate-200 text-center">
        <p class="text-[9px] text-slate-400">
            <i class="fas fa-print mr-1"></i> Dicetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y - H:i') }} WIB | E-PUSTAKA Management System © 2026
        </p>
    </div>
</div>
@endsection