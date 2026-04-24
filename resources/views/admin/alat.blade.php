@extends('layouts.admin')

@section('title', 'Kelola Buku')

@section('content')

<style>
    :root {
        --primary: #18181b;
        --primary-soft: #27272a;
        --accent: #6366f1;
        --accent-light: #eef2ff;
        --accent-hover: #4f46e5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --border: #e4e4e7;
        --muted: #71717a;
        --surface: #ffffff;
        --bg: #f8f8f8;
    }

    .page-bg { background: var(--bg); min-height: 100vh; }

    /* ── Search input ── */
    .search-input {
        padding: 10px 16px 10px 40px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13.5px;
        background: var(--surface);
        color: var(--primary);
        outline: none;
        transition: border 0.2s, box-shadow 0.2s;
        width: 260px;
    }
    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    /* ── Category pills ── */
    .cat-pill {
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 600;
        border: 1.5px solid var(--border);
        background: var(--surface);
        color: var(--muted);
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .cat-pill:hover { border-color: var(--accent); color: var(--accent); }
    .cat-pill.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    /* ── Book Card ── */
    .book-card {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(.4,0,.2,1);
        display: flex;
        flex-direction: column;
    }
    .book-card:hover {
        border-color: #c7d2fe;
        box-shadow: 0 8px 32px rgba(99,102,241,0.10);
        transform: translateY(-3px);
    }

    .book-img-wrap {
        position: relative;
        height: 200px;
        background: #f4f4f5;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .book-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .book-card:hover .book-img-wrap img { transform: scale(1.06); }

    .kondisi-badge {
        position: absolute;
        top: 10px; left: 10px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        backdrop-filter: blur(6px);
    }
    .kondisi-baik   { background: #d1fae5; color: #065f46; }
    .kondisi-lecet  { background: #fef3c7; color: #92400e; }
    .kondisi-rusak  { background: #fee2e2; color: #991b1b; }

    .book-body { padding: 16px; flex: 1; display: flex; flex-direction: column; gap: 8px; }

    .cat-tag {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        background: var(--accent-light);
        color: var(--accent);
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .book-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--primary);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-price {
        font-size: 17px;
        font-weight: 800;
        color: var(--accent);
    }
    .book-price span { font-size: 11px; font-weight: 500; color: var(--muted); }

    .book-desc {
        font-size: 12px;
        color: var(--muted);
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .stock-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0 0;
        border-top: 1.5px solid var(--border);
        margin-top: auto;
    }
    .stock-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; }
    .stock-val { font-size: 20px; font-weight: 800; color: var(--primary); }
    .stock-total { font-size: 11px; color: var(--muted); font-weight: 500; }

    /* ── Action Buttons ── */
    .book-actions {
        display: flex;
        gap: 8px;
        padding: 12px 16px;
        border-top: 1.5px solid var(--border);
        background: #fafafa;
    }
    .btn-edit, .btn-del {
        flex: 1;
        padding: 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    .btn-edit { background: var(--accent-light); color: var(--accent); }
    .btn-edit:hover { background: var(--accent); color: #fff; }
    .btn-del { background: #fee2e2; color: var(--danger); }
    .btn-del:hover { background: var(--danger); color: #fff; }

    /* ── Buttons ── */
    .btn-primary {
        background: var(--primary);
        color: #fff;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-primary:hover { background: var(--primary-soft); transform: translateY(-1px); }

    .btn-outline {
        background: var(--surface);
        color: var(--primary);
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-outline:hover { border-color: var(--primary); }

    /* ── Modal ── */
    .modal-backdrop {
        position: fixed; inset: 0; z-index: 50;
        display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-backdrop.open { display: flex; }
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.35);
        backdrop-filter: blur(3px);
    }
    .modal-box {
        background: var(--surface);
        border-radius: 20px;
        width: 100%; max-width: 520px;
        padding: 32px;
        position: relative; z-index: 10;
        box-shadow: 0 24px 64px rgba(0,0,0,0.15);
        max-height: 90vh;
        overflow-y: auto;
    }
    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 24px;
    }
    .modal-title span { color: var(--accent); }

    .form-label {
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 6px;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13.5px;
        color: var(--primary);
        background: #fafafa;
        outline: none;
        transition: border 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .form-textarea { resize: none; }

    .file-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px dashed var(--border);
        border-radius: 10px;
        font-size: 12.5px;
        background: #fafafa;
        cursor: pointer;
        transition: border 0.2s;
    }
    .file-input:hover { border-color: var(--accent); }

    .err { font-size: 11.5px; color: var(--danger); margin-top: 4px; }

    .modal-actions { display: flex; gap: 10px; margin-top: 8px; }
    .btn-cancel {
        flex: 1; padding: 11px;
        border-radius: 10px;
        font-size: 13px; font-weight: 700;
        border: 1.5px solid var(--border);
        background: #fff; color: var(--muted);
        cursor: pointer; transition: all 0.2s;
    }
    .btn-cancel:hover { border-color: var(--primary); color: var(--primary); }
    .btn-save {
        flex: 2; padding: 11px;
        border-radius: 10px;
        font-size: 13px; font-weight: 700;
        background: var(--primary); color: #fff;
        border: none; cursor: pointer; transition: all 0.2s;
    }
    .btn-save:hover { background: var(--accent); }

    /* ── Empty state ── */
    .empty-state {
        grid-column: 1 / -1;
        padding: 80px 20px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 12px;
        background: var(--surface);
        border-radius: 16px;
        border: 1.5px dashed var(--border);
    }

    /* ── Alert ── */
    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 24px;
    }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
</style>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ── Page Header ── --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">E-Pustaka Admin</p>
        <h1 class="text-3xl font-extrabold text-zinc-900">Katalog Buku</h1>
        <p class="text-sm text-zinc-500 mt-1">Kelola stok, harga sewa, dan kondisi buku secara real-time</p>
    </div>

    <div class="flex items-center gap-3 flex-wrap">
        {{-- Search --}}
        <form action="{{ route('admin.alat') }}" method="GET" class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400" style="font-size:12px;"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari buku..." class="search-input">
        </form>

        {{-- Export --}}
        <a href="{{ route('admin.alat.export_pdf', request()->only(['kategori_id', 'search'])) }}" class="btn-outline">
            <i class="fas fa-file-pdf" style="color:#ef4444;"></i> Export PDF
        </a>

        {{-- Tambah --}}
        <button onclick="openModal('modal-tambah')" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Buku
        </button>
    </div>
</div>

{{-- ── Category Filter ── --}}
<div class="flex items-center gap-2 mb-8 overflow-x-auto pb-1">
    <a href="{{ route('admin.alat') }}"
        class="cat-pill {{ !request('kategori_id') ? 'active' : '' }}">
        <i class="fas fa-th-large" style="font-size:10px;"></i> Semua
    </a>
    @foreach($kategoris as $kat)
    <a href="{{ route('admin.alat', ['kategori_id' => $kat->id, 'search' => request('search')]) }}"
        class="cat-pill {{ request('kategori_id') == $kat->id ? 'active' : '' }}">
        <i class="fas {{ $kat->icon }}" style="font-size:10px;"></i> {{ $kat->nama }}
    </a>
    @endforeach
</div>

{{-- ── Stats Row ── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-zinc-200 p-5">
        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Judul</p>
        <p class="text-3xl font-extrabold text-zinc-900">{{ $alats->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-zinc-200 p-5">
        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Unit</p>
        <p class="text-3xl font-extrabold text-zinc-900">{{ $alats->sum('stok_total') }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-zinc-200 p-5">
        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-1">Tersedia</p>
        <p class="text-3xl font-extrabold text-emerald-600">{{ $alats->sum('stok_tersedia') }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-zinc-200 p-5">
        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-1">Dipinjam</p>
        <p class="text-3xl font-extrabold text-indigo-600">{{ $alats->sum('stok_total') - $alats->sum('stok_tersedia') }}</p>
    </div>
</div>

{{-- ── Book Grid ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @forelse($alats as $alat)
    <div class="book-card">
        {{-- Image --}}
        <div class="book-img-wrap">
            <span class="kondisi-badge kondisi-{{ $alat->kondisi }}">{{ $alat->kondisi }}</span>
            @if($alat->foto)
                <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}">
            @else
                <div class="flex flex-col items-center gap-2">
                    <i class="fas fa-book-open text-5xl" style="color:#d4d4d8;"></i>
                    <span style="font-size:11px; color:#a1a1aa; font-weight:600;">No Image</span>
                </div>
            @endif
        </div>

        {{-- Body --}}
        <div class="book-body">
            <span class="cat-tag">{{ $alat->kategori->nama ?? '-' }}</span>
            <h3 class="book-title">{{ $alat->nama_alat }}</h3>
            <p class="book-price">
                Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}
                <span>/ hari</span>
            </p>
            <p class="book-desc">
                {{ $alat->deskripsi ?? 'Belum ada deskripsi untuk buku ini.' }}
            </p>

            {{-- Stock --}}
            <div class="stock-row">
                <div>
                    <p class="stock-label">Stok</p>
                    <p class="stock-val">
                        {{ $alat->stok_tersedia }}
                        <span class="stock-total">/ {{ $alat->stok_total }}</span>
                    </p>
                </div>
                {{-- Stock bar --}}
                <div style="width:80px;">
                    <div style="height:6px; background:#f4f4f5; border-radius:999px; overflow:hidden;">
                        @php
                            $pct = $alat->stok_total > 0
                                ? round(($alat->stok_tersedia / $alat->stok_total) * 100)
                                : 0;
                            $barColor = $pct > 50 ? '#10b981' : ($pct > 20 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div style="height:100%; width:{{ $pct }}%; background:{{ $barColor }}; border-radius:999px; transition:width 0.4s;"></div>
                    </div>
                    <p style="font-size:10px; color:#a1a1aa; font-weight:600; margin-top:3px; text-align:right;">{{ $pct }}%</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="book-actions">
            <button class="btn-edit"
                onclick="openEditAlat(
                    '{{ $alat->id }}',
                    '{{ addslashes($alat->nama_alat) }}',
                    '{{ $alat->kategori_id }}',
                    '{{ $alat->stok_total }}',
                    '{{ $alat->kondisi }}',
                    '{{ addslashes($alat->deskripsi ?? '') }}',
                    '{{ $alat->foto ? asset('storage/' . $alat->foto) : '' }}',
                    '{{ (int) $alat->harga_sewa }}'
                )">
                <i class="fas fa-pen" style="font-size:11px;"></i> Edit
            </button>
            <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" style="flex:1;"
                onsubmit="return confirm('Hapus buku ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-del" style="width:100%;">
                    <i class="fas fa-trash" style="font-size:11px;"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-book-open text-5xl" style="color:#d4d4d8;"></i>
        <p style="font-size:14px; font-weight:700; color:#a1a1aa;">Tidak ada buku ditemukan</p>
        <button onclick="openModal('modal-tambah')" class="btn-primary" style="margin-top:8px;">
            <i class="fas fa-plus"></i> Tambah Buku Pertama
        </button>
    </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL TAMBAH                          --}}
{{-- ══════════════════════════════════════ --}}
<div id="modal-tambah" class="modal-backdrop">
    <div class="modal-overlay" onclick="closeModal('modal-tambah')"></div>
    <div class="modal-box">
        <h2 class="modal-title">Tambah <span>Buku Baru</span></h2>

        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="form-label">Nama Buku <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required
                    placeholder="Contoh: Laskar Pelangi" class="form-input">
                @error('nama_alat')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kategori <span style="color:var(--danger)">*</span></label>
                    <select name="kategori_id" required class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Kondisi <span style="color:var(--danger)">*</span></label>
                    <select name="kondisi" required class="form-select">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik"  {{ old('kondisi') === 'baik'  ? 'selected' : '' }}>Baik</option>
                        <option value="lecet" {{ old('kondisi') === 'lecet' ? 'selected' : '' }}>Lecet</option>
                        <option value="rusak" {{ old('kondisi') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    @error('kondisi')<p class="err">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Stok <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="stok_total" value="{{ old('stok_total') }}"
                        required min="1" placeholder="0" class="form-input">
                    @error('stok_total')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Sewa/Hari (Rp) <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="harga_sewa" value="{{ old('harga_sewa') }}"
                        required min="1000" step="1000" placeholder="50000" class="form-input">
                    @error('harga_sewa')<p class="err">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Sinopsis atau keterangan buku..."
                    class="form-textarea">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="form-label">Foto Buku</label>
                <input type="file" name="foto" accept="image/*" id="tambah-foto-input" class="file-input">
                <img id="tambah-preview" src="" alt="Preview"
                    class="mt-3 h-24 w-auto object-cover rounded-xl border border-zinc-200 hidden">
                @error('foto')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeModal('modal-tambah')" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-save">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL EDIT                            --}}
{{-- ══════════════════════════════════════ --}}
<div id="modal-edit" class="modal-backdrop">
    <div class="modal-overlay" onclick="closeModal('modal-edit')"></div>
    <div class="modal-box">
        <h2 class="modal-title">Edit <span>Data Buku</span></h2>

        <form id="form-edit" action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="flex justify-center mb-2">
                <img id="edit-preview" src="" alt="Preview"
                    class="h-24 w-auto object-cover rounded-xl border border-zinc-200 hidden">
            </div>

            <div>
                <label class="form-label">Nama Buku <span style="color:var(--danger)">*</span></label>
                <input type="text" id="edit-nama" name="nama_alat" required class="form-input">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kategori <span style="color:var(--danger)">*</span></label>
                    <select id="edit-kategori" name="kategori_id" required class="form-select">
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Kondisi <span style="color:var(--danger)">*</span></label>
                    <select id="edit-kondisi" name="kondisi" required class="form-select">
                        <option value="baik">Baik</option>
                        <option value="lecet">Lecet</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Stok</label>
                    <input type="number" id="edit-stok" name="stok_total" required min="1" class="form-input">
                </div>
                <div>
                    <label class="form-label">Sewa/Hari (Rp)</label>
                    <input type="number" id="edit-harga" name="harga_sewa" required min="1000" step="1000" class="form-input">
                </div>
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea id="edit-deskripsi" name="deskripsi" rows="3" class="form-textarea"></textarea>
            </div>

            <div>
                <label class="form-label">Ganti Foto</label>
                <input type="file" name="foto" accept="image/*" id="edit-foto-input" class="file-input">
                <p style="font-size:11px; color:#a1a1aa; margin-top:4px;">Kosongkan jika tidak ingin mengganti foto</p>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeModal('modal-edit')" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-save">Update Buku</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
    // Legacy support untuk toggleModal yang mungkin dipakai di tempat lain
    function toggleModal(id) {
        const el = document.getElementById(id);
        el.classList.contains('open') ? closeModal(id) : openModal(id);
    }

    function openEditAlat(id, nama, kategoriId, stok, kondisi, deskripsi, fotoUrl, hargaSewa) {
        document.getElementById('form-edit').action = '/admin/alat/' + id;
        document.getElementById('edit-nama').value      = nama;
        document.getElementById('edit-stok').value      = stok;
        document.getElementById('edit-kondisi').value   = kondisi;
        document.getElementById('edit-deskripsi').value = deskripsi;
        document.getElementById('edit-harga').value     = hargaSewa;

        const sel = document.getElementById('edit-kategori');
        for (let i = 0; i < sel.options.length; i++) {
            if (String(sel.options[i].value) === String(kategoriId)) {
                sel.selectedIndex = i; break;
            }
        }

        const preview = document.getElementById('edit-preview');
        if (fotoUrl && fotoUrl.trim() !== '') {
            preview.src = fotoUrl;
            preview.classList.remove('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
        }

        document.getElementById('edit-foto-input').value = '';
        openModal('modal-edit');
    }

    document.getElementById('edit-foto-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const p = document.getElementById('edit-preview');
            p.src = e.target.result;
            p.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('tambah-foto-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const p = document.getElementById('tambah-preview');
            p.src = e.target.result;
            p.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    window.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-backdrop.open').forEach(m => {
                m.classList.remove('open');
                document.body.style.overflow = '';
            });
        }
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000, showConfirmButton: false,
            toast: true, position: 'top-end',
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 4000, showConfirmButton: true,
            toast: true, position: 'top-end',
        });
    @endif
</script>
@endsection