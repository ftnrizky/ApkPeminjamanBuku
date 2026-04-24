@extends('layouts.petugas')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Dashboard Petugas</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Pantau antrean verifikasi, peminjaman aktif, dan penyelesaian transaksi harian dalam satu tampilan yang ringkas.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 ring-1 ring-blue-100">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                Shift aktif
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Menunggu Approval</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-slate-900">{{ str_pad($waitingApproval, 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="mt-2 text-sm text-slate-500">Permintaan yang perlu diproses segera.</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Buku Dipinjam</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-slate-900">{{ str_pad($alatDipinjam, 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="mt-2 text-sm text-slate-500">Unit yang sedang beredar saat ini.</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Selesai Hari Ini</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-slate-900">{{ str_pad($selesaiHariIni, 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="mt-2 text-sm text-slate-500">Transaksi yang selesai pada hari ini.</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Task Queue</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">Antrean Tugas Terbaru</h2>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                    <i class="fas fa-bolt text-[10px]"></i>
                    Segera proses
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Peminjam</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Judul Buku</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Tugas</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($antreanTugas as $tugas)
                            @php
                                $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-violet-500', 'bg-rose-500'];
                                $avatarColor = $colors[$loop->index % count($colors)];
                            @endphp
                            <tr class="transition duration-200 hover:bg-blue-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-semibold text-white {{ $avatarColor }}">
                                            {{ strtoupper(substr($tugas->user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-semibold text-slate-900">{{ $tugas->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-sm font-semibold text-slate-900">{{ $tugas->alat->nama_alat }}</p>
                                        <span class="inline-flex rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                            Qty: {{ $tugas->jumlah }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        PJM-{{ str_pad($tugas->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($tugas->status == 'pending')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                            <i class="fas fa-arrow-up text-[10px]"></i>Peminjaman
                                        </span>
                                    @elseif($tugas->status == 'dikembalikan')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                            <i class="fas fa-arrow-down text-[10px]"></i>Pengembalian
                                        </span>
                                    @elseif($tugas->status == 'disetujui')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                            <i class="fas fa-check-circle text-[10px]"></i>Dipinjam
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                            <i class="fas fa-clock text-[10px]"></i>{{ ucfirst($tugas->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($tugas->status == 'pending')
                                        <a href="{{ route('petugas.menyetujui_peminjaman') }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-blue-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                                            <i class="fas fa-check text-xs"></i>Setujui
                                        </a>
                                    @elseif($tugas->status == 'dikembalikan')
                                        <a href="{{ route('petugas.menyetujui_kembali') }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-400 hover:shadow-md">
                                            <i class="fas fa-magnifying-glass text-xs"></i>Cek Buku
                                        </a>
                                    @elseif($tugas->status == 'disetujui')
                                        <button
                                            type="button"
                                            onclick="kirimPengingat({{ $tugas->id }}, '{{ $tugas->user->name }}', '{{ $tugas->alat->nama_alat }}')"
                                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition duration-200 hover:-translate-y-0.5 hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                                            <i class="fas fa-bell text-xs"></i>Ingatkan
                                        </button>
                                    @else
                                        <span class="text-sm font-medium text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <i class="fas fa-check-double text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-slate-900">Antrean Bersih</h3>
                                        <p class="text-sm text-slate-500">Semua tugas sudah selesai diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@section('extra-script')
    <script>
        function kirimPengingat(id, namaPeminjam, namaAlat) {
            Swal.fire({
                title: 'Kirim Pengingat?',
                text: `Kirim pengingat ke ${namaPeminjam} untuk mengembalikan "${namaAlat}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/petugas/pengingat/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    confirmButtonColor: '#3b82f6',
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                    confirmButtonColor: '#ef4444'
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengirim pengingat.',
                                confirmButtonColor: '#ef4444'
                            });
                        });
                }
            });
        }
    </script>
@endsection
