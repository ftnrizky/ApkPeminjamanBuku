@extends('layouts.admin')

@section('title', 'Monitoring Denda')

@section('content')

<div class="p-6">

    <!-- HEADER & FILTERS -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">📊 Monitoring <span class="text-indigo-600">Denda</span></h1>
            <p class="mt-1 text-sm font-medium leading-relaxed text-slate-500">Pantau status denda dan kelola pelaporan resmi sistem perpustakaan.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('admin.denda') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" 
                        class="rounded-xl border-slate-200 bg-slate-50 text-xs font-semibold text-slate-700 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Selesai</label>
                    <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" 
                        class="rounded-xl border-slate-200 bg-slate-50 text-xs font-semibold text-slate-700 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <button type="submit" class="flex h-[38px] items-center gap-2 rounded-xl bg-slate-900 px-4 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-slate-800">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.denda.export_pdf', request()->query()) }}" class="flex h-[38px] items-center gap-2 rounded-xl bg-indigo-600 px-4 text-xs font-bold uppercase tracking-widest text-white shadow-lg shadow-indigo-100 transition hover:bg-indigo-700">
                    <i class="fas fa-file-pdf"></i> Laporan
                </a>
            </form>
            <div class="h-8 w-px bg-slate-200 mx-1"></div>
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                <i class="fas fa-info-circle text-indigo-500"></i> Monitoring
            </div>
        </div>
    </div>

    <!-- SUMMARY CARD -->
    <div class="grid md:grid-cols-3 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition">
            <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Total Nominal Terdata</p>
            <h2 class="text-2xl font-black text-slate-900 mt-2">
               Rp{{ number_format($total_nominal, 0, ',', '.') }}
            </h2>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition">
            <p class="text-rose-500 text-[11px] font-bold uppercase tracking-wider">Belum Dilunasi</p>
            <h2 class="text-2xl font-black text-rose-600 mt-2">
                {{ $belum_lunas }} <span class="text-xs font-medium text-slate-400 tracking-normal">Kasus</span>
            </h2>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition">
            <p class="text-emerald-500 text-[11px] font-bold uppercase tracking-wider">Sudah Dilunasi</p>
            <h2 class="text-2xl font-black text-emerald-600 mt-2">
                {{ $sudah_lunas }} <span class="text-xs font-medium text-slate-400 tracking-normal">Kasus</span>
            </h2>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[10px] font-bold tracking-widest">
                        <th class="p-4 text-center w-16">No</th>
                        <th class="p-4 text-left">Peminjam</th>
                        <th class="p-4 text-left">Detail buku</th>
                        <th class="p-4 text-left">Nominal Denda</th>
                        <th class="p-4 text-left">Status Terakhir</th>
                        <th class="p-4 text-center">Bukti</th>
                        <th class="p-4 text-center">Aksi Monitoring</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 text-center font-bold text-slate-400">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-900">{{ $item->user->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 italic">ID: #{{ $item->user_id }}</p>
                        </td>
                        <td class="p-4 text-slate-600">
                            <p class="font-medium">{{ $item->alat->nama_alat ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $item->metode_bayar ?? 'Belum Pilih Metode' }}</p>
                        </td>
                        <td class="p-4 font-black text-slate-900">
                            Rp{{ number_format($item->total_denda, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            @if($item->is_denda_lunas)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] rounded-full bg-emerald-100 text-emerald-700 font-bold uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] rounded-full bg-rose-100 text-rose-700 font-bold uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($item->bukti_bayar)
                                <button onclick="showImage('{{ asset('storage/'.$item->bukti_bayar) }}')"
                                    class="text-cyan-600 hover:text-cyan-700 font-bold text-xs flex items-center justify-center gap-1 mx-auto">
                                    <i class="fas fa-image"></i> Cek Bukti
                                </button>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Hanya Tombol Notif Reminder & Detail --}}
                                <button onclick="kirimNotif({{ $item->id }}, this)"
                                    class="group relative bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white p-2 rounded-xl transition-all duration-300 border border-amber-100 shadow-sm"
                                    title="Kirim Pengingat">
                                    <i class="fas fa-bell"></i>
                                </button>

                                <button onclick="showImage('{{ asset('storage/'.$item->bukti_bayar) }}')"
                                    class="bg-slate-50 text-slate-600 hover:bg-slate-200 p-2 rounded-xl transition border border-slate-200 shadow-sm"
                                    title="Detail Info">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20">
                            <i class="fas fa-file-invoice-dollar text-slate-200 text-5xl mb-4 block"></i>
                            <p class="text-slate-400 font-medium">Tidak ada data denda yang terdeteksi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION -->
        <div class="p-6 border-t border-slate-100 bg-slate-50">
            {{ $data->links() }}
        </div>
    </div>
</div>

<!-- MODAL IMAGE -->
<div id="imageModal" class="fixed inset-0 bg-slate-900/80 hidden items-center justify-center z-[100] backdrop-blur-sm p-4"
    onclick="closeModal()">
    <div class="bg-white p-2 rounded-3xl shadow-2xl relative max-w-lg w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center p-4 border-b border-slate-100">
            <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest">Detail Bukti Pembayaran</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times-circle text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="previewImage" class="w-full h-auto object-contain rounded-2xl shadow-inner bg-slate-50" style="max-height: 70vh;">
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast"
    class="fixed bottom-10 right-10 z-[100] translate-y-20 opacity-0 transition-all duration-500 px-6 py-4 rounded-2xl shadow-2xl text-white text-sm font-bold flex items-center gap-3">
</div>

<script>
const CSRF = '{{ csrf_token() }}';

// ─── Modal Utils ───────────────────────────────────
function showImage(src) {
    if(!src || src.includes('null')) {
        showToast('Bukti pembayaran belum diunggah oleh peminjam.', 'error');
        return;
    }
    const modal = document.getElementById('imageModal');
    const content = document.getElementById('modalContent');
    document.getElementById('previewImage').src = src;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    const content = document.getElementById('modalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

// ─── Toast System ───────────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle text-xl"></i>' : '<i class="fas fa-exclamation-circle text-xl"></i>') + `<span>${message}</span>`;
    
    toast.className = `fixed bottom-10 right-10 z-[100] px-6 py-4 rounded-2xl shadow-2xl text-white text-sm font-bold flex items-center gap-3 transition-all duration-500 ${
        type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'
    }`;
    
    toast.classList.remove('translate-y-20', 'opacity-0');
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 4000);
}

// ─── Monitoring Action: Kirim Peringatan ────────────
async function kirimNotif(id, btn) {
    if (!confirm('Kirim pengingat denda kepada peminjam?')) return;
    
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-amber-600"></i>';

    try {
        const res = await fetch(`/admin/denda/${id}/kirim-notif`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
        });
        const json = await res.json();
        if (json.success) {
            showToast('✅ Peringatan denda berhasil dikirim');
        } else {
            showToast(json.message || 'Gagal mengirim peringatan', 'error');
        }
    } catch (e) {
        showToast('Kesalahan jaringan, coba lagi', 'error');
    }

    btn.disabled = false;
    btn.innerHTML = originalContent;
}

// Global closeModal on Esc
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
});
</script>

@endsection
