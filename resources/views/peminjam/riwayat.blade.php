@extends('layouts.peminjam')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Riwayat <span class="text-cyan-600">Peminjaman</span></h1>
        <p class="text-slate-500 text-[10px] font-medium mt-2 uppercase tracking-wider">Semua aktivitas peminjaman laptop Anda</p>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center w-40">Kode</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Laptop</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Qty</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $item)
                        @php
                            $statusColor = match($item->status) {
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'disetujui' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                                'selesai' => 'bg-teal-100 text-teal-700 border-teal-200',
                                'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200'
                            };
                        @endphp
                        <tr class="hover:bg-cyan-50/30 transition-all duration-200 group">
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-900 text-sm bg-slate-100 px-3 py-1.5 rounded-lg group-hover:bg-cyan-100 group-hover:text-cyan-700 transition-all duration-200">
                                    PJM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-100 to-teal-100 flex items-center justify-center flex-shrink-0 border border-cyan-200 group-hover:scale-110 transition-transform duration-200">
                                        @if($item->alat->foto)
                                            <img src="{{ asset('storage/' . $item->alat->foto) }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="fas fa-laptop text-cyan-600 text-sm"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm">{{ $item->alat->nama_alat }}</p>
                                        <p class="text-[9px] text-slate-500 uppercase font-medium">{{ $item->alat->kategori }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $item->jumlah }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-bold uppercase border {{ $statusColor }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="{{ $item->total_denda > 0 ? 'text-rose-600 font-black' : 'text-teal-600 font-bold' }}">
                                    Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <i class="fas fa-inbox text-slate-300 text-4xl mb-3 block"></i>
                                <p class="text-slate-500 font-bold uppercase text-xs tracking-wide">Belum ada riwayat peminjaman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50 border-t border-slate-200">
            {{ $riwayat->links() }}
        </div>
    </div>
@endsection