@extends('layouts.peminjam')

@section('title', 'Katalog Buku')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap');

:root {
  --bg: #F8F7F4;
  --surface: #FFFFFF;
  --surface-2: #F3F2EF;
  --ink: #111110;
  --ink-2: #6B6A67;
  --ink-3: #A8A7A4;
  --border: #E8E7E3;
  --border-2: #D4D3CF;
  --green: #16A34A;
  --green-bg: #F0FDF4;
  --green-border: #BBF7D0;
  --red: #DC2626;
  --red-bg: #FFF5F5;
  --amber: #D97706;
  --amber-bg: #FFFBEB;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 18px;
}

* { box-sizing: border-box; }
body { font-family: 'Instrument Sans', sans-serif !important; background: var(--bg) !important; -webkit-font-smoothing: antialiased; }

.page-wrap { animation: fadeUp 0.4s ease both; }
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Page Header ── */
.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 28px;
  flex-wrap: wrap;
}

.page-eyebrow {
  font-size: 10px;
  font-weight: 700;
  color: var(--ink-3);
  text-transform: uppercase;
  letter-spacing: 1.2px;
  margin-bottom: 6px;
}

.page-title {
  font-family: 'Syne', sans-serif;
  font-size: clamp(24px, 3vw, 32px);
  font-weight: 800;
  color: var(--ink);
  letter-spacing: -0.8px;
  line-height: 1.1;
}

/* ── Search ── */
.search-wrap {
  position: relative;
}

.search-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--ink-3);
  font-size: 12px;
  pointer-events: none;
  transition: color 0.2s;
}

.search-input {
  width: 240px;
  padding: 10px 14px 10px 38px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  font-size: 12px;
  font-weight: 500;
  color: var(--ink);
  font-family: 'Instrument Sans', sans-serif;
  outline: none;
  transition: all 0.2s;
}

.search-input::placeholder { color: var(--ink-3); }
.search-input:focus {
  border-color: var(--ink);
  box-shadow: 0 0 0 3px rgba(17,17,16,0.06);
}

.search-wrap:focus-within .search-icon { color: var(--ink); }

/* ── Category Filter ── */
.cat-strip {
  display: flex;
  gap: 8px;
  margin-bottom: 28px;
  overflow-x: auto;
  padding-bottom: 4px;
  scrollbar-width: none;
}
.cat-strip::-webkit-scrollbar { display: none; }

.cat-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  border-radius: 100px;
  font-size: 11px;
  font-weight: 700;
  white-space: nowrap;
  text-decoration: none;
  border: 1px solid var(--border);
  background: var(--surface);
  color: var(--ink-2);
  transition: all 0.18s ease;
  letter-spacing: 0.1px;
}

.cat-chip:hover {
  border-color: var(--ink);
  color: var(--ink);
  text-decoration: none;
  background: var(--surface);
}

.cat-chip.active {
  background: var(--ink);
  border-color: var(--ink);
  color: white;
}

.cat-chip i { font-size: 10px; }

/* ── Book Grid ── */
.book-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
  gap: 16px;
}

/* ── Book Card ── */
.book-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: all 0.22s ease;
  display: flex;
  flex-direction: column;
}

.book-card:hover {
  border-color: var(--border-2);
  box-shadow: 0 8px 28px rgba(0,0,0,0.09);
  transform: translateY(-3px);
}

.book-img-wrap {
  position: relative;
  height: 200px;
  background: var(--surface-2);
  overflow: hidden;
}

.book-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.book-card:hover .book-img-wrap img { transform: scale(1.05); }

.book-img-placeholder {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  font-size: 36px;
  color: var(--border-2);
  transition: color 0.2s;
}

.book-card:hover .book-img-placeholder { color: var(--ink-3); }

.badge-kondisi {
  position: absolute;
  top: 10px; left: 10px;
  font-size: 9px;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  backdrop-filter: blur(4px);
}

