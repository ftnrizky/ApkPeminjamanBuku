@extends('layouts.peminjam')

@section('title', 'Riwayat Peminjaman')

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
  --blue: #2563EB;
  --blue-bg: #EFF6FF;
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

/* ── Header ── */
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
  margin-bottom: 24px;
}

/* ── Table Card ── */
.table-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

/* ── Table ── */
.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead tr {
  background: var(--surface-2);
  border-bottom: 1px solid var(--border);
}

.data-table th {
  padding: 13px 20px;
  font-size: 9.5px;
  font-weight: 700;
  color: var(--ink-3);
  text-transform: uppercase;
  letter-spacing: 0.9px;
  white-space: nowrap;
}

.data-table th:first-child { text-align: left; }
.data-table th.center { text-align: center; }

.data-table tbody tr {
  border-bottom: 1px solid var(--border);
  transition: background 0.15s;
}

.data-table tbody tr:last-child { border-bottom: none; }

.data-table tbody tr:hover { background: var(--surface-2); }

.data-table td {
  padding: 14px 20px;
  vertical-align: middle;
}

.data-table td.center { text-align: center; }

/* ── Code Badge ── */
.code-badge {
  display: inline-block;
  font-family: 'Syne', monospace;
  font-size: 10.5px;
  font-weight: 700;
  background: var(--surface-2);
  border: 1px solid var(--border);
  color: var(--ink);
  padding: 4px 10px;
  border-radius: var(--radius-sm);
  letter-spacing: 0.2px;
  transition: all 0.15s;
}

tr:hover .code-badge {
  background: var(--ink);
  color: white;
  border-color: var(--ink);
}

/* ── Book Cell ── */
.book-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.book-thumb {
  width: 36px; height: 36px;
  border-radius: var(--radius-sm);
  background: var(--surface-2);
  border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
  flex-shrink: 0;
  transition: transform 0.2s;
}

tr:hover .book-thumb { transform: scale(1.08); }

.book-thumb img { width: 100%; height: 100%; object-fit: cover; }
.book-thumb i { font-size: 14px; color: var(--ink-3); }

.book-cell-info {}
.book-cell-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--ink);
  line-height: 1.3;
}

.book-cell-sub {
  font-size: 10px;
  color: var(--ink-3);
  font-weight: 500;
  margin-top: 1px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

/* ── Qty ── */
.qty-val {
  font-family: 'Syne', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: var(--ink);
}

/* ── Status Badge ── */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 10px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 100px;
  text-transform: capitalize;
  letter-spacing: 0.2px;
}

.status-pending  { background: var(--amber-bg); color: var(--amber); border: 1px solid #FDE68A; }
.status-disetujui{ background: var(--blue-bg);  color: var(--blue);  border: 1px solid #BFDBFE; }
.status-selesai  { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
.status-ditolak  { background: var(--red-bg);   color: var(--red);   border: 1px solid #FECACA; }
.status-default  { background: var(--surface-2);color: var(--ink-2); border: 1px solid var(--border); }

/* ── Denda ── */
.denda-val {
  font-family: 'Syne', sans-serif;
  font-size: 13px;
  font-weight: 700;
}

.denda-val.ada  { color: var(--red); }
.denda-val.none { color: var(--ink-3); font-size: 12px; font-weight: 500; }

/* ── Empty ── */
.empty-row td {
  padding: 72px 24px;
  text-align: center;
}

.empty-icon { font-size: 32px; color: var(--border-2); margin-bottom: 12px; }
.empty-title {
  font-family: 'Syne', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 5px;
}

.empty-sub { font-size: 12px; color: var(--ink-3); }

/* ── Pagination ── */
.pagination-wrap {
  padding: 16px 20px;
  border-top: 1px solid var(--border);
  background: var(--surface-2);
}

/* Override default pagination if needed */
.pagination-wrap nav { display: flex; justify-content: flex-end; }

@media (max-width: 640px) {
  .data-table thead { display: none; }
  .data-table tbody tr {
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding: 14px;
    gap: 8px;
    border-bottom: 1px solid var(--border);
  }
  .data-table td { padding: 0; }
  .data-table td.center { text-align: left; }
}
</style>

<div class="page-wrap">

  <div class="page-eyebrow">E-Pustaka</div>
  <div class="page-title">Riwayat Peminjaman</div>

  <div class="table-card">
    <div style="overflow-x: auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Buku</th>
            <th class="center">Qty</th>
            <th class="center">Status</th>
            <th class="center">Denda</th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayat as $item)
            @php
              $statusClass = match($item->status) {
                'pending'   => 'status-pending',
                'disetujui' => 'status-disetujui',
                'selesai'   => 'status-selesai',
                'ditolak'   => 'status-ditolak',
                default     => 'status-default',
              };
              $statusIcon = match($item->status) {
                'pending'   => 'fa-clock',
                'disetujui' => 'fa-check',
                'selesai'   => 'fa-check-double',
                'ditolak'   => 'fa-xmark',
                default     => 'fa-circle',
              };
            @endphp
            <tr style="animation: fadeUp {{ 0.05 + ($loop->index * 0.03) }}s ease both;">
              <td>
                <span class="code-badge">PJM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
              </td>
              <td>
                <div class="book-cell">
                  <div class="book-thumb">
                    @if($item->alat->foto)
                      <img src="{{ asset('storage/' . $item->alat->foto) }}" alt="{{ $item->alat->nama_alat }}">
                    @else
                      <i class="fas fa-book"></i>
                    @endif
                  </div>
                  <div class="book-cell-info">
                    <div class="book-cell-name">{{ $item->alat->nama_alat }}</div>
                    <div class="book-cell-sub">{{ $item->alat->kategoris ?? '-' }}</div>
                  </div>
                </div>
              </td>
              <td class="center">
                <span class="qty-val">{{ $item->jumlah }}</span>
              </td>
              <td class="center">
                <span class="status-badge {{ $statusClass }}">
                  <i class="fas {{ $statusIcon }}" style="font-size:8px;"></i>
                  {{ ucfirst($item->status) }}
                </span>
              </td>
              <td class="center">
                @if($item->total_denda > 0)
                  <span class="denda-val ada">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                @else
                  <span class="denda-val none">—</span>
                @endif
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="5">
                <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                <div class="empty-title">Belum Ada Riwayat</div>
                <div class="empty-sub">Aktivitas peminjaman kamu akan muncul di sini</div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination-wrap">
      {{ $riwayat->links() }}
    </div>
  </div>

</div>

@endsection