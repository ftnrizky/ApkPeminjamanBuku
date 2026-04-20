@extends('layouts.peminjam')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-gradient-to-r from-blue-50 via-white to-gray-50 rounded-2xl p-8 mb-10 border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-blue-100/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-blue-100/20 rounded-full blur-3xl"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
            <div>
                <h1 class="text-4xl font-bold tracking-tight mb-2 text-gray-900">Halo, <span class="text-blue-600">{{ Auth::user()->name }}!</span></h1>
                <p class="text-gray-600 text-sm font-medium">Selamat datang kembali di E-Laptop. Mari cek laptop yang tersedia!</p>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="{{ route('peminjam.katalog') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition-all duration-200 hover:shadow-lg hover:scale-105 active:scale-95 flex items-center gap-2 shadow-md">
                    <i class="fas fa-arrow-right"></i> Mulai Peminjaman
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <i class="fas fa-laptop text-cyan-600"></i> Peminjaman <span class="text-cyan-600">Aktif</span>
            </h2>
            <a href="{{ route('peminjam.kembali') }}" class="text-[10px] font-bold text-cyan-600 hover:text-cyan-700 hover:gap-1.5 uppercase tracking-widest flex items-center gap-1 transition-all duration-200">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        @if(isset($peminjamanAktifList) && count($peminjamanAktifList) > 0)
            <div class="space-y-3">
                @foreach($peminjamanAktifList as $item)
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-slate-50 rounded-xl border border-slate-100 hover:border-cyan-200 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-teal-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                            @if($item->alat->foto)
                                <img src="{{ asset('storage/' . $item->alat->foto) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-laptop text-cyan-600 text-lg"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $item->alat->nama_alat }}</p>
                            <p class="text-[10px] text-slate-500 font-medium">Kode: PJM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }} | Qty: {{ $item->jumlah }} unit</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Batas Kembali</p>
                        <p class="text-sm font-black {{ \Carbon\Carbon::now()->gt($item->tgl_kembali) ? 'text-rose-600' : 'text-teal-600' }}">
                            {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                <p class="text-slate-500 font-bold text-sm">Belum ada peminjaman aktif</p>
                <a href="{{ route('peminjam.katalog') }}" class="inline-block mt-4 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg hover:from-cyan-600 hover:to-cyan-700 transition-all duration-200 hover:shadow-md">
                    Mulai Pinjam Sekarang →
                </a>
            </div>
        @endif
    </div>
@endsection

@section('extra-script')
<script>
    // Notifikasi untuk peminjaman yang disetujui
    @if(session('peminjaman_disetujui'))
        Swal.fire({
            icon: 'success',
            title: 'Peminjaman Disetujui!',
            text: '{{ session("peminjaman_disetujui") }}',
            confirmButtonColor: '#06b6d4',
            confirmButtonText: 'OK'
        });
    @endif

    // Notifikasi untuk pengingat pengembalian
    @if(session('pengembalian_reminder'))
        Swal.fire({
            icon: 'warning',
            title: 'Pengingat Pengembalian',
            text: '{{ session("pengembalian_reminder") }}',
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
    @endif

    // Cek peminjaman yang mendekati batas waktu pengembalian
    const peminjamanAktif = [
        @if(isset($peminjamanAktifList) && count($peminjamanAktifList) > 0)
            @foreach($peminjamanAktifList as $item)
                {
                    nama: @json($item->alat->nama_alat),
                    batas: @json($item->tgl_kembali->format('Y-m-d'))
                }@if(!$loop->last),@endif
            @endforeach
        @endif
    ];

    function formatOverdueText(minutesLate) {
        if (minutesLate <= 0) {
            return 'Sudah melewati batas pengembalian. Denda akan mulai berlaku.';
        }

        const hours = Math.floor(minutesLate / 60);
        const minutes = minutesLate % 60;

        if (hours > 0 && minutes === 0) {
            return `Telat ${hours} jam. Denda akan mulai berlaku.`;
        }

        if (hours > 0) {
            return `Telat ${hours} jam ${minutes} menit. Denda akan mulai berlaku.`;
        }

        return `Telat ${minutes} menit. Denda akan mulai berlaku.`;
    }

    function buildWarningText(nama, threshold) {
        return `Laptop ${nama} harus segera dikembalikan, sekitar ${threshold} lagi.`;
    }

    function showReturnReminder() {
        const now = new Date();
        const thresholds = [
            { minutes: 5, title: 'Pengembalian Sangat Segera', icon: 'warning', message: '5 menit lagi' },
            { minutes: 30, title: 'Pengembalian Mendekat', icon: 'warning', message: '30 menit lagi' },
            { minutes: 60, title: 'Pengembalian Mendekat', icon: 'warning', message: '1 jam lagi' },
            { minutes: 120, title: 'Pengembalian Mendekat', icon: 'warning', message: '2 jam lagi' }
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