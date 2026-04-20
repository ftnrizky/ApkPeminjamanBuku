@extends('layouts.admin')

@section('title', 'Daftar Laptop Belum Dikembalikan')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Laptop Belum <span class="text-cyan-600">Dikembalikan</span></h1>
        <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">Monitor daftar laptop yang masih dalam peminjaman</p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    @php
        $totalBelumKembali = $peminjamanList->total();
        $totalOverdue = $peminjamanList->getCollection()->filter(fn($p) => $p->is_overdue)->count();
        $totalCritical = $peminjamanList->getCollection()->filter(fn($p) => $p->is_critical)->count();
    @endphp

    <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6 border border-cyan-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Total Belum Dikembalikan</p>
                <p class="text-3xl font-black text-cyan-700 mt-2">{{ $totalBelumKembali }}</p>
            </div>
            <div class="w-12 h-12 bg-cyan-200 rounded-full flex items-center justify-center">
                <i class="fas fa-laptop text-cyan-700 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Terlambat</p>
                <p class="text-3xl font-black text-amber-700 mt-2">{{ $totalOverdue }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-amber-700 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-6 border border-rose-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Kritis (>3 Hari)</p>
                <p class="text-3xl font-black text-rose-700 mt-2">{{ $totalCritical }}</p>
            </div>
            <div class="w-12 h-12 bg-rose-200 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-rose-700 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filter Section --}}
<div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm mb-8">
    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
        <i class="fas fa-filter text-cyan-600"></i> Filter & Cari
    </h2>
    
    <form method="GET" action="{{ route('admin.overdue_list') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Search --}}
        <div class="space-y-2">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama Peminjam/Laptop</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none text-sm">
        </div>

        {{-- Kategori --}}
        <div class="space-y-2">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
            <select name="kategori" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none text-sm">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->kategori }}" {{ request('kategori') === $kat->kategori ? 'selected' : '' }}>
                        {{ ucfirst($kat->kategori) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="space-y-2">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</label>
            <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none text-sm">
                <option value="">Semua Status</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                        {{ ucfirst($st) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-2 items-end">
            <button type="submit" class="flex-1 bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white font-bold px-4 py-3 rounded-lg transition-all hover:scale-105 active:scale-95 shadow-md text-sm">
                Cari
            </button>
            <a href="{{ route('admin.overdue_list') }}?overdue_only=1" class="flex-1 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold px-4 py-3 rounded-lg transition-all hover:scale-105 active:scale-95 shadow-md text-sm text-center">
                Terlambat
            </a>
        </div>
    </form>
</div>

{{-- Data Table --}}
<div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-600 uppercase tracking-widest border-b-2 border-slate-200">
                    <th class="pb-4 px-4">Kode</th>
                    <th class="pb-4 px-4">Peminjam</th>
                    <th class="pb-4 px-4">Laptop</th>
                    <th class="pb-4 px-4 text-center">Qty</th>
                    <th class="pb-4 px-4">Tgl Pinjam</th>
                    <th class="pb-4 px-4">Batas Kembali</th>
                    <th class="pb-4 px-4 text-center">Status</th>
                    <th class="pb-4 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamanList as $pinjam)
                    <tr class="group hover:bg-cyan-50/50 transition-colors duration-200">
                        <td class="py-4 px-4">
                            <span class="text-xs font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded-lg">
                                PJM-{{ str_pad($pinjam->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-xs font-bold text-slate-900">{{ $pinjam->user->name }}</p>
                            <p class="text-[9px] text-slate-500 font-medium">{{ $pinjam->user->email }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-xs font-bold text-slate-900">{{ $pinjam->alat->nama_alat }}</p>
                            <p class="text-[9px] text-slate-500">{{ $pinjam->alat->kategori }}</p>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="text-xs font-bold text-slate-900">{{ $pinjam->jumlah }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-xs font-semibold text-slate-900">{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-xs font-semibold {{ $pinjam->is_overdue ? 'text-rose-700 font-black' : 'text-slate-900' }}">
                                {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($pinjam->is_overdue)
                                <span class="text-[10px] font-bold px-3 py-1 rounded-lg {{ $pinjam->is_critical ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $pinjam->days_remaining }} {{ $pinjam->days_remaining === 1 ? 'hari' : 'hari' }} Terlambat
                                </span>
                            @else
                                <span class="text-[10px] font-bold px-3 py-1 rounded-lg bg-cyan-100 text-cyan-700">
                                    <i class="fas fa-hourglass-half mr-1"></i> {{ $pinjam->days_remaining }} hari
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button onclick="sendReminder({{ $pinjam->id }})" class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white text-[10px] font-bold px-3 py-2 rounded-lg transition-all hover:scale-105 active:scale-95 shadow-sm">
                                <i class="fas fa-bell mr-1"></i> Notif
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <i class="fas fa-inbox text-slate-300 text-4xl mb-3 block"></i>
                            <p class="text-slate-500 font-bold uppercase text-xs">Semua laptop sudah dikembalikan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($peminjamanList->hasPages())
    <div class="mt-6 pt-6 border-t border-slate-200">
        {{ $peminjamanList->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function sendReminder(peminjamanId) {
        if (!confirm('Kirim notifikasi pengingat pengembalian?')) return;

        fetch(`/admin/overdue-list/reminder/${peminjamanId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(e => alert('Error: ' + e.message));
    }
</script>
@endpush

@endsection
