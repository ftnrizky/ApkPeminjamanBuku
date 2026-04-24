@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
            <div>
                <h1 class="text-4xl font-900 text-slate-900 mb-2">Transaksi Peminjaman</h1>
                <p class="text-slate-600 font-500">Kelola peminjaman buku yang sedang berlangsung</p>
            </div>

            <!-- Search & Add Button -->
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <form action="{{ route('admin.peminjaman') }}" method="GET" class="relative flex-1 md:flex-initial">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama peminjam atau buku..."
                        class="w-full md:w-64 pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.peminjaman.export_pdf', request()->only(['search'])) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-cyan-200 bg-white px-5 py-3 text-sm font-semibold text-cyan-700 shadow-sm transition hover:border-cyan-300 hover:bg-cyan-50">
                        <i class="fas fa-file-pdf text-sm"></i> Export PDF
                    </a>
                    <button onclick="toggleModal('modal-tambah-pinjam')"
                        class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-3 rounded-xl font-700 flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/20 transition-all active:scale-95 text-sm whitespace-nowrap">
                        <i class="fas fa-plus-circle"></i> Input Peminjaman
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-800 text-slate-500 uppercase tracking-wider text-center w-32">
                                Kode</th>
                            <th class="px-6 py-4 text-xs font-800 text-slate-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-4 text-xs font-800 text-slate-500 uppercase tracking-wider">buku & Qty</th>
                            <th class="px-6 py-4 text-xs font-800 text-slate-500 uppercase tracking-wider">Tgl Kembali</th>
                            <th class="px-6 py-4 text-xs font-800 text-slate-500 uppercase tracking-wider text-right">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="tableBody">
                        @forelse($peminjamanBerlangsung as $item)
                            <tr class="hover:bg-cyan-50/30 transition-all duration-200 group">
                                <!-- Kode -->
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="font-700 text-slate-900 text-sm bg-cyan-100 text-cyan-700 px-3 py-1.5 rounded-lg inline-block group-hover:bg-cyan-500 group-hover:text-white transition-all">
                                        PJM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <!-- Peminjam -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white font-800 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                            {{ substr($item->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-700 text-slate-900 leading-tight">
                                                {{ $item->user->name }}
                                            </p>
                                            <!-- Status Badge -->
                                            @if ($item->status == 'disetujui')
                                                <span
                                                    class="text-xs font-700 bg-cyan-100 text-cyan-700 px-2.5 py-0.5 rounded-md inline-block mt-1 group-hover:bg-cyan-500 group-hover:text-white transition-all">
                                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                                </span>
                                            @elseif($item->status == 'pending')
                                                <span
                                                    class="text-xs font-700 bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-md inline-block mt-1 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                                    <i class="fas fa-hourglass-half mr-1"></i>Pending
                                                </span>
                                            @elseif($item->status == 'selesai' || $item->status == 'dikembalikan')
                                                <span
                                                    class="text-xs font-700 bg-teal-100 text-teal-700 px-2.5 py-0.5 rounded-md inline-block mt-1 group-hover:bg-teal-500 group-hover:text-white transition-all">
                                                    <i class="fas fa-check-double mr-1"></i>Selesai
                                                </span>
                                            @else
                                                <span
                                                    class="text-xs font-700 bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-md inline-block mt-1 group-hover:bg-rose-500 group-hover:text-white transition-all">
                                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- buku & Qty -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-700 text-slate-900 leading-tight">{{ $item->alat->nama_alat }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-600 mt-0.5">
                                        <i class="fas fa-box mr-1 text-cyan-500"></i>Qty: {{ $item->jumlah }}
                                    </p>
                                </td>

                                <!-- Tanggal Kembali -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-days text-cyan-500"></i>
                                        <span
                                            class="text-sm font-600 text-slate-700">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}</span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if ($item->status == 'pending')
                                            <!-- Approve with Modal -->
                                            <button
                                                onclick="openApprovalModal(
        {{ $item->id }},
        '{{ $item->user->name }}',
        '{{ $item->alat->nama_alat }}',
        {{ $item->jumlah }},
        {{ $item->alat->stok_tersedia }}
    )"
                                                title="Setujui dengan Jumlah"
                                                class="w-9 h-9 flex items-center justify-center bg-cyan-50 text-cyan-600 hover:bg-cyan-500 hover:text-white rounded-lg">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                            <!-- Reject -->
                                            <form action="{{ route('admin.peminjaman.verifikasi', $item->id) }}"
                                                method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="ditolak">
                                                <button title="Tolak"
                                                    class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white rounded-lg transition-all duration-200 hover:scale-110 shadow-sm hover:shadow-md">
                                                    <i class="fas fa-times text-sm"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($item->status == 'disetujui')
                                            <!-- Return -->
                                            <button
                                                onclick="openReturnModal(
        '{{ $item->id }}',
        '{{ $item->user->name }}'
    )"
                                                title="Konfirmasi Kembali"
                                                class="w-9 h-9 flex items-center justify-center bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white rounded-lg">
                                                <i class="fas fa-undo-alt text-sm"></i>
                                            </button>
                                        @endif

                                        <!-- Delete -->
                                        <form action="{{ route('admin.peminjaman.destroy', $item->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf @method('DELETE')
                                            <button title="Hapus"
                                                class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-400 hover:bg-rose-100 hover:text-rose-600 rounded-lg transition-all duration-200 hover:scale-110 shadow-sm hover:shadow-md">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-slate-300 text-3xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-700 uppercase text-sm tracking-wide">Belum ada data
                                            peminjaman</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="p-6
                                                bg-slate-50/50 flex items-center justify-between border-t border-slate-200">
                <p class="text-xs font-700 text-slate-500 uppercase tracking-wider">
                    Menampilkan {{ $peminjamanBerlangsung->firstItem() ?? 0 }} -
                    {{ $peminjamanBerlangsung->lastItem() ?? 0 }} dari
                    {{ $peminjamanBerlangsung->total() }} data
                </p>

                <div class="flex gap-2">
                    @if ($peminjamanBerlangsung->onFirstPage())
                        <span
                            class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $peminjamanBerlangsung->previousPageUrl() }}"
                            class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-400 transition-all shadow-sm">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    <span
                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-cyan-500 text-white shadow-lg font-700 text-xs">
                        {{ $peminjamanBerlangsung->currentPage() }}
                    </span>

                    @if ($peminjamanBerlangsung->hasMorePages())
                        <a href="{{ $peminjamanBerlangsung->nextPageUrl() }}"
                            class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-400 transition-all shadow-sm">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span
                            class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH PEMINJAMAN -->
        <div id="modal-tambah-pinjam" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                onclick="toggleModal('modal-tambah-pinjam')"></div>
            <div class="bg-white rounded-2xl w-full max-w-lg p-8 relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="mb-6">
                    <h2 class="text-2xl font-900 text-slate-900">
                        Input <span
                            class="bg-gradient-to-r from-cyan-500 to-cyan-600 bg-clip-text text-transpaE-PUSTAKA">Peminjaman
                            Baru</span>
                    </h2>
                </div>

                <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Pilih Member -->
                    <div>
                        <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Pilih
                            Peminjam <span class="text-rose-600">*</span></label>
                        <select name="user_id" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none transition-all font-medium text-sm">
                            <option value="">-- Cari Nama Peminjam --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih buku -->
                    <div>
                        <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Pilih buku
                            <span class="text-rose-600">*</span></label>
                        <select name="alat_id" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none transition-all font-medium text-sm">
                            <option value="">-- Pilih buku --</option>
                            @foreach ($alats as $alat)
                                <option value="{{ $alat->id }}">{{ $alat->nama_alat }} (Stok:
                                    {{ $alat->stok_tersedia }})</option>
                            @endforeach
                        </select>
                        @error('alat_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah & Tanggal Kembali -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Jumlah
                                <span class="text-rose-600">*</span></label>
                            <input type="number" name="jumlah" value="1" min="1" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                            @error('jumlah')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Tgl
                                Kembali <span class="text-rose-600">*</span></label>
                            <input type="date" name="tgl_kembali" id="tgl_kembali" required
                                min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+3 days')) }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                            @error('tgl_kembali')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tujuan -->
                    <div>
                        <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Tujuan
                            Peminjaman <span class="text-rose-600">*</span></label>
                        <textarea name="tujuan" rows="3" placeholder="Contoh: Digunakan untuk keperluan Belajar" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm resize-none transition-all"></textarea>
                        @error('tujuan')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="toggleModal('modal-tambah-pinjam')"
                            class="flex-1 px-6 py-3 rounded-lg font-800 text-sm uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-[2] bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-3 rounded-lg font-800 text-sm uppercase tracking-wider shadow-lg shadow-cyan-500/20 transition-all active:scale-95">
                            Proses Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL KONFIRMASI PENGEMBALIAN -->
        <div id="modal-kembali" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                onclick="toggleModal('modal-kembali')"></div>
            <div class="bg-white rounded-2xl w-full max-w-md p-8 relative z-10 shadow-2xl">
                <!-- Header Icon -->
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 bg-teal-100 text-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-undo-alt text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-900 text-slate-900">Konfirmasi Pengembalian</h2>
                    <p class="text-sm text-slate-500 font-600 mt-2">Peminjam: <span id="m-member"
                            class="text-cyan-600 font-700"></span></p>
                </div>

                <form id="form-kembali" method="POST" action="">
                    @csrf
                    @method('PATCH')

                    <!-- Kondisi Alat -->
                    <div class="mb-6">
                        <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-3">Kondisi buku
                            Saat Dikembalikan <span class="text-rose-600">*</span></label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <label class="cursor-pointer group">
                                <input type="radio" name="kondisi" value="baik" class="hidden peer" checked>
                                <div
                                    class="text-xs font-700 border-2 border-slate-200 px-4 py-2.5 rounded-lg text-slate-600 peer-checked:bg-cyan-500 peer-checked:text-white peer-checked:border-cyan-500 transition-all group-hover:border-cyan-400">
                                    <i class="fas fa-circle-check mr-1.5"></i>Baik
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="kondisi" value="lecet" class="hidden peer">
                                <div
                                    class="text-xs font-700 border-2 border-slate-200 px-4 py-2.5 rounded-lg text-slate-600 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all group-hover:border-amber-400">
                                    <i class="fas fa-exclamation-circle mr-1.5"></i>Lecet
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="kondisi" value="rusak" class="hidden peer">
                                <div
                                    class="text-xs font-700 border-2 border-slate-200 px-4 py-2.5 rounded-lg text-slate-600 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all group-hover:border-red-400">
                                    <i class="fas fa-times-circle mr-1.5"></i>Rusak
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="kondisi" value="hilang" class="hidden peer">
                                <div
                                    class="text-xs font-700 border-2 border-slate-200 px-4 py-2.5 rounded-lg text-slate-600 peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition-all group-hover:border-slate-400">
                                    <i class="fas fa-ban mr-1.5"></i>Hilang
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-6">
                        <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Catatan
                            (Opsional)</label>
                        <textarea name="catatan" rows="2.5" placeholder="Contoh: Alat lecet di bagian samping..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none text-sm font-medium resize-none transition-all"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button" onclick="toggleModal('modal-kembali')"
                            class="flex-1 px-4 py-3 rounded-lg font-800 text-sm uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-[2] bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-4 py-3 rounded-lg font-800 text-sm uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all active:scale-95">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL APPROVAL PEMINJAMAN -->
        <div id="modal-approval" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                onclick="toggleModal('modal-approval')"></div>
            <div class="bg-white rounded-2xl w-full max-w-md p-8 relative z-10 shadow-2xl">
                <!-- Header Icon -->
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 bg-cyan-100 text-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-900 text-slate-900">Setujui Peminjaman</h2>
                    <p class="text-sm text-slate-500 font-600 mt-2">Peminjam: <span id="approval-member"
                            class="text-cyan-600 font-700"></span></p>
                </div>

                <div class="mb-6 p-4 bg-slate-50 rounded-xl">
                    <p class="text-sm text-slate-600 mb-2"><strong>buku:</strong> <span id="approval-buku"></span></p>
                    <p class="text-sm text-slate-600 mb-2"><strong>Diajukan:</strong> <span
                            id="approval-requested"></span> unit</p>
                    <p class="text-sm text-slate-600"><strong>Stok Tersedia:</strong> <span
                            id="approval-available"></span> unit</p>
                </div>

                <form id="form-approval" method="POST" action="">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="disetujui">

                    <!-- Jumlah Disetujui -->
                    <div class="mb-6">
                        <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Jumlah yang
                            Disetujui <span class="text-rose-600">*</span></label>
                        <input type="number" id="jumlah-disetujui" name="jumlah_disetujui" min="1" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                        <p class="text-xs text-slate-500 mt-1">Maksimal: <span id="max-jumlah"></span> unit</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button" onclick="toggleModal('modal-approval')"
                            class="flex-1 px-4 py-3 rounded-lg font-800 text-sm uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-[2] bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-4 py-3 rounded-lg font-800 text-sm uppercase tracking-wider shadow-lg shadow-cyan-500/20 transition-all active:scale-95">
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

            function openReturnModal(id, member) {
                document.getElementById('form-kembali').action = `/admin/peminjaman/kembalikan/${id}`;
                document.getElementById('m-member').innerText = member;
                toggleModal('modal-kembali');
            }

            function openApprovalModal(id, member, buku, requested, available) {
                document.getElementById('form-approval').action = `/admin/peminjaman/verifikasi/${id}`;
                document.getElementById('approval-member').innerText = member;
                document.getElementById('approval-buku').innerText = buku;
                document.getElementById('approval-requested').innerText = requested;
                document.getElementById('approval-available').innerText = available;
                document.getElementById('jumlah-disetujui').value = requested;
                document.getElementById('jumlah-disetujui').max = Math.min(requested, available);
                document.getElementById('max-jumlah').innerText = Math.min(requested, available);
                toggleModal('modal-approval');
            }

            // Live search dengan highlight
            document.getElementById('searchInput').addEventListener('keyup', function() {
                let searchValue = this.value.toLowerCase();
                let rows = document.querySelectorAll('#tableBody tr');

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    if (text.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Set date constraints
            const tglKembaliInput = document.getElementById('tgl_kembali');
            if (tglKembaliInput) {
                const today = new Date();
                const maxDate = new Date();
                maxDate.setDate(today.getDate() + 3);

                const formatDate = (date) => {
                    return date.toISOString().split('T')[0];
                };

                tglKembaliInput.min = formatDate(today);
                tglKembaliInput.max = formatDate(maxDate);
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
