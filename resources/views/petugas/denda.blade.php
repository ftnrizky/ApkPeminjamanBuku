@extends('layouts.petugas')

@section('content')
    <div class="space-y-6">

        <section class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Manajemen Denda</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Kelola Denda</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Pantau pembayaran denda, verifikasi bukti transfer, dan konfirmasi pembayaran cash dari peminjam.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 ring-1 ring-rose-100">
                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                {{ $dendas->count() }} denda belum lunas
            </div>
        </section>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm">
                <div class="flex items-start gap-2">
                    <i class="fas fa-circle-check mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 shadow-sm">
                <div class="flex items-start gap-2">
                    <i class="fas fa-circle-xmark mt-0.5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Daftar Denda</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">Pembayaran Belum Lunas</h2>
                </div>
                <div class="rounded-xl bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 ring-1 ring-slate-200">
                    Total data: <span class="font-semibold text-slate-900">{{ $dendas->count() }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">No</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Peminjam</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Alat</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Total Denda</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Metode</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Bukti</th>
                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 min-w-[240px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($dendas as $denda)
                            @php
                                $status = $denda->status_pembayaran;
                                $hasBukti = (bool) $denda->bukti_bayar;
                                $isCashWait = $status === 'menunggu_cash';
                                $isPending = $status === 'pending' && $hasBukti;
                                $isDitolak = $status === 'ditolak';
                            @endphp
                            <tr class="transition duration-200 hover:bg-blue-50/40 {{ $isCashWait ? 'bg-amber-50/60' : '' }}">
                                <td class="px-4 py-4 text-center font-semibold text-slate-500">{{ $loop->iteration }}</td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-sm font-semibold text-blue-700">
                                            {{ strtoupper(substr($denda->peminjaman->user->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $denda->peminjaman->user->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">ID Denda: {{ $denda->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-slate-700">
                                    <div class="font-medium">{{ $denda->peminjaman->alat->nama_alat ?? '-' }}</div>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="text-base font-bold text-rose-600">
                                        Rp{{ number_format($denda->total_denda, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $denda->metode_bayar ? strtoupper($denda->metode_bayar) : '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    @if ($isCashWait)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                            <i class="fas fa-clock text-[10px]"></i>
                                            Niat Bayar Cash
                                        </span>
                                    @elseif($isPending)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                            <i class="fas fa-hourglass-half text-[10px]"></i>
                                            Verifikasi Bukti
                                        </span>
                                    @elseif($isDitolak)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                            <i class="fas fa-circle-xmark text-[10px]"></i>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                            <i class="fas fa-wallet text-[10px]"></i>
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-center">
                                    @if ($hasBukti)
                                        <button onclick="showImage('{{ asset('storage/' . $denda->bukti_bayar) }}')"
                                            class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition duration-200 hover:bg-blue-100">
                                            <i class="fas fa-image"></i>
                                            Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400">Belum ada</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center justify-center gap-2">

                                        @if ($isCashWait)
                                            <button onclick="konfirmasiCash({{ $denda->id }}, this)"
                                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-400 hover:shadow-md">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Konfirmasi Cash
                                            </button>
                                        @elseif(!$hasBukti && $status !== 'diterima')
                                            <button onclick="konfirmasiCash({{ $denda->id }}, this)"
                                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-400 hover:shadow-md">
                                                <i class="fas fa-cash-register"></i>
                                                Catat Cash
                                            </button>
                                        @endif

                                        @if ($isPending)
                                            <button onclick="bukaModalTolak({{ $denda->id }})"
                                                class="inline-flex items-center gap-2 rounded-xl bg-blue-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                                                <i class="fas fa-check"></i>
                                                Terima
                                            </button>
                                            <button onclick="bukaModalTolak({{ $denda->id }}, true)"
                                                class="inline-flex items-center gap-2 rounded-xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-rose-400 hover:shadow-md">
                                                <i class="fas fa-xmark"></i>
                                                Tolak
                                            </button>
                                        @endif

                                        <button onclick="kirimNotif({{ $denda->id }}, this)"
                                            class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition duration-200 hover:-translate-y-0.5 hover:bg-amber-100">
                                            <i class="fas fa-bell"></i>
                                            Ingatkan
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                                            <i class="fas fa-check-double text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Semua denda sudah lunas</h3>
                                            <p class="mt-1 text-sm text-slate-500">Belum ada pembayaran denda yang perlu ditindaklanjuti.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm"
        onclick="closeImageModal()">
        <div class="relative w-full max-w-3xl rounded-3xl border border-slate-200 bg-white p-4 shadow-2xl"
            onclick="event.stopPropagation()">
            <button onclick="closeImageModal()"
                class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition duration-200 hover:bg-rose-50 hover:text-rose-600">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="overflow-hidden rounded-2xl bg-slate-100">
                <img id="previewImage" class="max-h-[80vh] w-full object-contain rounded-2xl" alt="Bukti pembayaran">
            </div>
        </div>
    </div>

    <div id="modalVerifikasi" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div id="modal-verif-header" class="rounded-t-3xl px-6 py-5 text-white">
                <h3 id="modal-verif-title" class="text-base font-bold"></h3>
            </div>
            <div class="space-y-5 p-6">
                <p id="modal-verif-desc" class="text-sm leading-6 text-slate-600"></p>

                <div id="wrap-catatan" class="hidden">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Alasan penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="catatan-penolakan" rows="3"
                        class="w-full resize-none rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition duration-200 focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                        placeholder="Contoh: Nominal tidak sesuai, foto blur, dll."></textarea>
                </div>

                <div class="flex gap-3">
                    <button onclick="tutupModalVerifikasi()"
                        class="flex-1 rounded-xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-600 transition duration-200 hover:bg-slate-50">
                        Batal
                    </button>
                    <button id="btn-aksi-verif"
                        class="flex-1 rounded-xl py-3 text-sm font-semibold text-white transition duration-200">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="fixed bottom-6 right-6 z-[70] hidden rounded-2xl px-5 py-3 text-sm font-semibold text-white shadow-lg transition-all"></div>

    <script>
        const CSRF = '{{ csrf_token() }}';

        function showImage(src) {
            document.getElementById('previewImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = `fixed bottom-6 right-6 z-[70] rounded-2xl px-5 py-3 text-sm font-semibold text-white shadow-lg ${
                type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'
            }`;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3500);
        }

        async function konfirmasiCash(dendaId, btn) {
            if (!confirm('Konfirmasi denda ini sudah dibayar cash oleh peminjam?')) return;

            btn.disabled = true;
            const oriText = btn.textContent;
            btn.textContent = '⏳';

            try {
                const res = await fetch(`/petugas/denda/${dendaId}/cash`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                });
                const json = await res.json();

                if (json.success) {
                    showToast('✅ Denda berhasil dikonfirmasi lunas!');
                    btn.closest('tr').style.transition = 'opacity 0.4s';
                    btn.closest('tr').style.opacity = '0';
                    setTimeout(() => btn.closest('tr').remove(), 400);
                } else {
                    showToast(json.message ?? 'Terjadi kesalahan', 'error');
                    btn.disabled = false;
                    btn.textContent = oriText;
                }
            } catch (err) {
                showToast('Gagal terhubung ke server', 'error');
                btn.disabled = false;
                btn.textContent = oriText;
            }
        }

        let _verifDendaId = null;
        let _verifAksi = null;

        function bukaModalVerifikasi(dendaId) {
            _verifDendaId = dendaId;
            _verifAksi = 'terima';

            const header = document.getElementById('modal-verif-header');
            header.className = 'rounded-t-3xl px-6 py-5 text-white bg-blue-600';
            document.getElementById('modal-verif-title').textContent = 'Konfirmasi Terima Bukti';
            document.getElementById('modal-verif-desc').textContent =
                'Apakah Anda yakin bukti pembayaran ini valid dan ingin menandai denda sebagai lunas?';
            document.getElementById('wrap-catatan').classList.add('hidden');

            const btn = document.getElementById('btn-aksi-verif');
            btn.textContent = 'Ya, Terima';
            btn.className = 'flex-1 rounded-xl py-3 text-sm font-semibold text-white transition duration-200 bg-blue-600 hover:bg-blue-700';
            btn.onclick = () => submitVerifikasi();

            tampilModalVerif();
        }

        function bukaModalTolak(dendaId, isTolak = false) {
            _verifDendaId = dendaId;

            if (!isTolak) {
                bukaModalVerifikasi(dendaId);
                return;
            }

            _verifAksi = 'tolak';

            const header = document.getElementById('modal-verif-header');
            header.className = 'rounded-t-3xl px-6 py-5 text-white bg-rose-600';
            document.getElementById('modal-verif-title').textContent = 'Tolak Bukti Pembayaran';
            document.getElementById('modal-verif-desc').textContent =
                'Berikan alasan penolakan agar peminjam dapat memperbaikinya.';
            document.getElementById('wrap-catatan').classList.remove('hidden');
            document.getElementById('catatan-penolakan').value = '';

            const btn = document.getElementById('btn-aksi-verif');
            btn.textContent = 'Tolak Bukti';
            btn.className = 'flex-1 rounded-xl py-3 text-sm font-semibold text-white transition duration-200 bg-rose-600 hover:bg-rose-700';
            btn.onclick = () => submitVerifikasi();

            tampilModalVerif();
        }

        function tampilModalVerif() {
            document.getElementById('modalVerifikasi').classList.remove('hidden');
            document.getElementById('modalVerifikasi').classList.add('flex');
        }

        function tutupModalVerifikasi() {
            document.getElementById('modalVerifikasi').classList.add('hidden');
            document.getElementById('modalVerifikasi').classList.remove('flex');
            _verifDendaId = null;
            _verifAksi = null;
        }

        async function submitVerifikasi() {
            const catatan = document.getElementById('catatan-penolakan').value.trim();

            if (_verifAksi === 'tolak' && catatan.length < 5) {
                alert('⚠️ Harap isi alasan penolakan (minimal 5 karakter).');
                document.getElementById('catatan-penolakan').focus();
                return;
            }

            const btn = document.getElementById('btn-aksi-verif');
            btn.disabled = true;
            btn.textContent = '⏳ Memproses...';

            const body = new FormData();
            body.append('aksi', _verifAksi);
            if (_verifAksi === 'tolak') body.append('catatan_penolakan', catatan);

            try {
                const res = await fetch(`/petugas/denda/${_verifDendaId}/verifikasi`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body,
                });
                const json = await res.json();

                if (json.success) {
                    showToast(json.message ?? (_verifAksi === 'terima' ? '✅ Bukti diterima!' : '❌ Bukti ditolak!'));
                    tutupModalVerifikasi();
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast(json.message ?? 'Terjadi kesalahan', 'error');
                    btn.disabled = false;
                    btn.textContent = _verifAksi === 'terima' ? 'Ya, Terima' : 'Tolak Bukti';
                }
            } catch {
                showToast('Gagal terhubung ke server', 'error');
                btn.disabled = false;
                btn.textContent = _verifAksi === 'terima' ? 'Ya, Terima' : 'Tolak Bukti';
            }
        }

        async function kirimNotif(dendaId, btn) {
            btn.disabled = true;
            const ori = btn.textContent;
            btn.textContent = '⏳';

            try {
                const res = await fetch(`/petugas/denda/${dendaId}/notif`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                });
                const json = await res.json();
                showToast(json.message ?? 'Notifikasi terkirim!');
            } catch {
                showToast('Gagal kirim notifikasi', 'error');
            }

            btn.disabled = false;
            btn.textContent = ori;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                tutupModalVerifikasi();
            }
        });
    </script>
@endsection
