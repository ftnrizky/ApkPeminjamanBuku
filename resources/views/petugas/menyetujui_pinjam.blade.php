@extends('layouts.petugas')

@section('title', 'Menyetujui Peminjaman')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl shadow-lg shadow-teal-500/20 flex items-center gap-3 animate-pulse border border-teal-400">
    <i class="fas fa-check-circle text-xl"></i>
    <span class="font-bold text-sm uppercase tracking-wider">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl shadow-lg shadow-rose-500/20 flex items-center gap-3 border border-rose-400">
    <i class="fas fa-exclamation-circle text-xl"></i>
    <span class="font-bold text-sm uppercase tracking-wider">{{ session('error') }}</span>
</div>
@endif

<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Persetujuan <span class="text-cyan-600">Peminjaman</span></h1>
        <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">Validasi permintaan laptop dari peminjam</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 px-6 py-3 rounded-xl border border-cyan-200 shadow-sm flex items-center gap-3 hover:shadow-md transition-shadow duration-300">
            <div class="w-2.5 h-2.5 bg-cyan-500 rounded-full animate-pulse"></div>
            <span class="text-xs font-bold text-cyan-700 uppercase tracking-wider">{{ $peminjamans->count() }} Permintaan Menunggu</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-600 uppercase tracking-widest border-b-2 border-slate-200">
                    <th class="pb-4 px-4 w-32">Kode Pinjam</th>
                    <th class="pb-4 px-4">Data Peminjam</th>
                    <th class="pb-4 px-4 text-center">Laptop</th>
                    <th class="pb-4 px-4 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamans as $index => $item)
                    <tr class="group hover:bg-cyan-50/50 transition-colors duration-200 text-sm">
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900 text-xs bg-slate-100 px-3 py-2 rounded-lg">
                                PJM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md text-sm">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $item->user->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium mt-0.5">
                                        {{ $item->user->email }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-4 text-center">
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
                                {{ $item->alat->nama_alat }} <span class="text-cyan-600 font-black">({{ $item->jumlah }}x)</span>
                            </span>
                            <div class="text-[9px] text-cyan-600 font-semibold mt-1">
                                Rp {{ number_format($item->alat->harga_sewa, 0, ',', '.') }} / hari
                            </div>
                        </td>

                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="showDetail({{ $item->id }})" 
                                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-[10px] font-bold px-3 py-2 rounded-lg transition-all shadow-sm active:scale-95 hover:shadow-md"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" onclick="openApprovalModal({{ $item->id }}, '{{ $item->user->name }}', '{{ $item->alat->nama_alat }}', {{ $item->jumlah }}, {{ $item->alat->stok_tersedia }})"
                                        class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white text-[10px] font-bold px-4 py-2 rounded-lg transition-all shadow-md hover:shadow-lg active:scale-95 hover:scale-105 uppercase tracking-wider">
                                    <i class="fas fa-check-circle mr-1"></i> Setuju
                                </button>

                                <form action="{{ route('petugas.pinjam.proses', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak permintaan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="bg-rose-100 hover:bg-rose-600 text-rose-600 hover:text-white text-[10px] font-bold px-4 py-2 rounded-lg transition-all uppercase tracking-wider hover:shadow-lg shadow-sm">
                                        <i class="fas fa-times-circle mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <tr id="detail-{{ $item->id }}" class="hidden bg-slate-50/80 border-b border-slate-200">
                        <td colspan="4" class="px-6 py-5">
                            <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                            <i class="fas fa-info-circle text-cyan-600"></i> Tujuan Peminjaman
                                        </p>
                                        <div class="bg-gradient-to-br from-cyan-50 to-teal-50 p-4 rounded-lg border border-cyan-200">
                                            <p class="text-sm text-slate-700 font-medium leading-relaxed">
                                                {{ $item->tujuan ?? '(Tidak ada catatan tujuan)' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                            <i class="fas fa-calendar-alt text-teal-600"></i> Detail Waktu & Biaya
                                        </p>
                                        <div class="space-y-2.5">
                                            <div class="flex justify-between items-center text-xs bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                <span class="font-bold text-slate-600">Tanggal Pinjam:</span>
                                                <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->translatedFormat('d M Y') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                <span class="font-bold text-slate-600">Batas Kembali:</span>
                                                <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($item->tgl_kembali)->translatedFormat('d M Y') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                <span class="font-bold text-slate-600">Durasi:</span>
                                                <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->diffInDays($item->tgl_kembali) }} Hari</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                <span class="font-bold text-slate-600">Tgl Pengajuan:</span>
                                                <span class="font-semibold text-slate-900">{{ $item->created_at->translatedFormat('d M Y - H:i') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                <span class="font-bold text-slate-600">Qty:</span>
                                                <span class="font-semibold text-slate-900">{{ $item->jumlah }} Unit</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs bg-gradient-to-r from-cyan-50 to-teal-50 p-2.5 rounded-lg border border-cyan-200">
                                                <span class="font-bold text-cyan-700">Harga Sewa:</span>
                                                <span class="font-bold text-cyan-900">Rp {{ number_format($item->alat->harga_sewa, 0, ',', '.') }}/hari</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs bg-gradient-to-r from-teal-50 to-cyan-50 p-3 rounded-lg border-2 border-teal-300 font-bold">
                                                <span class="text-teal-700">Estimasi Total:</span>
                                                <span class="text-lg text-teal-900">Rp {{ number_format($item->alat->harga_sewa * $item->jumlah * \Carbon\Carbon::parse($item->tgl_pinjam)->diffInDays($item->tgl_kembali), 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-t border-slate-200 flex justify-end">
                                    <button onclick="hideDetail({{ $item->id }})" 
                                            class="text-[10px] font-bold text-slate-500 hover:text-slate-700 uppercase tracking-wider transition-colors">
                                        <i class="fas fa-chevron-up mr-1"></i> Tutup Detail
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-32 text-center px-4">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mb-6 border-2 border-teal-100">
                                    <i class="fas fa-inbox text-teal-500 text-3xl"></i>
                                </div>
                                <p class="text-slate-500 font-bold uppercase tracking-wider text-xs">Semua permintaan telah diproses</p>
                                <p class="text-slate-400 text-[11px] mt-1 font-medium">Tidak ada antrian peminjaman yang menunggu persetujuan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
        <div class="flex items-center gap-2">
            <i class="fas fa-shield-alt text-cyan-500"></i>
            <span>Validasi data peminjam sebelum menyetujui</span>
        </div>
        <div class="flex items-center gap-2">
            <i class="fas fa-clock text-slate-400"></i>
            <span>Sistem mencatat waktu real-time dengan akurat</span>
        </div>
    </div>
</div>

<!-- MODAL APPROVAL PEMINJAMAN -->
<div id="modal-approval" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-approval')"></div>
    <div class="bg-white rounded-2xl w-full max-w-md p-8 relative z-10 shadow-2xl">
        <!-- Header Icon -->
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-teal-100 text-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <h2 class="text-2xl font-900 text-slate-900">Setujui Peminjaman</h2>
            <p class="text-sm text-slate-500 font-600 mt-2">Peminjam: <span id="approval-member" class="text-teal-600 font-700"></span></p>
        </div>

        <div class="mb-6 p-4 bg-slate-50 rounded-xl">
            <p class="text-sm text-slate-600 mb-2"><strong>Laptop:</strong> <span id="approval-laptop"></span></p>
            <p class="text-sm text-slate-600 mb-2"><strong>Diajukan:</strong> <span id="approval-requested"></span> unit</p>
            <p class="text-sm text-slate-600"><strong>Stok Tersedia:</strong> <span id="approval-available"></span> unit</p>
        </div>

        <form id="form-approval" method="POST" action="">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="disetujui">

            <!-- Jumlah Disetujui -->
            <div class="mb-6">
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Jumlah yang Disetujui <span class="text-rose-600">*</span></label>
                <input type="number" id="jumlah-disetujui" name="jumlah_disetujui" min="1" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500 outline-none font-medium text-sm transition-all">
                <p class="text-xs text-slate-500 mt-1">Maksimal: <span id="max-jumlah"></span> unit</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="toggleModal('modal-approval')" class="flex-1 px-4 py-3 rounded-lg font-800 text-sm uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-[2] bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-4 py-3 rounded-lg font-800 text-sm uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all active:scale-95">
                    Setujui Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    function showDetail(id) {
        const detailRow = document.getElementById('detail-' + id);
        if (detailRow) {
            detailRow.classList.remove('hidden');
            detailRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function hideDetail(id) {
        const detailRow = document.getElementById('detail-' + id);
        if (detailRow) {
            detailRow.classList.add('hidden');
        }
    }

    function openApprovalModal(id, member, laptop, requested, available) {
        document.getElementById('form-approval').action = `/petugas/peminjaman/${id}/proses`;
        document.getElementById('approval-member').innerText = member;
        document.getElementById('approval-laptop').innerText = laptop;
        document.getElementById('approval-requested').innerText = requested;
        document.getElementById('approval-available').innerText = available;
        document.getElementById('jumlah-disetujui').value = requested;
        document.getElementById('jumlah-disetujui').max = Math.min(requested, available);
        document.getElementById('max-jumlah').innerText = Math.min(requested, available);
        toggleModal('modal-approval');
    }

    // Close modal on Escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
        }
    });
</script>
@endsection