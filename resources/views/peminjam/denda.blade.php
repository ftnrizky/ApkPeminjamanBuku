@extends('layouts.peminjam')

@section('content')
    <div
        class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.10),_transparent_30%),linear-gradient(180deg,#f8fbff_0%,#f3f6fb_45%,#eef2f7_100%)] py-6 sm:py-10 px-4">
        <div class="max-w-6xl mx-auto space-y-6">

            {{-- ── HEADER ── --}}
            <div
                class="relative overflow-hidden rounded-[28px] border border-white/70 bg-white/90 shadow-[0_24px_80px_-32px_rgba(15,23,42,0.35)] backdrop-blur">
                <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-indigo-50 via-sky-50 to-transparent">
                </div>
                <div class="relative p-6 sm:p-8">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-700">
                        Payment Center
                    </div>
                    <div class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">Pembayaran Denda
                            </h1>
                            <p class="mt-2 max-w-xl text-sm sm:text-base leading-relaxed text-slate-500">
                                Selesaikan pembayaran dengan alur yang cepat, aman, dan mudah dipantau langsung dari halaman
                                ini.
                            </p>
                        </div>

                        @php
                            $totalBelumLunas = $dendas->filter(fn($d) => !$d->is_denda_lunas)->sum('total_denda');
                        @endphp

                        <div class="grid grid-cols-2 gap-3 sm:min-w-[320px]">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Total Denda
                                </p>
                                <p class="mt-2 text-xl sm:text-2xl font-semibold text-slate-900">{{ $dendas->count() }}</p>
                                <p class="mt-1 text-xs text-slate-500">Tagihan terdaftar</p>
                            </div>
                            <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-rose-400">Belum Lunas
                                </p>
                                <p class="mt-2 text-base sm:text-lg font-semibold text-rose-600">
                                    Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs text-rose-500">Perlu diselesaikan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── FLASH MESSAGES ── --}}
            @foreach ([
            'success' => ['border-emerald-200', 'bg-emerald-50/90', 'text-emerald-700', 'Success'],
            'error' => ['border-rose-200', 'bg-rose-50/90', 'text-rose-700', 'Error'],
            'info' => ['border-sky-200', 'bg-sky-50/90', 'text-sky-700', 'Info'],
        ] as $key => $s)
                @if (session($key))
                    <div
                        class="rounded-2xl border {{ $s[0] }} {{ $s[1] }} px-4 py-3 shadow-sm backdrop-blur">
                        <div class="flex items-start gap-3">
                            <div
                                class="mt-0.5 rounded-full bg-white/80 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em] {{ $s[2] }}">
                                {{ $s[3] }}
                            </div>
                            <p class="text-sm leading-relaxed {{ $s[2] }}">{{ session($key) }}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">

                {{-- ── KIRI: LIST DENDA ── --}}
                <div
                    class="rounded-[28px] border border-white/80 bg-white/95 shadow-[0_20px_70px_-35px_rgba(15,23,42,0.32)] backdrop-blur">
                    <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:px-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Daftar Denda Saya</h2>
                                <p class="mt-1 text-sm text-slate-500">Pilih tagihan yang ingin dibayar dan lanjutkan ke
                                    checkout.</p>
                            </div>
                            <div
                                class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ $dendas->count() }} tagihan
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6">
                        @forelse ($dendas as $denda)
                            @php
                                $isLunas = $denda->is_denda_lunas;
                                $status = $denda->status_pembayaran; // null | pending | menunggu_cash | diterima | ditolak
                                $isPending = $status === 'pending';
                                $isDitolak = $status === 'ditolak';
                                $isCashWait = $status === 'menunggu_cash';

                                // Tombol bayar muncul jika:
                                // - belum lunas
                                // - BUKAN sedang menunggu verifikasi bukti (pending)
                                // - BUKAN sedang menunggu konfirmasi cash (menunggu_cash)
                                $canPay = !$isLunas && !$isPending && !$isCashWait;
                            @endphp

                            <div
                                class="group mb-4 overflow-hidden rounded-[24px] border bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_40px_-28px_rgba(15,23,42,0.35)]
                        @if ($isLunas) border-emerald-200/80 bg-emerald-50/70
                        @elseif($isDitolak) border-rose-200/80 bg-rose-50/70
                        @elseif($isCashWait) border-amber-200/80 bg-amber-50/80
                        @elseif($isPending) border-sky-200/80 bg-sky-50/70
                        @else border-slate-200/80 @endif">

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="space-y-3 flex-1">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p
                                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                    Kode Tagihan</p>
                                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                                    DENDA-{{ $denda->id }}</p>
                                            </div>

                                            @if ($isLunas)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold text-emerald-700">
                                                    Lunas
                                                </span>
                                            @elseif($isPending)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-[11px] font-semibold text-sky-700">
                                                    Menunggu Verifikasi
                                                </span>
                                            @elseif($isCashWait)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-700">
                                                    Menunggu Konfirmasi Cash
                                                </span>
                                            @elseif($isDitolak)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-[11px] font-semibold text-rose-700">
                                                    Ditolak
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1 text-[11px] font-semibold text-white">
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid gap-3 sm:grid-cols-3">
                                            <div class="rounded-2xl border border-white/70 bg-white/85 px-4 py-3">
                                                <p
                                                    class="text-[11px] font-medium uppercase tracking-[0.16em] text-slate-400">
                                                    Alat</p>
                                                <p class="mt-2 text-sm font-medium text-slate-700">
                                                    {{ $denda->peminjaman->alat->nama_alat ?? '-' }}</p>
                                            </div>
                                            <div class="rounded-2xl border border-white/70 bg-white/85 px-4 py-3">
                                                <p
                                                    class="text-[11px] font-medium uppercase tracking-[0.16em] text-slate-400">
                                                    Total Denda</p>
                                                <p class="mt-2 text-base font-semibold text-rose-600">
                                                    Rp {{ number_format($denda->total_denda, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="rounded-2xl border border-white/70 bg-white/85 px-4 py-3">
                                                <p
                                                    class="text-[11px] font-medium uppercase tracking-[0.16em] text-slate-400">
                                                    Metode</p>
                                                <p class="mt-2 text-sm font-medium text-slate-700">
                                                    {{ $denda->metode_bayar ? strtoupper($denda->metode_bayar) : 'Belum dipilih' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($canPay)
                                        <div class="sm:pl-2">
                                            <button
                                                onclick="bukaModal(
                                        {{ $denda->id }},
                                        '{{ addslashes($denda->peminjaman->alat->nama_alat ?? '-') }}',
                                        {{ $denda->total_denda }},
                                        '{{ $isDitolak ? 'ditolak' : '' }}'
                                    )"
                                                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 px-4 py-3 text-sm font-semibold text-white shadow-[0_16px_30px_-16px_rgba(37,99,235,0.8)] transition hover:brightness-105 active:scale-[0.99] sm:min-w-[180px]">
                                                Bayar Sekarang
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                {{-- Catatan penolakan --}}
                                @if ($isDitolak && $denda->catatan_penolakan)
                                    <div
                                        class="mt-4 rounded-2xl border border-rose-200 bg-white/80 px-4 py-3 text-xs leading-relaxed text-rose-700">
                                        <strong class="font-semibold">Alasan ditolak:</strong>
                                        {{ $denda->catatan_penolakan }}
                                    </div>
                                @endif

                                {{-- Info pending bukti --}}
                                @if ($isPending && $denda->metode_bayar)
                                    <div
                                        class="mt-4 rounded-2xl border border-sky-200 bg-white/80 px-4 py-3 text-xs leading-relaxed text-sky-700">
                                        Bukti pembayaran via <strong>{{ strtoupper($denda->metode_bayar) }}</strong> sedang
                                        diverifikasi petugas.
                                    </div>
                                @endif

                                {{-- Info menunggu konfirmasi cash --}}
                                @if ($isCashWait)
                                    <div
                                        class="mt-4 rounded-2xl border border-amber-200 bg-white/80 px-4 py-3 text-xs leading-relaxed text-amber-700">
                                        Petugas akan mengkonfirmasi setelah menerima uang cash dari Anda.
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div
                                class="rounded-[24px] border border-dashed border-slate-200 bg-slate-50 px-6 py-14 text-center">
                                <p class="text-base font-semibold text-slate-700">Tidak ada denda</p>
                                <p class="mt-2 text-sm text-slate-500">Semua tagihan Anda sudah terselesaikan dengan baik.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── KANAN: INFO ── --}}
                <div class="space-y-6">
                    <div
                        class="overflow-hidden rounded-[28px] border border-slate-200/80 bg-white/95 shadow-[0_20px_70px_-35px_rgba(15,23,42,0.28)]">
                        <div
                            class="border-b border-slate-100 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-5 text-white">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-300">Checkout Guide
                            </p>
                            <h2 class="mt-2 text-lg font-semibold">Cara Pembayaran</h2>
                        </div>
                        <div class="space-y-4 px-6 py-6">
                            <div class="flex gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                    1</div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Pilih tagihan</p>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Tekan tombol bayar pada denda
                                        yang ingin Anda selesaikan.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                    2</div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Tentukan metode</p>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Pilih pembayaran cash atau QRIS
                                        sesuai kebutuhan Anda.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                    3</div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Upload bukti transfer</p>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Jika membayar via QRIS, unggah
                                        bukti agar segera diverifikasi petugas.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                    4</div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Tunggu konfirmasi</p>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Status pembayaran akan diperbarui
                                        setelah proses verifikasi selesai.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-[28px] border border-amber-200/80 bg-gradient-to-br from-amber-50 to-orange-50 px-6 py-5 shadow-[0_18px_40px_-30px_rgba(245,158,11,0.55)]">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-600">Perhatian</p>
                        <p class="mt-2 text-sm font-semibold text-amber-900">Akun dibatasi selama denda belum lunas.</p>
                        <p class="mt-2 text-sm leading-relaxed text-amber-700">
                            Segera selesaikan tagihan agar Anda bisa melakukan peminjaman kembali tanpa hambatan.
                        </p>
                    </div>

                    @if ($totalBelumLunas > 0)
                        <div
                            class="rounded-[28px] border border-rose-200/80 bg-white/95 p-6 shadow-[0_20px_70px_-35px_rgba(244,63,94,0.4)]">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-rose-400">Outstanding Amount
                            </p>
                            <p class="mt-3 text-3xl sm:text-4xl font-semibold tracking-tight text-rose-600">
                                Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}
                            </p>
                            <p class="mt-2 text-sm text-slate-500">Total keseluruhan denda yang masih aktif saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- =================================================================
     MODAL BAYAR DENDA
     ================================================================= --}}
    <div id="modalBayar"
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-3 sm:p-4"
        role="dialog" aria-modal="true" aria-labelledby="modal-title">

        <div
            class="w-full max-w-4xl overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_35px_120px_-40px_rgba(15,23,42,0.55)]">

            {{-- Header --}}
            <div
                class="relative overflow-hidden bg-gradient-to-r from-slate-950 via-indigo-950 to-blue-900 px-3 py-3 text-white sm:px-5 sm:py-4">
                <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-cyan-400/10 to-transparent"></div>
                <button onclick="tutupModal()" aria-label="Tutup"
                    class="absolute right-3 top-3 flex h-7 w-7 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white/80 transition hover:bg-white/15 hover:text-white text-sm">✕</button>

                <div class="relative pr-10">
                    <p class="text-[9px] font-semibold uppercase tracking-[0.22em] text-blue-200">Secure Checkout</p>
                    <h2 id="modal-title" class="mt-1 text-lg sm:text-xl font-semibold tracking-tight">Pembayaran Denda
                    </h2>
                    <div
                        class="mt-2 inline-flex items-center rounded-full border border-white/15 bg-white/10 px-2 py-0.5 text-[10px] text-blue-100">
                        <span id="modal-kode"></span>
                    </div>
                </div>
            </div>

            <div class="grid gap-0 lg:grid-cols-[240px_minmax(0,1fr)]">
                <div class="border-b border-slate-100 bg-slate-50/80 p-2 sm:p-3 lg:border-b-0 lg:border-r">
                    {{-- Ringkasan --}}
                    <div class="rounded-[16px] border border-slate-200 bg-white p-2 shadow-sm">
                        <p class="text-[8px] font-semibold uppercase tracking-[0.18em] text-slate-400">Ringkasan Tagihan
                        </p>

                        <div class="mt-2 rounded-lg bg-slate-50 px-2 py-1.5">
                            <p class="text-[8px] font-medium uppercase tracking-[0.14em] text-slate-400">Alat</p>
                            <p id="modal-alat" class="mt-0.5 text-[10px] font-semibold leading-relaxed text-slate-800">
                            </p>
                        </div>

                        <div
                            class="mt-2 overflow-hidden rounded-xl bg-gradient-to-br from-rose-500 via-pink-500 to-orange-400 px-2 py-2 text-white shadow-[0_18px_40px_-25px_rgba(244,63,94,0.7)]">
                            <p class="text-[8px] font-semibold uppercase tracking-[0.16em] text-white/80">Total Denda</p>
                            <p id="modal-total" class="mt-0.5 text-base sm:text-lg font-semibold tracking-tight"></p>
                            <p class="mt-0.5 text-[9px] text-white/80">Pastikan nominal yang dibayarkan sesuai tagihan.</p>
                        </div>
                    </div>

                    {{-- Alert jika sebelumnya ditolak --}}
                    <div id="info-ditolak"
                        class="hidden mt-2 rounded-[14px] border border-rose-200 bg-rose-50 px-2 py-1.5 text-[10px] leading-relaxed text-rose-700">
                        Bukti sebelumnya ditolak. Silakan upload ulang atau pilih metode lain.
                    </div>

                    {{-- Pilih metode --}}
                    <div class="mt-2 rounded-[16px] border border-slate-200 bg-white p-2 shadow-sm">
                        <p class="text-[10px] font-semibold text-slate-800">Pilih Metode Pembayaran</p>
                        <p class="mt-0.5 text-[9px] text-slate-500">Gunakan metode yang paling nyaman untuk Anda.</p>

                        <div class="mt-2 grid grid-cols-1 gap-1.5 sm:grid-cols-2 lg:grid-cols-1">
                            <button id="btn-cash" onclick="pilihMetode('cash')"
                                class="metode-btn group rounded-[16px] border-2 border-gray-200 bg-white p-2 text-left transition-all duration-200 hover:border-green-400 hover:bg-green-50">
                                <div class="flex items-start gap-1.5">
                                    <div
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-green-100 text-sm text-green-700 shadow-inner">
                                        💵</div>
                                    <div class="min-w-0">
                                        <div class="text-[10px] font-semibold text-slate-800">Cash</div>
                                        <div class="mt-0.5 text-[9px] leading-relaxed text-slate-500">Bayar langsung ke
                                            petugas dan tunggu konfirmasi di sistem.</div>
                                    </div>
                                </div>
                            </button>

                            <button id="btn-qris" onclick="pilihMetode('qris')"
                                class="metode-btn group rounded-[16px] border-2 border-gray-200 bg-white p-2 text-left transition-all duration-200 hover:border-blue-400 hover:bg-blue-50">
                                <div class="flex items-start gap-1.5">
                                    <div
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-sm text-blue-700 shadow-inner">
                                        📱</div>
                                    <div class="min-w-0">
                                        <div class="text-[10px] font-semibold text-slate-800">QRIS / Transfer</div>
                                        <div class="mt-0.5 text-[9px] leading-relaxed text-slate-500">Scan QR, lakukan
                                            pembayaran, lalu unggah bukti transfer.</div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-2 sm:p-3">
                    {{-- ── PANEL CASH ── --}}
                    <div id="panel-cash" class="hidden space-y-2">
                        <div
                            class="rounded-[16px] border border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 p-2 shadow-sm">
                            <div class="flex items-start gap-1.5">
                                <div
                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-white text-green-700 shadow-sm">
                                    💵</div>
                                <div>
                                    <p class="text-[11px] font-semibold text-green-900">Bayar Cash ke Petugas</p>
                                    <p class="mt-0.5 text-[9px] leading-relaxed text-green-700">Ikuti langkah berikut agar
                                        pembayaran cash dapat dikonfirmasi dengan cepat.</p>
                                </div>
                            </div>

                            <ol class="mt-2 space-y-1">
                                <li class="flex gap-1.5">
                                    <span
                                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white text-[8px] font-semibold text-green-700">1</span>
                                    <p class="text-[10px] text-green-800">Klik tombol konfirmasi di bawah ini.</p>
                                </li>
                                <li class="flex gap-1.5">
                                    <span
                                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white text-[8px] font-semibold text-green-700">2</span>
                                    <p class="text-[10px] text-green-800">Datangi petugas perpustakaan dan tunjukkan kode
                                        <strong id="cash-kode" class="font-semibold"></strong>.</p>
                                </li>
                                <li class="flex gap-1.5">
                                    <span
                                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white text-[8px] font-semibold text-green-700">3</span>
                                    <p class="text-[10px] text-green-800">Serahkan uang sesuai nominal dan tunggu
                                        konfirmasi di sistem.</p>
                                </li>
                            </ol>
                        </div>

                        <form id="form-cash" method="POST" action="">
                            @csrf
                            <input type="hidden" name="metode_bayar" value="cash">
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-[16px] bg-gradient-to-r from-green-600 via-emerald-600 to-teal-500 px-3 py-1.5 text-[10px] font-semibold text-white shadow-[0_20px_35px_-20px_rgba(5,150,105,0.85)] transition hover:brightness-105 active:scale-[0.99]">
                                Konfirmasi Saya Akan Bayar Cash
                            </button>
                        </form>
                    </div>

                    {{-- ── PANEL QRIS ── --}}
                    <div id="panel-qris" class="hidden">
                        <form id="form-qris" method="POST" action="" enctype="multipart/form-data"
                            class="flex max-h-[60vh] flex-col overflow-hidden rounded-[18px] border border-slate-200 bg-slate-50/70 shadow-sm">

                            @csrf
                            <input type="hidden" name="metode_bayar" value="qris">

                            <div class="flex-1 space-y-2 overflow-y-auto p-2 sm:p-2.5">
                                <div
                                    class="rounded-[16px] bg-gradient-to-br from-blue-600 via-indigo-600 to-slate-900 p-2.5 text-white shadow-[0_24px_50px_-28px_rgba(37,99,235,0.8)]">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-[8px] font-semibold uppercase tracking-[0.18em] text-blue-100">
                                                QRIS Payment</p>
                                            <h3 class="mt-0.5 text-xs font-semibold">Scan untuk Membayar</h3>
                                            <p class="mt-0.5 text-[9px] leading-relaxed text-blue-100/90">
                                                Gunakan aplikasi e-wallet atau mobile banking yang mendukung QRIS.
                                            </p>
                                        </div>
                                        <div
                                            class="hidden h-6 rounded-full border border-white/15 bg-white/10 px-2 text-[8px] font-semibold text-white/85 sm:flex sm:items-center">
                                            Aman & Cepat
                                        </div>
                                    </div>

                                    <div class="mt-2 grid gap-2 md:grid-cols-[100px_minmax(0,1fr)]">
                                        <div
                                            class="mx-auto flex w-full max-w-[100px] items-center justify-center rounded-[16px] bg-white p-1.5 shadow-[0_18px_30px_-18px_rgba(15,23,42,0.6)]">
                                            <img src="{{ asset('images/qris.png') }}"
                                                onerror="this.onerror=null;this.src='https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=DENDA'"
                                                class="h-20 w-20 object-contain rounded-lg" alt="QRIS Pembayaran">
                                        </div>

                                        <div class="flex flex-col justify-center space-y-1">
                                            <div class="rounded-lg border border-white/15 bg-white/10 px-2 py-1">
                                                <p
                                                    class="text-[8px] font-semibold uppercase tracking-[0.16em] text-blue-100/80">
                                                    Merchant</p>
                                                <p class="mt-0.5 text-[9px] font-semibold text-white">Perpustakaan</p>
                                            </div>
                                            <div class="rounded-lg border border-white/15 bg-white/10 px-2 py-1">
                                                <p
                                                    class="text-[8px] font-semibold uppercase tracking-[0.16em] text-blue-100/80">
                                                    Nominal</p>
                                                <p id="qris-nominal" class="mt-0.5 text-[10px] font-semibold text-white">
                                                </p>
                                            </div>
                                            <p class="text-[9px] leading-relaxed text-blue-100/80">
                                                Setelah transfer berhasil, lanjutkan dengan mengunggah bukti pembayaran.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[16px] border border-slate-200 bg-white p-2 sm:p-2.5">
                                    <div class="flex items-start justify-between gap-1.5">
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-900">Upload Bukti Pembayaran</p>
                                            <p class="mt-0.5 text-[9px] leading-relaxed text-slate-500">
                                                Unggah screenshot atau foto bukti transfer (JPG/PNG, maks 2MB).
                                            </p>
                                        </div>
                                        <div
                                            class="rounded-full bg-slate-100 px-1 py-0.5 text-[7px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                            Wajib
                                        </div>
                                    </div>

                                    <div id="dropzone" onclick="document.getElementById('input-bukti').click()"
                                        class="mt-2 cursor-pointer rounded-[16px] border-2 border-dashed border-slate-300 bg-slate-50 px-2 py-3 text-center transition hover:border-blue-400 hover:bg-blue-50">

                                        <div id="upload-placeholder">
                                            <div
                                                class="mx-auto flex h-8 w-8 items-center justify-center rounded-lg bg-white text-base shadow-sm">
                                                📎</div>
                                            <p class="mt-1.5 text-[10px] font-semibold text-slate-800">Klik untuk pilih
                                                bukti</p>
                                            <p class="mt-0.5 text-[8px] text-slate-500">Drag & drop juga bisa</p>
                                            <p
                                                class="mt-1 inline-flex rounded-full border border-slate-200 bg-white px-1.5 py-0.5 text-[7px] font-medium text-slate-500">
                                                JPG / PNG • Maks 2MB
                                            </p>
                                        </div>

                                        <img id="preview-img"
                                            class="hidden max-h-24 mx-auto rounded-lg object-contain border border-slate-200 mt-1 shadow-sm">
                                    </div>

                                    <input type="file" id="input-bukti" name="bukti_bayar" class="hidden"
                                        accept="image/jpeg,image/jpg,image/png" onchange="previewBukti(event)">

                                    <p id="file-name"
                                        class="mt-1 rounded-lg bg-slate-50 px-1.5 py-0.5 text-[8px] text-slate-500 text-center hidden">
                                    </p>
                                </div>
                            </div>

                            <div class="border-t border-slate-200 bg-white/95 p-2 sm:p-2.5">
                                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-[8px] leading-relaxed text-slate-500">
                                        Dengan mengirim bukti, Anda menyatakan data sudah benar.
                                    </p>
                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-[14px] bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500 px-3 py-1.5 text-[10px] font-semibold text-white shadow-[0_20px_35px_-20px_rgba(37,99,235,0.9)] transition hover:brightness-105 active:scale-[0.99] sm:w-auto sm:min-w-[140px]">
                                        Kirim Bukti
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── LOADING OVERLAY ── --}}
    <div id="loadingOverlay"
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-[60] hidden items-center justify-center" role="status"
        aria-live="polite">
        <div
            class="mx-4 w-full max-w-sm rounded-[28px] border border-white/60 bg-white p-8 text-center shadow-[0_30px_90px_-35px_rgba(15,23,42,0.55)]">
            <div class="relative mx-auto mb-5 h-16 w-16">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-100"></div>
                <div class="absolute inset-0 animate-spin rounded-full border-4 border-indigo-600 border-t-transparent">
                </div>
            </div>
            <p class="text-lg font-semibold text-slate-900">Memproses Pembayaran</p>
            <p id="loading-msg" class="mt-2 text-sm text-slate-500">Mohon tunggu sebentar</p>
        </div>
    </div>


    <script>
        /* ──────────────────────────────────────────────
                   Buka modal dengan data denda
                   Dipanggil dari tombol "Bayar Sekarang"
                ────────────────────────────────────────────── */
        function bukaModal(id, alat, total, statusSebelumnya) {
            // Isi ringkasan
            document.getElementById('modal-kode').textContent = 'DENDA-' + id;
            document.getElementById('modal-alat').textContent = alat;
            document.getElementById('modal-total').textContent = 'Rp ' + Number(total).toLocaleString('id-ID');
            document.getElementById('cash-kode').textContent = 'DENDA-' + id;
            document.getElementById('qris-nominal').textContent = 'Nominal: Rp ' + Number(total).toLocaleString('id-ID');

            /*
             * Set action form:
             * - Cash  → POST /peminjam/denda/{id}/bayar      (route: peminjam.denda.bayar)
             * - QRIS  → POST /peminjam/upload-bukti-denda/{id} (route: peminjam.upload.bukti.denda)
             */
            document.getElementById('form-cash').action = '/peminjam/denda/' + id + '/bayar';
            document.getElementById('form-qris').action = '/peminjam/upload-bukti-denda/' + id;

            // Alert jika sebelumnya ditolak
            document.getElementById('info-ditolak').classList.toggle('hidden', statusSebelumnya !== 'ditolak');

            resetPanel();

            const modal = document.getElementById('modalBayar');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        /* ──────────────────────────────────────────────
           Tutup modal
        ────────────────────────────────────────────── */
        function tutupModal() {
            document.getElementById('modalBayar').classList.add('hidden');
            document.getElementById('modalBayar').classList.remove('flex');
            document.body.style.overflow = '';
            resetPanel();
        }

        /* ──────────────────────────────────────────────
           Reset semua panel ke kondisi awal
        ────────────────────────────────────────────── */
        function resetPanel() {
            document.querySelectorAll('.metode-btn').forEach(btn => {
                btn.classList.remove('border-green-500', 'bg-green-50', 'border-blue-500', 'bg-blue-50');
                btn.classList.add('border-gray-200');
            });
            document.getElementById('panel-cash').classList.add('hidden');
            document.getElementById('panel-qris').classList.add('hidden');
            document.getElementById('preview-img').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('file-name').classList.add('hidden');
            document.getElementById('file-name').textContent = '';
            const fi = document.getElementById('input-bukti');
            if (fi) fi.value = '';
        }

        /* ──────────────────────────────────────────────
           Pilih metode pembayaran (Cash / QRIS)
        ────────────────────────────────────────────── */
        function pilihMetode(metode) {
            // Reset highlight tombol metode
            document.querySelectorAll('.metode-btn').forEach(btn => {
                btn.classList.remove('border-green-500', 'bg-green-50', 'border-blue-500', 'bg-blue-50');
                btn.classList.add('border-gray-200');
            });
            document.getElementById('panel-cash').classList.add('hidden');
            document.getElementById('panel-qris').classList.add('hidden');

            if (metode === 'cash') {
                document.getElementById('btn-cash').classList.remove('border-gray-200');
                document.getElementById('btn-cash').classList.add('border-green-500', 'bg-green-50');
                document.getElementById('panel-cash').classList.remove('hidden');
            } else {
                document.getElementById('btn-qris').classList.remove('border-gray-200');
                document.getElementById('btn-qris').classList.add('border-blue-500', 'bg-blue-50');
                document.getElementById('panel-qris').classList.remove('hidden');
            }
        }

        /* ──────────────────────────────────────────────
           Preview gambar sebelum upload
        ────────────────────────────────────────────── */
        function previewBukti(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('❌ File terlalu besar! Maksimal 2MB.');
                event.target.value = '';
                return;
            }

            const allowed = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowed.includes(file.type)) {
                alert('❌ Format tidak didukung! Gunakan JPG atau PNG.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-placeholder').classList.add('hidden');
                const img = document.getElementById('preview-img');
                img.src = e.target.result;
                img.classList.remove('hidden');

                const fn = document.getElementById('file-name');
                fn.textContent = '📎 ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                fn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        /* ──────────────────────────────────────────────
           Loading overlay saat submit Cash
        ────────────────────────────────────────────── */
        document.getElementById('form-cash').addEventListener('submit', function() {
            document.getElementById('loading-msg').textContent = 'Mengirim permintaan cash ke petugas...';
            const ov = document.getElementById('loadingOverlay');
            ov.classList.remove('hidden');
            ov.classList.add('flex');
        });

        /* ──────────────────────────────────────────────
           Loading overlay saat submit QRIS
        ────────────────────────────────────────────── */
        document.getElementById('form-qris').addEventListener('submit', function(e) {
            const bukti = document.getElementById('input-bukti').files[0];
            if (!bukti) {
                e.preventDefault();
                alert('⚠️ Harap upload bukti transfer terlebih dahulu!');
                return;
            }
            document.getElementById('loading-msg').textContent = 'Mengupload bukti transfer...';
            const ov = document.getElementById('loadingOverlay');
            ov.classList.remove('hidden');
            ov.classList.add('flex');
        });

        /* ──────────────────────────────────────────────
           Tutup modal: klik backdrop atau tekan Escape
        ────────────────────────────────────────────── */
        document.getElementById('modalBayar').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') tutupModal();
        });
    </script>
@endsection