.badge-kondisi.baik   { background: rgba(240,253,244,0.95); color: var(--green); border: 1px solid var(--green-border); }
.badge-kondisi.lecet  { background: rgba(255,251,235,0.95); color: var(--amber); border: 1px solid #FDE68A; }
.badge-kondisi.rusak  { background: rgba(255,245,245,0.95); color: var(--red);   border: 1px solid #FECACA; }

.badge-stok {
  position: absolute;
  bottom: 10px; right: 10px;
  font-size: 9px;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.badge-stok.ada    { background: var(--ink); color: white; }
.badge-stok.habis  { background: rgba(0,0,0,0.35); color: rgba(255,255,255,0.7); }

/* ── Book Body ── */
.book-body {
  padding: 16px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.book-cat {
  font-size: 9.5px;
  font-weight: 700;
  color: var(--ink-3);
  text-transform: uppercase;
  letter-spacing: 0.8px;
  margin-bottom: 5px;
  display: flex; align-items: center; gap: 5px;
}

.book-name {
  font-family: 'Syne', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.3px;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.book-price {
  font-size: 13px;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 14px;
}

.book-price span {
  font-size: 10px;
  font-weight: 500;
  color: var(--ink-3);
}

.book-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 12px;
  border-top: 1px solid var(--border);
  margin-top: auto;
}

.stok-info {}
.stok-lbl { font-size: 9px; font-weight: 700; color: var(--ink-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
.stok-val { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: var(--ink); line-height: 1; }
.stok-val span { font-size: 10px; font-weight: 500; color: var(--ink-3); }

.btn-pinjam {
  width: 40px; height: 40px;
  border-radius: var(--radius-sm);
  background: var(--ink);
  color: white;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.btn-pinjam:hover {
  background: var(--green);
  transform: scale(1.08);
  color: white;
  text-decoration: none;
}

.btn-pinjam-disabled {
  width: 40px; height: 40px;
  border-radius: var(--radius-sm);
  background: var(--surface-2);
  color: var(--border-2);
  display: flex; align-items: center; justify-content: center;
  font-size: 14px;
  cursor: not-allowed;
  border: none;
  flex-shrink: 0;
}

/* ── Empty ── */
.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 80px 24px;
}

.empty-icon {
  font-size: 40px;
  color: var(--border-2);
  margin-bottom: 16px;
}

.empty-title {
  font-family: 'Syne', sans-serif;
  font-size: 17px;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 6px;
}

.empty-sub { font-size: 13px; color: var(--ink-3); }

@media (max-width: 640px) {
  .page-header { flex-direction: column; align-items: flex-start; }
  .search-input { width: 100%; }
  .search-wrap { width: 100%; }
  .book-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
  .book-img-wrap { height: 160px; }
}
</style>

<div class="page-wrap">

  {{-- Header --}}
  <div class="page-header">
    <div>
      <div class="page-eyebrow">E-Pustaka</div>
      <div class="page-title">Katalog Buku</div>
    </div>

    <form action="{{ route('peminjam.katalog') }}" method="GET" class="search-wrap">
      @if(request('kategori'))
        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
      @endif
      <i class="fas fa-search search-icon"></i>
      <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Cari judul, kategori..."
        class="search-input">
    </form>
  </div>

  {{-- Category Filter --}}
  <div class="cat-strip">
    @php $kategoriList = \App\Models\Kategori::all(); @endphp

    <a href="{{ route('peminjam.katalog', ['kategori' => 'Semua', 'search' => request('search')]) }}"
      class="cat-chip {{ request('kategori', 'Semua') == 'Semua' ? 'active' : '' }}">
      <i class="fas fa-th-large"></i> Semua
    </a>

    @foreach($kategoriList as $kategori)
      <a href="{{ route('peminjam.katalog', ['kategori' => $kategori->nama, 'search' => request('search')]) }}"
        class="cat-chip {{ request('kategori') == $kategori->nama ? 'active' : '' }}">
        <i class="fas {{ $kategori->icon ?? 'fa-book' }}"></i>
        {{ $kategori->nama }}
      </a>
    @endforeach
  </div>

  {{-- Grid --}}
  <div class="book-grid">
    @forelse($alats as $alat)
      @php
        $kondisiClass = match($alat->kondisi) {
          'baik'  => 'baik',
          'lecet' => 'lecet',
          default => 'rusak',
        };
      @endphp
      <div class="book-card" style="animation: fadeUp {{ 0.05 + ($loop->index * 0.04) }}s ease both;">

        <div class="book-img-wrap">
          <span class="badge-kondisi {{ $kondisiClass }}">{{ ucfirst($alat->kondisi) }}</span>

          @if($alat->foto)
            <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama_alat }}">
          @else
            <div class="book-img-placeholder"><i class="fas fa-book"></i></div>
          @endif

          <span class="badge-stok {{ $alat->stok_tersedia > 0 ? 'ada' : 'habis' }}">
            {{ $alat->stok_tersedia > 0 ? 'Tersedia' : 'Kosong' }}
          </span>
        </div>

        <div class="book-body">
          <div class="book-cat">
            <i class="fas fa-tag"></i>
            {{ $alat->kategori->nama ?? '-' }}
          </div>
          <div class="book-name">{{ $alat->nama_alat }}</div>
          <div class="book-price">
            Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}
            <span>/ hari</span>
          </div>

          <div class="book-footer">
            <div class="stok-info">
              <div class="stok-lbl">Stok</div>
              <div class="stok-val">{{ $alat->stok_tersedia }} <span>unit</span></div>
            </div>

            @if($alat->stok_tersedia > 0)
              <a href="{{ route('peminjam.ajukan', $alat->id) }}" class="btn-pinjam">
                <i class="fas fa-plus"></i>
              </a>
            @else
              <button disabled class="btn-pinjam-disabled">
                <i class="fas fa-ban"></i>
              </button>
            @endif
          </div>
        </div>

      </div>
    @empty
      <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-search"></i></div>
        <div class="empty-title">Buku Tidak Ditemukan</div>
        <div class="empty-sub">Coba ubah filter atau kata kunci pencarian</div>
      </div>
    @endforelse
  </div>

</div>

@endsection