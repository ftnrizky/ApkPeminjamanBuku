@extends('layouts.admin')

@section('title', 'Kelola Alat')

@section('content')
<!-- Success Alert -->
@if(session('success'))
<div class="mb-6 p-4 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white rounded-xl shadow-lg shadow-cyan-500/20 flex items-center gap-3 border border-cyan-400/30">
    <i class="fas fa-check-circle text-xl"></i>
    <span class="font-bold text-sm">{{ session('success') }}</span>
</div>
@endif

<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
        <div>
            <h1 class="text-4xl font-900 text-slate-900 mb-2">Katalog Laptop</h1>
            <p class="text-slate-600 font-500">Kelola stok, harga sewa, dan kondisi laptop E-Laptop secara real-time</p>
        </div>

        <!-- Search & Add Button -->
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <form action="{{ route('admin.alat') }}" method="GET" class="relative flex-1 md:flex-initial">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laptop..."
                    class="w-full md:w-64 pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none transition-all font-medium">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.alat.export_pdf', request()->only(['kategori', 'search'])) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-cyan-200 bg-white px-5 py-3 text-sm font-semibold text-cyan-700 shadow-sm transition hover:border-cyan-300 hover:bg-cyan-50">
                    <i class="fas fa-file-pdf text-sm"></i> Export PDF
                </a>
                <button onclick="toggleModal('modal-tambah')"
                    class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-3 rounded-xl font-700 flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/20 transition-all active:scale-95 text-sm whitespace-nowrap">
                    <i class="fas fa-plus-circle"></i> Tambah Laptop
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Category Filter -->
<div class="flex items-center gap-3 mb-8 overflow-x-auto pb-2 -mx-4 px-4 md:mx-0 md:px-0 md:overflow-visible">
    <a href="{{ route('admin.alat') }}"
        class="px-4 py-2 rounded-lg text-sm font-700 transition-all whitespace-nowrap flex items-center gap-2 border
        {{ (!request('kategori'))
            ? 'bg-cyan-500 text-white border-cyan-500 shadow-lg shadow-cyan-500/20'
            : 'bg-white text-slate-600 border-slate-200 hover:border-cyan-400/50 hover:text-cyan-600' }}">
        <i class="fas fa-th-large"></i> Semua
    </a>
    @foreach($kategoris as $kat)
    <a href="{{ route('admin.alat', ['kategori' => $kat->nama, 'search' => request('search')]) }}"
        class="px-4 py-2 rounded-lg text-sm font-700 transition-all whitespace-nowrap flex items-center gap-2 border
        {{ (request('kategori') == $kat->nama)
            ? 'bg-cyan-500 text-white border-cyan-500 shadow-lg shadow-cyan-500/20'
            : 'bg-white text-slate-600 border-slate-200 hover:border-cyan-400/50 hover:text-cyan-600' }}">
        <i class="fas {{ $kat->icon }}"></i> {{ $kat->nama }}
    </a>
    @endforeach
</div>

