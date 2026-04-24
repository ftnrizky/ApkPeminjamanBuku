@extends('layouts.peminjam')

@section('title', 'Dashboard')

@section('content')
    @php
        $peminjamanAktifList = $peminjamanAktifList ?? [];
        $jumlahAktif = count($peminjamanAktifList);
        $jumlahTerlambat = collect($peminjamanAktifList)->filter(fn($item) => \Carbon\Carbon::now()->gt($item->tgl_kembali))->count();
        $adaTerlambat = false;
        $namaTerlambat = [];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-6 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-100">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Sistem aktif
                    </div>
                    <p class="mt-4 text-sm font-medium text-slate-500">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
                    </p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Halo, {{ Auth::user()->name }}
                    </h1>
                    <p class="mt-3 max-w-xl text-sm leading-7 text-slate-600 sm:text-base">
                        Kelola peminjaman Anda dengan tampilan yang lebih rapi, cepat, dan mudah dipantau setiap hari.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('peminjam.katalog') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                            <i class="fas fa-book-open text-xs"></i>
                            Jelajahi Katalog
                        </a>
                        <a href="{{ route('peminjam.kembali') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                            <i class="fas fa-rotate-left text-xs"></i>
                            Lihat Pengembalian
                        </a>
                    </div>
                </div>

                <div class="grid w-full gap-4 sm:grid-cols-3 lg:max-w-md">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dipinjam</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $jumlahAktif }}</p>
                        <p class="mt-1 text-xs text-slate-500">Peminjaman aktif saat ini</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Terlambat</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-amber-700">{{ $jumlahTerlambat }}</p>
                        <p class="mt-1 text-xs text-amber-600">Perlu segera dikembalikan</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Hari Ini</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-700">
                            {{ \Carbon\Carbon::now()->format('d') }}
                        </p>
                        <p class="mt-1 text-xs text-emerald-600">{{ \Carbon\Carbon::now()->translatedFormat('M Y') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('peminjam.katalog') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition duration-200 group-hover:bg-blue-100">
                    <i class="fas fa-book-open"></i>
                </div>
                <h2 class="mt-4 text-base font-semibold text-slate-900">Katalog Buku</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Telusuri koleksi dan pilih alat atau buku yang ingin dipinjam.</p>
            </a>

            <a href="{{ route('peminjam.kembali') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-md">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition duration-200 group-hover:bg-emerald-100">
                    <i class="fas fa-rotate-left"></i>
                </div>
                <h2 class="mt-4 text-base font-semibold text-slate-900">Pengembalian</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Pantau peminjaman aktif dan lakukan pengembalian tepat waktu.</p>
            </a>

            <a href="{{ route('peminjam.riwayat') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-violet-200 hover:shadow-md">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition duration-200 group-hover:bg-violet-100">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
                <h2 class="mt-4 text-base font-semibold text-slate-900">Riwayat</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Lihat histori peminjaman yang pernah Anda lakukan sebelumnya.</p>
            </a>

            <a href="{{ route('peminjam.denda') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-rose-200 hover:shadow-md">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition duration-200 group-hover:bg-rose-100">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h2 class="mt-4 text-base font-semibold text-slate-900">Denda</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Cek tagihan aktif dan selesaikan pembayaran tanpa hambatan.</p>
            </a>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.7fr,1fr]">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Perpustakaan Kamu</p>
                        <h2 class="mt-1 text-xl font-semibold text-slate-900">Peminjaman Aktif</h2>
                    </div>
                    <a href="{{ route('peminjam.kembali') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                        Lihat Semua
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="p-4 sm:p-6">
                    @if ($jumlahAktif > 0)
                        <div class="space-y-4">
                            @foreach ($peminjamanAktifList as $item)
                                @php
                                    $isLate = \Carbon\Carbon::now()->gt($item->tgl_kembali);
                                    if ($isLate) {
                                        $adaTerlambat = true;
                                        $namaTerlambat[] = $item->alat->nama_alat;
                                    }
                                @endphp

                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 transition duration-200 hover:border-blue-200 hover:bg-blue-50/50">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex min-w-0 items-center gap-4">
                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-200 text-slate-500">
                                                @if ($item->alat->foto)
                                                    <img src="{{ asset('storage/' . $item->alat->foto) }}" alt="{{ $item->alat->nama_alat }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <i class="fas fa-book text-lg"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="truncate text-base font-semibold text-slate-900">{{ $item->alat->nama_alat }}</h3>
                                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                                    <span class="rounded-lg bg-white px-2.5 py-1 font-semibold text-slate-600 ring-1 ring-slate-200">
                                                        PJM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                    <span class="text-slate-500">{{ $item->jumlah }} unit</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="sm:text-right">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Batas Kembali</p>
                                            <p class="mt-1 text-sm font-semibold {{ $isLate ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}
                                            </p>
                                            <span
                                                class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $isLate ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                                {{ $isLate ? 'Terlambat ' . \Carbon\Carbon::parse($item->tgl_kembali)->diffForHumans(\Carbon\Carbon::now(), true) : \Carbon\Carbon::parse($item->tgl_kembali)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if ($adaTerlambat)
                                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-rose-600 ring-1 ring-rose-200">
                                            <i class="fas fa-triangle-exclamation"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-rose-700">Pengembalian Telat</h3>
                                            <p class="mt-1 text-sm leading-6 text-rose-600">
                                                <strong>{{ implode(', ', $namaTerlambat) }}</strong> sudah melewati batas pengembalian. Segera kembalikan untuk menghindari denda tambahan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-400 ring-1 ring-slate-200">
                                <i class="fas fa-book-open text-xl"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-semibold text-slate-900">Belum Ada Peminjaman Aktif</h3>
                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                Mulai pinjam buku favorit Anda dari koleksi E-Pustaka untuk melihat aktivitas di dashboard ini.
                            </p>
                            <a href="{{ route('peminjam.katalog') }}"
                                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                                <i class="fas fa-search text-xs"></i>
                                Jelajahi Katalog
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Panduan</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">Cara Meminjam Buku</h2>

                    <div class="mt-6 space-y-4">
                        @php
                            $steps = [
                                ['title' => 'Pilih Buku', 'icon' => 'fa-book', 'state' => 'done'],
                                ['title' => 'Ajukan Pinjaman', 'icon' => 'fa-paper-plane', 'state' => 'done'],
                                ['title' => 'Menunggu Persetujuan', 'icon' => 'fa-spinner', 'state' => 'active'],
                                ['title' => 'Buku Tersedia', 'icon' => 'fa-box', 'state' => 'idle'],
                                ['title' => 'Kembalikan Buku', 'icon' => 'fa-rotate-left', 'state' => 'idle'],
                            ];
                        @endphp

                        @foreach ($steps as $step)
                            <div class="flex items-start gap-3 rounded-2xl border p-4 {{ $step['state'] === 'done'
                                ? 'border-emerald-200 bg-emerald-50'
                                : ($step['state'] === 'active'
                                    ? 'border-blue-200 bg-blue-50'
                                    : 'border-slate-200 bg-slate-50') }}">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl {{ $step['state'] === 'done'
                                        ? 'bg-emerald-500 text-white'
                                        : ($step['state'] === 'active'
                                            ? 'bg-blue-500 text-white'
                                            : 'bg-white text-slate-500 ring-1 ring-slate-200') }}">
                                    <i class="fas {{ $step['icon'] }}"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">{{ $step['title'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $step['state'] === 'done' ? 'Tahap ini sudah Anda lewati.' : ($step['state'] === 'active' ? 'Tahap ini sedang berlangsung sekarang.' : 'Tahap berikutnya akan aktif setelah proses sebelumnya selesai.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ringkasan Cepat</p>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-500">Peminjaman aktif</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $jumlahAktif }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-500">Terlambat</span>
                            <span class="text-sm font-semibold text-amber-700">{{ $jumlahTerlambat }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-500">Tanggal hari ini</span>
                            <span class="text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('extra-script')
    <script>
        @if (session('peminjaman_disetujui'))
            Swal.fire({
                icon: 'success',
                title: 'Peminjaman Disetujui!',
                text: '{{ session('peminjaman_disetujui') }}',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('pengembalian_reminder'))
            Swal.fire({
                icon: 'warning',
                title: 'Pengingat Pengembalian',
                text: '{{ session('pengembalian_reminder') }}',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK'
            });
        @endif

        const peminjamanAktif = [
            @if (isset($peminjamanAktifList) && count($peminjamanAktifList) > 0)
                @foreach ($peminjamanAktifList as $item)
                    {
                        nama: @json($item->alat->nama_alat),
                        batas: @json($item->tgl_kembali->format('Y-m-d'))
                    }@if (!$loop->last),@endif
                @endforeach
            @endif
        ];

        function formatOverdueText(minutesLate) {
            if (minutesLate <= 0) return 'Sudah melewati batas pengembalian. Denda akan mulai berlaku.';
            const hours = Math.floor(minutesLate / 60);
            const minutes = minutesLate % 60;
            if (hours > 0 && minutes === 0) return `Telat ${hours} jam. Denda akan mulai berlaku.`;
            if (hours > 0) return `Telat ${hours} jam ${minutes} menit. Denda akan mulai berlaku.`;
            return `Telat ${minutes} menit. Denda akan mulai berlaku.`;
        }

        function buildWarningText(nama, threshold) {
            return `Buku "${nama}" harus segera dikembalikan, sekitar ${threshold} lagi.`;
        }

        function showReturnReminder() {
            const now = new Date();
            const thresholds = [{
                    minutes: 5,
                    title: 'Pengembalian Sangat Segera',
                    icon: 'warning',
                    message: '5 menit lagi'
                },
                {
                    minutes: 30,
                    title: 'Pengembalian Mendekat',
                    icon: 'warning',
                    message: '30 menit lagi'
                },
                {
                    minutes: 60,
                    title: 'Pengembalian Mendekat',
                    icon: 'warning',
                    message: '1 jam lagi'
                },
                {
                    minutes: 120,
                    title: 'Pengembalian Mendekat',
                    icon: 'warning',
                    message: '2 jam lagi'
                }
            ];

            peminjamanAktif.forEach(item => {
                if (!item.batas) return;
                const [year, month, day] = item.batas.split('-');
                const deadline = new Date(parseInt(year), parseInt(month, 10) - 1, parseInt(day, 10), 17, 0, 0);
                const diffMinutes = Math.floor((deadline - now) / 60000);

                if (diffMinutes <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Telat Mengembalikan!',
                        text: formatOverdueText(Math.abs(diffMinutes)),
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                for (const threshold of thresholds) {
                    if (diffMinutes <= threshold.minutes) {
                        Swal.fire({
                            icon: threshold.icon,
                            title: threshold.title,
                            text: buildWarningText(item.nama, threshold.message),
                            confirmButtonColor: '#f59e0b',
                            confirmButtonText: 'Oke'
                        });
                        break;
                    }
                }
            });
        }

        showReturnReminder();
    </script>
@endsection
