@extends('layouts.admin')

@section('title', 'Riwayat Pengembalian')

@section('content')
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-900 text-slate-900">Riwayat Pengembalian</h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Log pengembalian buku berdasarkan kondisi dan denda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pengembalian.export_pdf', request()->only(['search'])) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-cyan-200 bg-white px-5 py-3 text-sm font-semibold text-cyan-700 shadow-sm transition hover:border-cyan-300 hover:bg-cyan-50">
                <i class="fas fa-file-pdf text-sm"></i> Export PDF
            </a>
            @if (session('success'))
                <div
                    class="bg-cyan-100/50 border border-cyan-200 text-cyan-700 px-4 py-2 rounded-xl text-xs font-bold animate-pulse backdrop-blur-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.pengembalian') }}" method="GET"
        class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row gap-4 mb-6 hover:shadow-md transition-all duration-300">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama peminjam atau buku..."
                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 transition-all text-sm font-medium hover:border-slate-300">
        </div>
        <button type="submit"
            class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-8 py-3 rounded-xl font-bold text-sm hover:from-cyan-600 hover:to-cyan-700 transition-all duration-200 hover:shadow-lg hover:scale-105 active:scale-95">
            Cari Data
        </button>
    </form>

    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center w-36">
                        Kode Pinjam</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">buku & Peminjam
                    </th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Tgl
                        Dikembalikan</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Total
                        Denda</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamans as $data)
                    @php
                        // Parse kondisi dari kolom 'tujuan'
                        $countBaik = 0;
                        $countLecet = 0;
                        $countRusak = 0;
                        $countHilang = 0;
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
                        $dendaKondisi = $countLecet * 50000 + $countRusak * 200000;
                        if ($countHilang > 0) {
                            $dendaKondisi += $countHilang * 500000;
                        }
                    @endphp
                    <tr class="hover:bg-cyan-50/40 transition-all duration-200 group">
                        <td class="px-6 py-4 text-center">
                            <span
                                class="font-bold text-slate-900 text-sm bg-slate-100 px-3 py-1.5 rounded-lg group-hover:bg-cyan-100 group-hover:text-cyan-700 transition-all duration-200">
                                PJM-{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 shrink-0 rounded-lg bg-gradient-to-br from-cyan-100 to-teal-100 flex items-center justify-center text-cyan-600 font-bold uppercase group-hover:scale-110 transition-transform duration-200">
                                    {{ substr($data->alat->nama_alat ?? 'L', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm mb-0.5 leading-none">
                                        {{ $data->alat->nama_alat ?? 'buku Dihapus' }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-tight">
                                        {{ $data->user->name ?? 'User Unknown' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-cyan-600">
                                    {{ $data->tgl_dikembalikan ? \Carbon\Carbon::parse($data->tgl_dikembalikan)->translatedFormat('d M Y') : '-' }}
                                </span>
                                <span class="text-[10px] text-slate-400 italic font-medium">
                                    Batas: {{ \Carbon\Carbon::parse($data->tgl_kembali)->format('d/m/Y') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php $nilaiDenda = (float) ($data->total_denda ?? 0); @endphp
                            @if ($nilaiDenda > 0)
                                <div class="flex flex-col">
                                    <span class="text-rose-600 font-black text-sm">
                                        Rp {{ number_format($nilaiDenda, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[8px] text-rose-400 font-bold uppercase italic tracking-tighter">Denda
                                        Terbayar</span>
                                </div>
                            @else
                                <div class="flex flex-col items-center">
                                    <span class="text-teal-600 font-bold text-[10px] uppercase">Lunas</span>
                                    <span class="text-[8px] text-teal-400 font-medium italic">Tanpa Denda</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button
                                    onclick="showDetailModal({
                                id: {{ $data->id }},
                                user: { name: '{{ addslashes($data->user->name) }}' },
                                alat: { nama_alat: '{{ addslashes($data->alat->nama_alat) }}', harga_sewa: {{ $data->alat->harga_sewa ?? 0 }} },
                                jumlah: {{ $data->jumlah }},
                                {{ is_array($data->kondisi) ? implode(', ', $data->kondisi) : $data->kondisi }}
                                tgl_kembali: '{{ $data->tgl_kembali }}',
                                tgl_dikembalikan: '{{ $data->tgl_dikembalikan }}',
                                total_denda: {{ $data->total_denda ?? 0 }}
                            }, {{ $countBaik }}, {{ $countLecet }}, {{ $countRusak }}, {{ $countHilang }}, {{ $dendaTerlambat }}, {{ $dendaKondisi }})"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-cyan-50 text-cyan-600 hover:bg-cyan-500 hover:text-white transition-all duration-200 hover:scale-110 active:scale-95"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                @foreach ($peminjamans as $item)
                                    @if ($item->status_pembayaran == 'pending')
                                        <a href="{{ route('admin.verifikasi.denda', [$item->id, 'terima']) }}">Terima</a>
                                        <a href="{{ route('admin.verifikasi.denda', [$item->id, 'tolak']) }}">Tolak</a>
                                    @endif
                                @endforeach
                                <form action="{{ route('admin.peminjaman.destroy', $data->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus permanen riwayat ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-rose-50 hover:text-rose-500 transition-all duration-200 hover:scale-110 active:scale-95 border border-slate-200 hover:border-rose-200">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center text-slate-400 font-bold">
                            <i class="fas fa-box-open block text-4xl mb-3 opacity-30"></i>
                            Tidak ada data pengembalian ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-6 bg-slate-50 flex items-center justify-between border-t border-slate-200">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                Showing {{ $peminjamans->firstItem() ?? 0 }} to {{ $peminjamans->lastItem() ?? 0 }} of
                {{ $peminjamans->total() }} results
            </p>
            <div class="pagination-custom">
                {{ $peminjamans->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL PENGEMBALIAN -->
    <div id="modalDetail" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
            <div
                class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-t-2xl p-5 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-receipt"></i> Detail Pengembalian
                </h3>
                <button onclick="closeDetailModal()"
                    class="text-white text-xl hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5 space-y-3 max-h-96 overflow-y-auto" id="modalDetailContent"></div>
            <div class="p-5 pt-3 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
                <button onclick="closeDetailModal()"
                    class="w-full bg-gradient-to-r from-slate-200 to-slate-300 py-2.5 rounded-lg text-sm font-bold text-slate-700 hover:from-slate-300 hover:to-slate-400 transition-all duration-200 hover:shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(data, countBaik, countLecet, countRusak, countHilang, dendaTerlambat, dendaKondisi) {
            // Format tanggal (abaikan jam)
            const tglKembali = data.tgl_kembali ? new Date(data.tgl_kembali).toLocaleDateString('id-ID') : '-';
            const tglDikembalikan = data.tgl_dikembalikan ? new Date(data.tgl_dikembalikan).toLocaleDateString('id-ID') :
                '-';

            const totalDenda = data.total_denda || 0;

            let kondisiHtml = '';
            if (countBaik > 0 || countLecet > 0 || countRusak > 0 || countHilang > 0) {
                kondisiHtml = `
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 p-4 rounded-xl border border-cyan-200">
                    <p class="font-bold text-sm text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-buku text-cyan-600"></i> Detail Kondisi per Unit:
                    </p>
                    <div class="grid grid-cols-4 gap-2 text-center text-xs">
                        <div class="bg-white p-2 rounded-lg border border-cyan-200">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-teal-500 mb-1"></span>
                            <div class="font-bold text-slate-900">${countBaik}</div>
                            <div class="text-[10px] text-slate-500 font-medium">Baik</div>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-amber-200">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500 mb-1"></span>
                            <div class="font-bold text-slate-900">${countLecet}</div>
                            <div class="text-[10px] text-slate-500 font-medium">Lecet</div>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-orange-200">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-orange-500 mb-1"></span>
                            <div class="font-bold text-slate-900">${countRusak}</div>
                            <div class="text-[10px] text-slate-500 font-medium">Rusak</div>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-rose-200">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-rose-500 mb-1"></span>
                            <div class="font-bold text-slate-900">${countHilang}</div>
                            <div class="text-[10px] text-slate-500 font-medium">Hilang</div>
                        </div>
                    </div>
                </div>
            `;
            } else {
                kondisiHtml = `
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 p-4 rounded-xl border border-cyan-200">
                    <p class="font-bold text-sm text-slate-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-check-circle text-teal-600"></i> Kondisi:
                    </p>
                    <div class="text-center">
                        <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-bold uppercase bg-teal-100 text-teal-700">${data.kondisi || 'Baik'}</span>
                    </div>
                </div>
            `;
            }

            const html = `
            <div class="space-y-2 text-sm">
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-600 font-medium">Kode Pinjam:</span>
                    <span class="font-bold text-slate-900">PJM-${String(data.id).padStart(4, '0')}</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-600 font-medium">Peminjam:</span>
                    <span class="font-bold text-slate-900">${data.user.name}</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-600 font-medium">buku:</span>
                    <span class="font-bold text-slate-900">${data.alat.nama_alat}</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-600 font-medium">Jumlah:</span>
                    <span class="font-bold text-slate-900">${data.jumlah} Unit</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-600 font-medium">Batas Kembali:</span>
                    <span class="font-bold text-slate-900">${tglKembali}</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2 mb-2">
                    <span class="text-slate-600 font-medium">Tgl Dikembalikan:</span>
                    <span class="font-bold text-slate-900">${tglDikembalikan}</span>
                </div>
                ${kondisiHtml}
                <div class="bg-gradient-to-br from-rose-50 to-orange-50 p-4 rounded-xl border border-rose-200 mt-3">
                    <p class="font-bold text-slate-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-receipt text-rose-600"></i> Ringkasan Denda:
                    </p>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between pb-2 border-b border-rose-200">
                            <span class="text-slate-600">Denda Terlambat:</span>
                            <span class="font-bold text-rose-700">Rp ${dendaTerlambat.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between pb-2 border-b border-rose-200">
                            <span class="text-slate-600">Denda Kondisi:</span>
                            <span class="font-bold text-rose-700">Rp ${dendaKondisi.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between pt-2 text-sm">
                            <span class="font-bold text-slate-900">TOTAL DENDA:</span>
                            <span class="font-black text-rose-700">Rp ${totalDenda.toLocaleString('id-ID')}</span>
                        </div>
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

        // SweetAlert for success messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#10b981',
                color: '#ffffff'
            });
        @endif
    </script>
@endsection
