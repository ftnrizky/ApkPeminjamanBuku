@extends('layouts.petugas')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Dashboard Petugas</h1>
        <p class="text-gray-600 font-medium text-sm">Pantau aktivitas peminjaman laptop secara real-time.</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm flex items-center gap-3 hover:shadow-md transition-shadow duration-300">
            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg text-xs">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-gradient-to-br from-white to-cyan-50/30 p-6 rounded-2xl border border-cyan-200 shadow-sm hover:shadow-lg hover:border-cyan-300 transition-all duration-300 group cursor-pointer">
        <div class="bg-gradient-to-br from-cyan-100 to-cyan-50 p-4 rounded-lg text-cyan-600 font-bold text-xl mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-hourglass-start"></i>
        </div>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Menunggu Approval</p>
        <h3 class="text-3xl font-black text-slate-900 mt-1">{{ str_pad($waitingApproval, 2, '0', STR_PAD_LEFT) }} <span class="text-xs font-medium text-slate-400">Permintaan</span></h3>
    </div>

    <div class="bg-gradient-to-br from-white to-amber-50/30 p-6 rounded-2xl border border-amber-200 shadow-sm hover:shadow-lg hover:border-amber-300 transition-all duration-300 group cursor-pointer">
        <div class="bg-gradient-to-br from-amber-100 to-amber-50 p-4 rounded-lg text-amber-600 font-bold text-xl mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-laptop"></i>
        </div>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Laptop Dipinjam</p>
        <h3 class="text-3xl font-black text-slate-900 mt-1">{{ str_pad($alatDipinjam, 2, '0', STR_PAD_LEFT) }} <span class="text-xs font-medium text-slate-400">Unit</span></h3>
    </div>

    <div class="bg-gradient-to-br from-white to-teal-50/30 p-6 rounded-2xl border border-teal-200 shadow-sm hover:shadow-lg hover:border-teal-300 transition-all duration-300 group cursor-pointer">
        <div class="bg-gradient-to-br from-teal-100 to-teal-50 p-4 rounded-lg text-teal-600 font-bold text-xl mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-check-circle"></i>
        </div>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Selesai Hari Ini</p>
        <h3 class="text-3xl font-black text-slate-900 mt-1">{{ str_pad($selesaiHariIni, 2, '0', STR_PAD_LEFT) }} <span class="text-xs font-medium text-slate-400">Transaksi</span></h3>
    </div>
</div>

<div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-slate-900 uppercase tracking-tight">Antrean Tugas Terbaru</h2>
        <span class="text-[10px] font-black bg-cyan-100 text-cyan-700 px-3 py-1.5 rounded-lg uppercase tracking-wider">Segera Proses</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-200">
                    <th class="pb-4 px-2">Peminjam</th>
                    <th class="pb-4">Laptop</th>
                    <th class="pb-4">Kode</th>
                    <th class="pb-4">Jenis Tugas</th>
                    <th class="pb-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($antreanTugas as $tugas)
                <tr class="group hover:bg-cyan-50/40 transition-all duration-200">
                    <td class="py-5 px-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-md group-hover:scale-110 transition-transform duration-300">
                                {{ strtoupper(substr($tugas->user->name, 0, 2)) }}
                            </div>
                            <span class="font-bold text-sm text-slate-900">{{ $tugas->user->name }}</span>
                        </div>
                    </td>
                    <td class="py-5">
                        <span class="text-sm font-semibold text-slate-700 block">{{ $tugas->alat->nama_alat }}</span>
                        <span class="text-[10px] font-bold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-200 mt-1 inline-block">
                            <i class="fas fa-cubes mr-1"></i> Qty: {{ $tugas->jumlah }}
                        </span>
                    </td>
                    <td class="py-5">
                        <span class="font-bold text-slate-900 text-sm bg-slate-100 px-3 py-1.5 rounded-lg group-hover:bg-cyan-100 group-hover:text-cyan-700 transition-all duration-200">
                            PJM-{{ str_pad($tugas->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td class="py-5">
                        @if($tugas->status == 'pending')
                            <span class="bg-cyan-100 text-cyan-700 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-cyan-200 flex items-center gap-1 w-fit">
                                <i class="fas fa-arrow-up"></i> Peminjaman
                            </span>
                        @elseif($tugas->status == 'dikembalikan')
                            <span class="bg-teal-100 text-teal-700 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-teal-200 flex items-center gap-1 w-fit">
                                <i class="fas fa-arrow-down"></i> Pengembalian
                            </span>
                        @elseif($tugas->status == 'disetujui')
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-green-200 flex items-center gap-1 w-fit">
                                <i class="fas fa-check-circle"></i> Dipinjam
                            </span>
                        @else
                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-slate-200 flex items-center gap-1 w-fit">
                                <i class="fas fa-clock"></i> {{ ucfirst($tugas->status) }}
                            </span>
                        @endif
                    </td>
                    <td class="py-5 text-center">
                        @if($tugas->status == 'pending')
                            <a href="{{ route('petugas.menyetujui_peminjaman') }}" 
                            class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white text-[10px] font-bold px-6 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg hover:scale-105 active:scale-95 uppercase tracking-wider inline-block">
                                <i class="fas fa-check mr-1"></i> Setujui
                            </a>
                        @elseif($tugas->status == 'dikembalikan')
                            <a href="{{ route('petugas.menyetujui_kembali') }}" 
                            class="bg-gradient-to-r from-slate-700 to-slate-800 hover:from-slate-800 hover:to-slate-900 text-white text-[10px] font-bold px-6 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg hover:scale-105 active:scale-95 uppercase tracking-wider inline-block">
                                <i class="fas fa-search mr-1"></i> Cek Laptop
                            </a>
                        @elseif($tugas->status == 'disetujui')
                            <button type="button" onclick="kirimPengingat({{ $tugas->id }}, '{{ $tugas->user->name }}', '{{ $tugas->alat->nama_alat }}')" 
                            class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-[10px] font-bold px-4 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg hover:scale-105 active:scale-95 uppercase tracking-wider">
                                <i class="fas fa-bell mr-1"></i> Ingatkan
                            </button>
                        @else
                            <span class="text-slate-400 text-[9px] font-bold uppercase">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-300">
                            <i class="fas fa-check-double text-5xl mb-4"></i>
                            <p class="text-xs font-bold uppercase tracking-wider">Antrean Bersih - Semua Tugas Selesai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('extra-script')
<script>
    function kirimPengingat(id, namaPeminjam, namaAlat) {
        Swal.fire({
            title: 'Kirim Pengingat?',
            text: `Kirim pengingat ke ${namaPeminjam} untuk mengembalikan ${namaAlat}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
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
                            confirmButtonColor: '#06b6d4',
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
                .catch(error => {
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