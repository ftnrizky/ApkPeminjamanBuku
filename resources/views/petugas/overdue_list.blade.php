@extends('layouts.petugas')

@section('title', 'Daftar Laptop Belum Dikembalikan')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight">
            Laptop Belum <span class="text-cyan-600">Dikembalikan</span>
        </h1>
        <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">
            Daftar laptop yang masih dalam peminjaman
        </p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    @php
        $totalBelumKembali = $peminjamanList->total();
        $totalOverdue = $peminjamanList->getCollection()->filter(fn($p) => $p->is_overdue ?? false)->count();
        $totalCritical = $peminjamanList->getCollection()->filter(fn($p) => $p->is_critical ?? false)->count();
    @endphp

    <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6 border shadow-sm">
        <p class="text-xs font-bold text-slate-600">Total</p>
        <p class="text-3xl font-black text-cyan-700">{{ $totalBelumKembali }}</p>
    </div>

    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 border shadow-sm">
        <p class="text-xs font-bold text-slate-600">Terlambat</p>
        <p class="text-3xl font-black text-amber-700">{{ $totalOverdue }}</p>
    </div>

    <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-6 border shadow-sm">
        <p class="text-xs font-bold text-slate-600">Kritis</p>
        <p class="text-3xl font-black text-rose-700">{{ $totalCritical }}</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl p-6 border shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-xs font-bold text-slate-600 border-b">
                    <th class="py-3">Kode</th>
                    <th>Peminjam</th>
                    <th>Laptop</th>
                    <th>Qty</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($peminjamanList as $pinjam)
                    <tr class="border-b hover:bg-gray-50">

                        {{-- KODE --}}
                        <td class="py-3">
                            PJM-{{ str_pad($pinjam->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- USER (FIX NULL) --}}
                        <td>
                            {{ optional($pinjam->user)->name ?? '-' }} <br>
                            <small>{{ optional($pinjam->user)->email ?? '-' }}</small>
                        </td>

                        {{-- ALAT (FIX NULL) --}}
                        <td>
                            {{ optional($pinjam->alat)->nama_alat ?? '-' }}
                        </td>

                        <td>{{ $pinjam->jumlah ?? '-' }}</td>

                        {{-- TGL PINJAM (FIX NULL) --}}
                        <td>
                            {{ $pinjam->tgl_pinjam 
                                ? \Carbon\Carbon::parse($pinjam->tgl_pinjam)->translatedFormat('d M Y') 
                                : '-' }}
                        </td>

                        {{-- TGL KEMBALI (FIX NULL) --}}
                        <td>
                            {{ $pinjam->tgl_kembali 
                                ? \Carbon\Carbon::parse($pinjam->tgl_kembali)->translatedFormat('d M Y') 
                                : '-' }}
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($pinjam->is_overdue ?? false)
                                <span class="text-red-600 text-xs">
                                    {{ $pinjam->days_remaining ?? 0 }} hari terlambat
                                </span>
                            @else
                                <span class="text-cyan-600 text-xs">
                                    {{ $pinjam->days_remaining ?? 0 }} hari
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td>
                            <button onclick="sendReminder({{ $pinjam->id }})"
                                class="bg-cyan-500 text-white px-3 py-1 rounded text-xs">
                                Notif
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $peminjamanList->links() }}
    </div>
</div>

{{-- SCRIPT --}}
<script>
function sendReminder(id) {
    if (!confirm('Kirim pengingat?')) return;

    fetch(`/petugas/overdue-list/reminder/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => alert(data.message))
    .catch(err => alert('Error'));
}
</script>

@endsection