<!-- Laptops Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($alats as $alat)
    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-cyan-400/50 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
        <!-- Image Section -->
        <div class="relative h-48 bg-slate-100 overflow-hidden flex items-center justify-center">
            <!-- Condition Badge -->
            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-xs font-800 px-3 py-1.5 rounded-lg shadow-md z-10 uppercase tracking-wider
                {{ $alat->kondisi == 'baik' ? 'text-cyan-600' : ($alat->kondisi == 'lecet' ? 'text-amber-500' : 'text-rose-600') }}">
                {{ $alat->kondisi }}
            </span>

            <!-- Image Display -->
            @if($alat->foto)
                <img src="{{ asset('storage/' . $alat->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            @else
                <div class="flex flex-col items-center justify-center">
                    <i class="fas fa-laptop text-4xl text-slate-300 mb-2"></i>
                    <p class="text-xs text-slate-400 font-bold">No Image</p>
                </div>
            @endif
        </div>

        <!-- Content Section -->
        <div class="p-4 space-y-3">
            <!-- Category -->
            <div class="flex justify-between items-start gap-2">
                <span class="inline-block px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-700 rounded-lg uppercase tracking-wider">
                    {{ $alat->kategori }}
                </span>
            </div>

            <!-- Product Name -->
            <h3 class="text-base font-900 text-slate-900 line-clamp-2 leading-tight">{{ $alat->nama_alat }}</h3>

            <!-- Price -->
            <p class="text-cyan-600 font-900 text-lg">
                Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}
                <span class="text-xs text-slate-400 font-500"> / hari</span>
            </p>

            <!-- Description -->
            <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed">
                {{ $alat->deskripsi ?? 'Laptop berkualitas dengan spesifikasi terbaik untuk kebutuhan Anda.' }}
            </p>

            <!-- Stock Info -->
            <div class="pt-3 border-t border-slate-100">
                <p class="text-xs font-700 text-slate-400 uppercase mb-1.5">Stok Tersedia</p>
                <p class="text-2xl font-900 text-slate-900">
                    {{ $alat->stok_tersedia }}
                    <span class="text-sm font-500 text-slate-400">/{{ $alat->stok_total }}</span>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-3 border-t border-slate-100">
                <button
                    onclick="openEditAlat(
                        '{{ $alat->id }}',
                        '{{ addslashes($alat->nama_alat) }}',
                        '{{ addslashes($alat->kategori) }}',
                        '{{ $alat->stok_total }}',
                        '{{ $alat->kondisi }}',
                        '{{ addslashes($alat->deskripsi ?? '') }}',
                        '{{ $alat->foto ? asset('storage/' . $alat->foto) : '' }}',
                        '{{ (int)$alat->harga_sewa }}'
                    )"
                    class="flex-1 px-3 py-2 rounded-lg bg-cyan-50 text-cyan-600 hover:bg-cyan-100 font-700 text-xs transition-colors flex items-center justify-center gap-1.5">
                    <i class="fas fa-edit text-xs"></i> Edit
                </button>
                <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="flex-1"
                    onsubmit="return confirm('Hapus laptop ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-full px-3 py-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 font-700 text-xs transition-colors flex items-center justify-center gap-1.5">
                        <i class="fas fa-trash text-xs"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 flex flex-col items-center justify-center">
        <div class="bg-slate-50 rounded-2xl p-8 text-center">
            <i class="fas fa-inbox text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-500 font-700 uppercase text-sm tracking-wide">Tidak ada data laptop ditemukan</p>
        </div>
    </div>
    @endforelse
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MODAL TAMBAH LAPTOP                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-tambah')"></div>
    <div class="bg-white rounded-2xl w-full max-w-lg p-8 relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-900 text-slate-900 mb-6">
            Tambah <span class="text-cyan-600">Laptop Baru</span>
        </h2>

        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="formTambah">
            @csrf

            <!-- Nama Laptop -->
            <div>
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">
                    Nama Laptop <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="nama_alat" required placeholder="Contoh: ASUS VivoBook 15"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                @error('nama_alat')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori & Kondisi -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">
                        Kategori <span class="text-rose-600">*</span>
                    </label>
                    <select name="kategori" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none text-sm font-medium transition-all">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->nama }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">
                        Kondisi <span class="text-rose-600">*</span>
                    </label>
                    <select name="kondisi" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none text-sm font-medium transition-all">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="lecet">Lecet</option>
                        <option value="rusak">Rusak</option>
                    </select>
                    @error('kondisi')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Stok & Harga -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">
                        Stok <span class="text-rose-600">*</span>
                    </label>
                    <input type="number" name="stok_total" required min="1" placeholder="0"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                    @error('stok_total')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">
                        Sewa/Hari <span class="text-rose-600">*</span>
                    </label>
                    <input type="number" name="harga_sewa" required min="1000" step="1000" placeholder="50000"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                    @error('harga_sewa')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Spesifikasi dan keterangan laptop..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm resize-none transition-all"></textarea>
                @error('deskripsi')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Foto -->
            <div>
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Upload Foto Laptop</label>
                <input type="file" name="foto" accept="image/*"
                    class="w-full px-4 py-2 bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-800 file:bg-cyan-100 file:text-cyan-700 hover:border-cyan-400">
                @error('foto')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="toggleModal('modal-tambah')"
                    class="flex-1 px-6 py-3 rounded-lg font-800 text-sm uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-[2] bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-3 rounded-lg font-800 text-sm uppercase tracking-wider shadow-lg shadow-cyan-500/20 transition-all active:scale-95">
                    Simpan Laptop
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MODAL EDIT LAPTOP                                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-edit')"></div>
    <div class="bg-white rounded-2xl w-full max-w-lg p-8 relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-900 text-slate-900 mb-6">
            Edit <span class="text-cyan-600">Data Laptop</span>
        </h2>

        {{-- ✅ action diisi via JS, method spoofing PUT via hidden input --}}
        <form id="form-edit" action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <!-- Image Preview -->
            <div class="flex justify-center mb-2">
                <img id="edit-preview" src="" alt="Preview"
                    class="h-24 w-auto object-cover rounded-lg border-2 border-cyan-200 shadow-md hidden">
            </div>

            <!-- Nama Laptop -->
            <div>
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Nama Laptop</label>
                <input type="text" id="edit-nama" name="nama_alat" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
            </div>

            <!-- Kategori & Kondisi -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Kategori</label>
                    <select id="edit-kategori" name="kategori" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none text-sm font-medium transition-all">
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->nama }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Kondisi</label>
                    <select id="edit-kondisi" name="kondisi" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none text-sm font-medium transition-all">
                        <option value="baik">Baik</option>
                        <option value="lecet">Lecet</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>
            </div>

            <!-- Stok & Harga -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Stok</label>
                    <input type="number" id="edit-stok" name="stok_total" required min="1"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                </div>
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Sewa/Hari</label>
                    <input type="number" id="edit-harga" name="harga_sewa" required min="1000" step="1000"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm transition-all">
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Deskripsi</label>
                <textarea id="edit-deskripsi" name="deskripsi" rows="3"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-sm resize-none transition-all"></textarea>
            </div>

            <!-- Ganti Foto -->
            <div>
                <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-2">Ganti Foto Laptop</label>
                <input type="file" name="foto" accept="image/*" id="edit-foto-input"
                    class="w-full px-4 py-2 bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-800 file:bg-cyan-100 file:text-cyan-700 hover:border-cyan-400">
                <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti foto</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="toggleModal('modal-edit')"
                    class="flex-1 px-6 py-3 rounded-lg font-800 text-sm uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-[2] bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-3 rounded-lg font-800 text-sm uppercase tracking-wider shadow-lg shadow-cyan-500/20 transition-all active:scale-95">
                    Update Laptop
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Toggle modal open/close ──────────────────────────────────────────────
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (!modal) return;
        const isHidden = modal.classList.contains('hidden');
        modal.classList.toggle('hidden', !isHidden);
        modal.classList.toggle('flex', isHidden);
    }

    // ── Isi form edit lalu buka modal ────────────────────────────────────────
    function openEditAlat(id, nama, kategori, stok, kondisi, deskripsi, fotoUrl, hargaSewa) {
        // Set action URL dengan ID yang benar
        document.getElementById('form-edit').action = '/admin/alat/' + id;

        // Isi semua field
        document.getElementById('edit-nama').value      = nama;
        document.getElementById('edit-stok').value      = stok;
        document.getElementById('edit-kondisi').value   = kondisi;
        document.getElementById('edit-deskripsi').value = deskripsi;
        document.getElementById('edit-harga').value     = hargaSewa;

        // Set kategori — pastikan option dengan value tersebut di-select
        const selectKategori = document.getElementById('edit-kategori');
        for (let i = 0; i < selectKategori.options.length; i++) {
            if (selectKategori.options[i].value === kategori) {
                selectKategori.selectedIndex = i;
                break;
            }
        }

        // Preview foto jika ada
        const preview = document.getElementById('edit-preview');
        if (fotoUrl && fotoUrl.trim() !== '') {
            preview.src = fotoUrl;
            preview.classList.remove('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
        }

        // Reset input file agar tidak mengirim file lama
        document.getElementById('edit-foto-input').value = '';

        toggleModal('modal-edit');
    }

    // ── Preview foto baru saat dipilih di modal edit ─────────────────────────
    document.getElementById('edit-foto-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('edit-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    // ── Tutup semua modal dengan tombol Escape ───────────────────────────────
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
        }
    });

    // ── SweetAlert notifikasi sukses ─────────────────────────────────────────
    @if(session('success'))
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