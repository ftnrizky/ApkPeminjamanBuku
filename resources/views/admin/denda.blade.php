@extends('layouts.admin')

@section('title', 'Kelola Denda')

@section('content')

<div class="p-6">

    <!-- HEADER & FILTERS -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">💰 Manajemen Denda</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola dan pantau denda keterlambatan/kerusakan buku.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('admin.denda') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" 
                        class="text-xs border-slate-200 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Selesai</label>
                    <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" 
                        class="text-xs border-slate-200 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.denda.export_pdf', request()->query()) }}" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </form>
            <div class="h-8 w-px bg-slate-200 mx-1"></div>
            <div class="text-xs text-slate-500 font-medium">
                <i class="fas fa-database mr-1"></i> Total Data: {{ $data->count() }}
            </div>
        </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        ❌ {{ session('error') }}
    </div>
    @endif

    <!-- SUMMARY CARD -->
    <div class="grid md:grid-cols-3 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-slate-500 text-sm">Total Nominal Denda</p>
            <h2 class="text-2xl font-bold text-red-500 mt-2">
               Rp{{ number_format($total_nominal, 0, ',', '.') }}
            </h2>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-slate-500 text-sm">Kasus Belum Lunas</p>
            <h2 class="text-2xl font-bold text-yellow-500 mt-2">
                {{ $belum_lunas }}
            </h2>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-slate-500 text-sm">Kasus Sudah Lunas</p>
            <h2 class="text-2xl font-bold text-green-500 mt-2">
                {{ $sudah_lunas }}
            </h2>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white">
                    <tr>
                        <th class="p-4 text-center">No</th>
                        <th class="p-3 text-left">Nama Peminjam</th>
                        <th class="p-3 text-left">buku</th>
                        <th class="p-3 text-left">Denda</th>
                        <th class="p-3 text-left">Metode</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Bukti</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-50 transition" id="row-{{ $item->id }}">
                        <td class="p-4 text-center font-semibold text-slate-500">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-3 font-medium text-slate-700">{{ $item->user->name ?? '-' }}</td>
                        <td class="p-3 text-slate-600">{{ $item->alat->nama_alat ?? '-' }}</td>
                        <td class="p-3 font-bold text-red-500">Rp{{ number_format($item->total_denda, 0, ',', '.') }}</td>
                        <td class="p-3 text-slate-500 text-xs">{{ strtoupper($item->metode_bayar ?? '-') }}</td>
                        <td class="p-3">
                            @if($item->is_denda_lunas)
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">Lunas</span>
                            @elseif($item->status_pembayaran == 'pending')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">Pending</span>
                            @elseif($item->status_pembayaran == 'ditolak')
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">Ditolak</span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-semibold">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($item->bukti_bayar)
                                <button onclick="showImage('{{ asset('storage/'.$item->bukti_bayar) }}')"
                                    class="text-blue-500 hover:underline text-xs font-semibold">📷 Lihat</button>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1 flex-wrap">

                                {{-- Lunas via Cash (admin tandai manual) --}}
                                @if(!$item->is_denda_lunas)
                                    <button onclick="bayarCash({{ $item->id }}, this)"
                                        class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition font-semibold">
                                        💵 Cash
                                    </button>
                                @endif

                                {{-- Verifikasi QRIS: muncul hanya jika pending DAN ada bukti --}}
                                @if($item->status_pembayaran == 'pending' && $item->bukti_bayar)
                                    <button onclick="verifikasi({{ $item->id }}, 'terima', this)"
                                        class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                        ✔ Terima
                                    </button>
                                    <button onclick="bukaModalTolak({{ $item->id }}, this)"
                                        class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">
                                        ✖ Tolak
                                    </button>
                                @endif

                                {{-- Kirim Notif Reminder (hanya jika belum lunas) --}}
                                @if(!$item->is_denda_lunas)
                                    <button onclick="kirimNotif({{ $item->id }}, this)"
                                        class="bg-purple-500 text-white px-2 py-1 rounded text-xs hover:bg-purple-600 transition">
                                        🔔 Notif
                                    </button>
                                @endif

                                @if($item->is_denda_lunas)
                                    <span class="text-green-500 text-xs font-semibold">✅ Lunas</span>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-slate-400">Tidak ada data denda ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION -->
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $data->links() }}
        </div>
    </div>
</div>

<!-- MODAL IMAGE -->
<div id="imageModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50"
    onclick="closeModal()">
    <div class="bg-white p-4 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-red-500 text-lg font-bold">✖</button>
        <img id="previewImage" class="max-w-md max-h-[80vh] object-contain rounded">
    </div>
</div>

