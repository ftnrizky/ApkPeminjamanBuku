@extends('layouts.admin')

@section('title', 'Riwayat Pengembalian')

@section('content')
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase italic">Riwayat <span class="text-emerald-600">Pengembalian</span></h1>
        <p class="text-gray-500 font-medium text-sm">Log pengembalian alat berdasarkan kondisi dan denda.</p>
    </div>
    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-2xl text-xs font-bold animate-bounce">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
</div>

<form action="{{ route('admin.pengembalian') }}" method="GET" class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 mb-6">
    <div class="relative flex-1">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam atau alat..." class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-sm font-semibold">
    </div>
    <button type="submit" class="bg-[#062c21] text-white px-8 py-3 rounded-2xl font-bold text-sm hover:bg-emerald-900 transition-all">
        Cari Data
    </button>
</form>

<div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-36">Kode Pinjam</th>
                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Alat & Peminjam</th>
                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tgl Dikembalikan</th>
                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Total Denda</th>
                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($peminjamans as $data)
                @php
                    // Parse kondisi dari kolom 'tujuan'
                    $countBaik = 0; $countLecet = 0; $countRusak = 0; $countHilang = 0;
                    if (str_contains($data->tujuan ?? '', 'Baik:')) {
                        preg_match('/Baik:(\d+), Lecet:(\d+), Rusak:(\d+), Hilang:(\d+)/', $data->tujuan, $matches);
                        if (count($matches) == 5) {
                            $countBaik = $matches[1];
                            $countLecet = $matches[2];
                            $countRusak = $matches[3];
                            $countHilang = $matches[4];
                        }
                    }
                    
                    // Hitung denda terlambat (berdasarkan TANGGAL, bukan jam)
                    $dendaTerlambat = 0;
                    if ($data->tgl_dikembalikan && $data->tgl_kembali) {
                        $deadline = \Carbon\Carbon::parse($data->tgl_kembali)->startOfDay();
                        $kembali = \Carbon\Carbon::parse($data->tgl_dikembalikan)->startOfDay();
                        if ($kembali->gt($deadline)) {
                            $selisihHari = $deadline->diffInDays($kembali);
                            $dendaTerlambat = $selisihHari * 5000;
                        }
                    }
                    
                    // Hitung denda kondisi
                    $dendaKondisi = ($countLecet * 15000) + ($countRusak * 50000);
                    if ($countHilang > 0) {
                        $dendaKondisi += $countHilang * ($data->alat->harga_asli ?? 0);
                    }
                @endphp
                <tr class="hover:bg-emerald-50/30 transition-colors group">
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-gray-900 text-sm bg-gray-100 px-2 py-1 rounded-lg">
                            PJM-{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold uppercase">
                                {{ substr($data->alat->nama_alat ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm mb-0.5 leading-none">{{ $data->alat->nama_alat ?? 'Alat Dihapus' }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $data->user->name ?? 'User Unknown' }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-emerald-600">
                                {{ $data->tgl_dikembalikan ? \Carbon\Carbon::parse($data->tgl_dikembalikan)->translatedFormat('d M Y') : '-' }}
                            </span>
                            <span class="text-[10px] text-gray-400 italic font-medium">
                                Batas: {{ \Carbon\Carbon::parse($data->tgl_kembali)->format('d/m/Y') }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @php $nilaiDenda = (float) ($data->total_denda ?? 0); @endphp
                        @if($nilaiDenda > 0)
                            <div class="flex flex-col">
                                <span class="text-rose-600 font-black text-sm">
                                    Rp {{ number_format($nilaiDenda, 0, ',', '.') }}
                                </span>
                                <span class="text-[8px] text-rose-400 font-bold uppercase italic tracking-tighter">Denda Terbayar</span>
                            </div>
                        @else
                            <div class="flex flex-col items-center">
                                <span class="text-emerald-500 font-bold text-[10px] uppercase">Lunas</span>
                                <span class="text-[8px] text-emerald-300 font-medium italic">Tanpa Denda</span>
                            </div>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="showDetailModal({
                                id: {{ $data->id }},
                                user: { name: '{{ addslashes($data->user->name) }}' },
                                alat: { nama_alat: '{{ addslashes($data->alat->nama_alat) }}', harga_asli: {{ $data->alat->harga_asli ?? 0 }} },
                                jumlah: {{ $data->jumlah }},
                                kondisi: '{{ $data->kondisi }}',
                                tgl_kembali: '{{ $data->tgl_kembali }}',
                                tgl_dikembalikan: '{{ $data->tgl_dikembalikan }}',
                                total_denda: {{ $data->total_denda ?? 0 }}
                            }, {{ $countBaik }}, {{ $countLecet }}, {{ $countRusak }}, {{ $countHilang }}, {{ $dendaTerlambat }}, {{ $dendaKondisi }})" 
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition-all"
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            <form action="{{ route('admin.peminjaman.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus permanen riwayat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 hover:bg-rose-50 hover:text-rose-500 transition-all border border-gray-200">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-20 text-center text-gray-400 font-bold italic">
                        <i class="fas fa-box-open block text-4xl mb-3 opacity-20"></i>
                        Tidak ada data pengembalian ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-6 bg-gray-50/50 flex items-center justify-between border-t border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            Showing {{ $peminjamans->firstItem() ?? 0 }} to {{ $peminjamans->lastItem() ?? 0 }} of {{ $peminjamans->total() }} results
        </p>
        <div class="pagination-custom">
            {{ $peminjamans->appends(request()->input())->links() }}
        </div>
    </div>
</div>

<!-- MODAL DETAIL -->
<div id="modalDetail" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl max-w-md w-full">
        <div class="bg-emerald-600 rounded-t-2xl p-4 text-white flex justify-between items-center">
            <h3 class="font-bold">Detail Pengembalian</h3>
            <button onclick="closeDetailModal()" class="text-white text-xl">&times;</button>
        </div>
        <div class="p-4 space-y-3" id="modalDetailContent"></div>
        <div class="p-4 pt-0">
            <button onclick="closeDetailModal()" class="w-full bg-gray-200 py-2 rounded-lg text-sm font-bold">Tutup</button>
        </div>
    </div>
</div>

<script>
    function showDetailModal(data, countBaik, countLecet, countRusak, countHilang, dendaTerlambat, dendaKondisi) {
        // Format tanggal (abaikan jam)
        const tglKembali = data.tgl_kembali ? new Date(data.tgl_kembali).toLocaleDateString('id-ID') : '-';
        const tglDikembalikan = data.tgl_dikembalikan ? new Date(data.tgl_dikembalikan).toLocaleDateString('id-ID') : '-';
        
        const totalDenda = data.total_denda || 0;
        
        let kondisiHtml = '';
        if (countBaik > 0 || countLecet > 0 || countRusak > 0 || countHilang > 0) {
            kondisiHtml = `
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="font-bold text-sm mb-2">Detail Kondisi per Unit:</p>
                    <div class="grid grid-cols-4 gap-2 text-center text-xs">
                        <div><span class="inline-block w-3 h-3 rounded-full bg-emerald-500"></span> Baik: ${countBaik}</div>
                        <div><span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span> Lecet: ${countLecet}</div>
                        <div><span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> Rusak: ${countRusak}</div>
                        <div><span class="inline-block w-3 h-3 rounded-full bg-gray-700"></span> Hilang: ${countHilang}</div>
                    </div>
                </div>
            `;
        } else {
            kondisiHtml = `
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="font-bold text-sm mb-2">Kondisi:</p>
                    <div class="text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700">${data.kondisi || 'Baik'}</span>
                    </div>
                </div>
            `;
        }
        
        const html = `
            <div class="space-y-2 text-sm">
                <div class="flex justify-between border-b pb-1">
                    <span class="text-gray-500">Kode:</span>
                    <span class="font-bold">PJM-${String(data.id).padStart(4, '0')}</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span class="text-gray-500">Peminjam:</span>
                    <span class="font-bold">${data.user.name}</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span class="text-gray-500">Alat:</span>
                    <span class="font-bold">${data.alat.nama_alat}</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span class="text-gray-500">Jumlah:</span>
                    <span class="font-bold">${data.jumlah} Unit</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span class="text-gray-500">Batas Kembali:</span>
                    <span class="font-bold">${tglKembali}</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span class="text-gray-500">Tgl Dikembalikan:</span>
                    <span class="font-bold">${tglDikembalikan}</span>
                </div>
                ${kondisiHtml}
                <div class="bg-emerald-50 p-3 rounded-lg mt-2">
                    <div class="flex justify-between">
                        <span>Denda Terlambat:</span>
                        <span class="font-bold text-rose-600">Rp ${dendaTerlambat.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Denda Kondisi:</span>
                        <span class="font-bold text-rose-600">Rp ${dendaKondisi.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="border-t pt-2 mt-2 flex justify-between font-bold">
                        <span>TOTAL DENDA:</span>
                        <span class="text-rose-700">Rp ${totalDenda.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('modalDetailContent').innerHTML = html;
        document.getElementById('modalDetail').classList.remove('hidden');
        document.getElementById('modalDetail').classList.add('flex');
    }
    
    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
        document.getElementById('modalDetail').classList.remove('flex');
    }
    
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDetailModal();
    });
</script>
@endsection