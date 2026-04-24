@extends('layouts.peminjam')

@section('title', 'Pengembalian Buku')

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
  --red-border: #FECACA;
  --amber: #D97706;
  --amber-bg: #FFFBEB;
  --amber-border: #FDE68A;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 18px;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
  --shadow-md: 0 4px 16px rgba(0,0,0,0.07);
}

* { box-sizing: border-box; }
body { font-family: 'Instrument Sans', sans-serif !important; background: var(--bg) !important; -webkit-font-smoothing: antialiased; }

.page-wrap { animation: fadeUp 0.4s ease both; display: flex; flex-direction: column; gap: 20px; }
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Header ── */
.page-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
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
  font-size: clamp(22px, 3vw, 30px);
  font-weight: 800;
  color: var(--ink);
  letter-spacing: -0.7px;
  line-height: 1.1;
}

.success-chip {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: var(--green-bg);
  border: 1px solid var(--green-border);
  color: var(--green);
  font-size: 11px;
  font-weight: 700;
  padding: 8px 14px;
  border-radius: 100px;
}

/* ── Info Banner ── */
.info-banner {
  background: var(--amber-bg);
  border: 1px solid var(--amber-border);
  border-radius: var(--radius-md);
  padding: 16px 20px;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 14px;
  align-items: start;
}

.info-banner-icon {
  width: 34px; height: 34px;
  background: #FEF3C7;
  border: 1px solid var(--amber-border);
  border-radius: var(--radius-sm);
  display: flex; align-items: center; justify-content: center;
  color: var(--amber);
  font-size: 13px;
  flex-shrink: 0;
}

.info-rules {
  display: flex;
  gap: 28px;
  flex-wrap: wrap;
}

.info-rule-item {}
.info-rule-lbl {
  font-size: 9.5px;
  font-weight: 700;
  color: #92400E;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin-bottom: 2px;
}

.info-rule-val {
  font-size: 12px;
  font-weight: 600;
  color: #78350F;
}

.info-rule-val.red { color: var(--red); }

/* ── Return Card ── */
.return-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: box-shadow 0.2s;
  box-shadow: var(--shadow-sm);
}

.return-card.overdue {
  border-color: #FECACA;
}

.return-card:hover { box-shadow: var(--shadow-md); }

.rc-body {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 0;
  align-items: stretch;
}

.rc-cover {
  width: 110px;
  background: var(--surface-2);
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
  flex-shrink: 0;
}

.rc-cover img { width: 100%; height: 100%; object-fit: cover; }

.rc-cover-placeholder {
  font-size: 28px;
  color: var(--border-2);
}

.rc-info {
  padding: 22px 24px;
  flex: 1;
  border-left: 1px solid var(--border);
  border-right: 1px solid var(--border);
}

.rc-badges {
  display: flex;
  gap: 6px;
  margin-bottom: 10px;
  flex-wrap: wrap;
}

.rc-badge {
  font-size: 9px;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.rc-badge.code { background: var(--ink); color: white; }
.rc-badge.cat  { background: var(--surface-2); color: var(--ink-2); border: 1px solid var(--border); }

.rc-name {
  font-family: 'Syne', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.4px;
  margin-bottom: 16px;
}

.rc-meta-grid {
  display: grid;
  grid-template-columns: repeat(4, auto);
  gap: 20px 32px;
}

.rc-meta-item {}
.rc-meta-lbl {
  font-size: 9px;
  font-weight: 700;
  color: var(--ink-3);
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin-bottom: 3px;
}

.rc-meta-val {
  font-family: 'Syne', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: var(--ink);
  line-height: 1;
}

.rc-meta-val.ok   { color: var(--green); }
.rc-meta-val.late { color: var(--red); }
.rc-meta-val.denda { color: var(--red); }

.rc-action {
  padding: 22px 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  min-width: 160px;
}

.btn-return {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--ink);
  color: white;
  font-size: 12px;
  font-weight: 700;
  padding: 12px 20px;
  border-radius: var(--radius-sm);
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  letter-spacing: 0.2px;
  font-family: 'Instrument Sans', sans-serif;
  white-space: nowrap;
}

.btn-return:hover {
  background: var(--green);
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(22,163,74,0.28);
}

/* ── Empty ── */
.empty-state {
  background: var(--surface);
  border: 1px dashed var(--border-2);
  border-radius: var(--radius-lg);
  padding: 70px 24px;
  text-align: center;
}

.empty-icon { font-size: 36px; color: var(--border-2); margin-bottom: 14px; }

.empty-title {
  font-family: 'Syne', sans-serif;
  font-size: 17px;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.3px;
  margin-bottom: 6px;
}

.empty-sub { font-size: 13px; color: var(--ink-3); margin-bottom: 20px; }