<!-- MODAL TOLAK (dengan input catatan) -->
<div id="modalTolak" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 space-y-4">
        <h3 class="font-bold text-gray-800 text-lg">✖ Tolak Pembayaran</h3>
        <p class="text-sm text-gray-500">Masukkan alasan penolakan (opsional, akan dikirim ke peminjam):</p>
        <textarea id="catatan-tolak" rows="3"
            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
            placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai"></textarea>
        <div class="flex gap-3">
            <button onclick="tutupModalTolak()"
                class="flex-1 border border-gray-300 rounded-xl py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                Batal
            </button>
            <button id="btn-konfirmasi-tolak"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white rounded-xl py-2 text-sm font-semibold transition">
                Konfirmasi Tolak
            </button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast"
    class="fixed bottom-6 right-6 z-50 hidden px-5 py-3 rounded-xl shadow-lg text-white text-sm font-semibold">
</div>

<script>
const CSRF = '{{ csrf_token() }}';

// ─── Modal Image ───────────────────────────────────
function showImage(src) {
    document.getElementById('previewImage').src = src;
    const m = document.getElementById('imageModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal() {
    const m = document.getElementById('imageModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}

// ─── Toast ─────────────────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-white text-sm font-semibold ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3500);
}

// ─── Bayar Cash (admin tandai manual) ──────────────
async function bayarCash(id, btn) {
    if (!confirm('Tandai denda ini sudah dibayar cash?')) return;
    btn.disabled = true;
    btn.textContent = '⏳';

    try {
        const res = await fetch(`/admin/bayar-denda/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ metode_bayar: 'cash' }),
        });
        const json = await res.json();
        if (json.success) {
            showToast('✅ Denda lunas (cash)');
            refreshData();
        } else {
            showToast(json.message || 'Gagal memproses', 'error');
            btn.disabled = false;
            btn.textContent = '💵 Cash';
        }
    } catch (e) {
        showToast('Error koneksi', 'error');
        btn.disabled = false;
        btn.textContent = '💵 Cash';
    }
}

// ─── Modal Tolak ───────────────────────────────────
let _tolakId   = null;
let _tolakBtn  = null;

function bukaModalTolak(id, btn) {
    _tolakId  = id;
    _tolakBtn = btn;
    document.getElementById('catatan-tolak').value = '';
    const m = document.getElementById('modalTolak');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function tutupModalTolak() {
    const m = document.getElementById('modalTolak');
    m.classList.add('hidden');
    m.classList.remove('flex');
    _tolakId = null;
    _tolakBtn = null;
}

document.getElementById('btn-konfirmasi-tolak').addEventListener('click', async function () {
    if (!_tolakId) return;
    const catatan = document.getElementById('catatan-tolak').value.trim();
    this.disabled = true;
    this.textContent = '⏳';

    try {
        const res = await fetch(`/admin/verifikasi-denda/${_tolakId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ aksi: 'tolak', catatan_penolakan: catatan }),
        });
        const json = await res.json();
        if (json.success) {
            showToast('❌ Pembayaran ditolak');
            tutupModalTolak();
            refreshData();
        } else {
            showToast('Gagal memproses', 'error');
        }
    } catch (e) {
        showToast('Error koneksi', 'error');
    }

    this.disabled = false;
    this.textContent = 'Konfirmasi Tolak';
});

// ─── Verifikasi Terima ─────────────────────────────
async function verifikasi(id, aksi, btn) {
    if (aksi === 'tolak') { bukaModalTolak(id, btn); return; }
    if (!confirm('Terima pembayaran ini?')) return;

    btn.disabled = true;
    btn.textContent = '⏳';

    try {
        const res = await fetch(`/admin/verifikasi-denda/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ aksi }),
        });
        const json = await res.json();
        if (json.success) {
            showToast('✅ Pembayaran diterima');
            refreshData();
        } else {
            showToast('Gagal memproses', 'error');
            btn.disabled = false;
            btn.textContent = '✔ Terima';
        }
    } catch (e) {
        showToast('Error koneksi', 'error');
        btn.disabled = false;
        btn.textContent = '✔ Terima';
    }
}

// ─── Kirim Notifikasi Reminder ─────────────────────
async function kirimNotif(id, btn) {
    if (!confirm('Kirim notifikasi reminder ke peminjam?')) return;
    btn.disabled = true;
    btn.textContent = '⏳';

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
            showToast('🔔 Notifikasi berhasil dikirim');
        } else {
            showToast(json.message || 'Gagal kirim notifikasi', 'error');
        }
    } catch (e) {
        showToast('Error koneksi', 'error');
    }

    btn.disabled = false;
    btn.textContent = '🔔 Notif';
}

// ─── Refresh / Polling ─────────────────────────────
// Fitur refresh otomatis telah dihapus untuk menampilkan data secara statis.
function refreshData() {
    window.location.reload();
}
</script>

@endsection