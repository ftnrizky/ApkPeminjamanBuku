@extends('layouts.admin')

@section('title', 'Daftar buku Belum Dikembalikan')

@section('content')

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight">
                buku Belum <span class="text-cyan-600">Dikembalikan</span>
            </h1>
            <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">
                Monitor daftar buku yang masih dalam peminjaman
            </p>
        </div>
    </div>

    {{-- FILTER (TAMBAHAN TAPI TIDAK MERUSAK DESAIN) --}}
    <div class="bg-white rounded-2xl p-6 border shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.overdue_list') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / buku"
                class="px-4 py-3 bg-slate-50 border rounded-lg text-sm">

            <select name="kategori_id" class="px-4 py-3 bg-slate-50 border rounded-lg text-sm">
                <option value="">Semua Kategori</option>

                @foreach ($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="px-4 py-3 bg-slate-50 border rounded-lg text-sm">
                <option value="">Semua Status</option>
                @foreach ($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                        {{ ucfirst($st) }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button class="bg-cyan-500 text-white px-4 py-3 rounded-lg text-sm font-bold flex-1">
                    Cari
                </button>

                <a href="{{ route('admin.overdue_list') }}?overdue_only=1"
                    class="bg-amber-500 text-white px-4 py-3 rounded-lg text-sm font-bold flex-1 text-center">
                    Terlambat
                </a>
            </div>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl p-6 border shadow-sm">

        @php
            $totalBelumKembali = $peminjamanList->total();
            $totalOverdue = $peminjamanList->getCollection()->filter(fn($p) => $p->is_overdue)->count();
            $totalCritical = $peminjamanList->getCollection()->filter(fn($p) => $p->is_critical)->count();
        @endphp

        {{-- STATS --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="text-cyan-600 font-bold">Total: {{ $totalBelumKembali }}</div>
            <div class="text-amber-600 font-bold">Terlambat: {{ $totalOverdue }}</div>
            <div class="text-red-600 font-bold">Kritis: {{ $totalCritical }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">

                <thead>
                    <tr class="text-xs font-bold text-slate-600 border-b">
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>buku</th>
                        <th>Qty</th>
                        <th>Pinjam</th>
                        <th>Batas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($peminjamanList as $pinjam)
                        @php
                            $now = \Carbon\Carbon::now();
                            $batas = $pinjam->tgl_kembali ? \Carbon\Carbon::parse($pinjam->tgl_kembali) : null;
                            $diff = $batas ? $now->diffInSeconds($batas, false) : null;
                        @endphp

                        <tr class="border-b hover:bg-gray-50">

                            <td>PJM-{{ str_pad($pinjam->id, 4, '0', STR_PAD_LEFT) }}</td>

                            <td>
                                {{ optional($pinjam->user)->name ?? '-' }}<br>
                                <small>{{ optional($pinjam->user)->email ?? '-' }}</small>
                            </td>

                            <td>{{ optional($pinjam->alat)->nama_alat ?? '-' }}</td>

                            <td>{{ $pinjam->jumlah ?? '-' }}</td>

                            <td>
                                {{ $pinjam->tgl_pinjam ? \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d M Y H:i') : '-' }}
                            </td>

                            <td>
                                {{ $pinjam->tgl_kembali ? \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M Y H:i') : '-' }}
                            </td>

                            {{-- REALTIME STATUS --}}
                            <td>
                                @if (is_null($diff))
                                    <span class="text-gray-500 text-xs">-</span>
                                @else
                                    <span class="text-xs font-bold countdown" data-seconds="{{ $diff }}">
                                        loading...
                                    </span>
                                @endif
                            </td>

                            <td>
                                <button onclick="sendReminder({{ $pinjam->id }})"
                                    class="bg-cyan-500 text-white px-3 py-1 rounded text-xs">
                                    Notif
                                </button>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $peminjamanList->links() }}
        </div>
    </div>

    {{-- REALTIME JS --}}
    <script>
        function format(seconds) {
            let abs = Math.abs(seconds);
            return {
                jam: Math.floor(abs / 3600),
                menit: Math.floor((abs % 3600) / 60),
                detik: abs % 60
            };
        }

        function updateCountdown() {
            document.querySelectorAll('.countdown').forEach(el => {
                let seconds = parseInt(el.getAttribute('data-seconds'));
                seconds--;
                el.setAttribute('data-seconds', seconds);

                let t = format(seconds);

                if (seconds < 0) {
                    el.className = "text-xs font-bold text-red-600 countdown";
                    el.innerText = `${t.jam}j ${t.menit}m ${t.detik}s terlambat`;
                } else if (seconds <= 3600) {
                    el.className = "text-xs font-bold text-amber-600 countdown";
                    el.innerText = `${t.jam}j ${t.menit}m ${t.detik}s tersisa`;
                } else {
                    el.className = "text-xs font-bold text-cyan-600 countdown";
                    el.innerText = `${t.jam}j ${t.menit}m ${t.detik}s tersisa`;
                }
            });
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        function sendReminder(id) {
            if (!confirm('Kirim pengingat?')) return;

            fetch(`/admin/overdue-list/reminder/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => alert(data.message))
                .catch(() => alert('Error'));
        }
    </script>

@endsection