.btn-catalog {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: var(--ink);
  color: white;
  font-size: 12px;
  font-weight: 700;
  padding: 10px 18px;
  border-radius: var(--radius-sm);
  text-decoration: none;
  transition: all 0.2s;
}

.btn-catalog:hover {
  background: var(--green);
  color: white;
  text-decoration: none;
  transform: translateY(-1px);
}

@media (max-width: 768px) {
  .rc-body { grid-template-columns: 1fr; }
  .rc-cover { width: 100%; height: 140px; }
  .rc-info { border-left: none; border-right: none; border-top: 1px solid var(--border); padding: 16px; }
  .rc-meta-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
  .rc-action { border-top: 1px solid var(--border); padding: 16px; min-width: unset; justify-content: stretch; }
  .btn-return { width: 100%; justify-content: center; }
  .info-rules { flex-direction: column; gap: 10px; }
}
</style>

<div class="page-wrap">

  {{-- Header --}}
  <div class="page-header">
    <div>
      <div class="page-eyebrow">E-Pustaka</div>
      <div class="page-title">Pengembalian Buku</div>
    </div>

    @if(session('success'))
      <div class="success-chip">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
      </div>
    @endif
  </div>

  {{-- Info Banner --}}
  <div class="info-banner">
    <div class="info-banner-icon"><i class="fas fa-info"></i></div>
    <div class="info-rules">
      <div class="info-rule-item">
        <div class="info-rule-lbl">Aturan Dasar</div>
        <div class="info-rule-val">Maksimal pinjam 3 hari kerja</div>
      </div>
      <div class="info-rule-item">
        <div class="info-rule-lbl">Denda Terlambat</div>
        <div class="info-rule-val red">Rp 5.000 / hari</div>
      </div>
      <div class="info-rule-item">
        <div class="info-rule-lbl">Denda Kondisi</div>
        <div class="info-rule-val">Rusak / Hilang sesuai nilai buku</div>
      </div>
    </div>
  </div>

  {{-- List --}}
  @forelse($peminjamans as $pinjam)
    <div class="return-card {{ $pinjam->estimasi_denda > 0 ? 'overdue' : '' }}"
         style="animation: fadeUp {{ 0.1 + ($loop->index * 0.06) }}s ease both;">
      <div class="rc-body">

        {{-- Cover --}}
        <div class="rc-cover">
          @if($pinjam->alat->foto)
            <img src="{{ asset('storage/' . $pinjam->alat->foto) }}" alt="{{ $pinjam->alat->nama_alat }}">
          @else
            <div class="rc-cover-placeholder"><i class="fas fa-book"></i></div>
          @endif
        </div>

        {{-- Info --}}
        <div class="rc-info">
          <div class="rc-badges">
            <span class="rc-badge code">PJM-{{ str_pad($pinjam->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="rc-badge cat">{{ $pinjam->alat->kategoris ?? '-' }}</span>
          </div>
          <div class="rc-name">{{ $pinjam->alat->nama_alat }}</div>
          <div class="rc-meta-grid">
            <div class="rc-meta-item">
              <div class="rc-meta-lbl">Jumlah</div>
              <div class="rc-meta-val">{{ $pinjam->jumlah }} <span style="font-size:11px;font-weight:500;color:var(--ink-3);">unit</span></div>
            </div>
            <div class="rc-meta-item">
              <div class="rc-meta-lbl">Batas Kembali</div>
              <div class="rc-meta-val {{ $pinjam->estimasi_denda > 0 ? 'late' : 'ok' }}">
                {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M Y') }}
              </div>
            </div>
            <div class="rc-meta-item">
              <div class="rc-meta-lbl">Estimasi Denda</div>
              <div class="rc-meta-val {{ $pinjam->estimasi_denda > 0 ? 'denda' : '' }}">
                Rp {{ number_format($pinjam->estimasi_denda, 0, ',', '.') }}
              </div>
            </div>
            <div class="rc-meta-item">
              <div class="rc-meta-lbl">Pembayaran</div>
              <div class="rc-meta-val" style="font-size:12px;color:var(--green);">Tunai ke Petugas</div>
            </div>
          </div>
        </div>

        {{-- Action --}}
        <div class="rc-action">
          <form action="{{ route('peminjam.proses_kembali', $pinjam->id) }}" method="POST"
                onsubmit="return confirm('Sudah siap mengembalikan buku?')">
            @csrf
            <button type="submit" class="btn-return">
              <i class="fas fa-check"></i>
              Kembalikan
            </button>
          </form>
        </div>

      </div>
    </div>
  @empty
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-check-double"></i></div>
      <div class="empty-title">Semua Buku Sudah Dikembalikan</div>
      <div class="empty-sub">Tidak ada peminjaman aktif saat ini.</div>
      <a href="{{ route('peminjam.katalog') }}" class="btn-catalog">
        <i class="fas fa-book-open"></i> Pinjam Buku Lagi
      </a>
    </div>
  @endforelse

</div>

@endsection