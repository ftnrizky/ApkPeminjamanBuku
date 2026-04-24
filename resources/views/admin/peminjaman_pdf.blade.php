<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 9px; }
        body { font-family: Arial, sans-serif; color: #1a1a1a; background: #fff; line-height: 1.2; }
        .page { width: 210mm; height: 297mm; padding: 8mm 8mm; margin: 0 auto; display: flex; flex-direction: column; }
        @media print {
            body { margin: 0; padding: 0; }
            .page { max-width: 100%; height: auto; padding: 8mm; margin: 0; }
        }
        .header { margin-bottom: 6px; border-bottom: 1.5px solid #0ea5e9; padding-bottom: 5px; flex-shrink: 0; }
        .header-row { display: flex; justify-content: space-between; align-items: flex-start; }
        .brand { font-size: 12px; font-weight: 800; color: #0f172a; }
        .subtitle { font-size: 7px; color: #475569; margin-top: 1px; }
        .meta { font-size: 7px; color: #475569; text-align: right; }
        .meta-line { margin: 1px 0; }
        .summary { margin: 4px 0; display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; flex-shrink: 0; }
        .summary-card { background: #f8fafc; border: 1px solid #cbd5e1; padding: 4px 6px; border-radius: 3px; text-align: center; }
        .summary-label { font-size: 6px; color: #475569; text-transform: uppercase; margin-bottom: 1px; }
        .summary-value { font-size: 10px; font-weight: 800; color: #0f172a; }
        .content { flex: 1; display: flex; flex-direction: column; min-height: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 7px; }
        th, td { padding: 2px 2px; border: 0.5px solid #cbd5e1; }
        th { background: #0ea5e9; color: #fff; text-align: left; text-transform: uppercase; font-weight: 700; letter-spacing: 0.03em; line-height: 1.1; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status-badge { padding: 1px 3px; border-radius: 2px; font-size: 6px; font-weight: 700; text-transform: uppercase; }
        .status-disetujui { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        .status-selesai { background: #dbeafe; color: #1e40af; }
        .status-dikembalikan { background: #d1fae5; color: #065f46; }
        .footer { margin-top: 4px; padding-top: 3px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 6px; color: #64748b; flex-shrink: 0; }
        .no-data { text-align: center; padding: 15px 0; color: #64748b; font-size: 7px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-row">
                <div>
                    <div class="brand">E-PUSTAKA Management</div>
                    <div class="subtitle">Laporan Transaksi Peminjaman</div>
                </div>
                <div class="meta">
                    <div class="meta-line">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB</div>
                    <div class="meta-line">Total: <strong>{{ $totalPeminjaman ?? 0 }} Peminjaman</strong></div>
                </div>
            </div>
        </div>

        <div class="summary">
            <div class="summary-card">
                <div class="summary-label">Total</div>
                <div class="summary-value">{{ $totalPeminjaman ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Disetujui</div>
                <div class="summary-value">{{ $totalDisetujui ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Pending</div>
                <div class="summary-value">{{ $totalPending ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Ditolak</div>
                <div class="summary-value">{{ $totalDitolak ?? 0 }}</div>
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 7%;">Kode</th>
                        <th style="width: 14%;">Peminjam</th>
                        <th style="width: 16%;">buku</th>
                        <th style="width: 5%;" class="text-center">Qty</th>
                        <th style="width: 9%;" class="text-center">Pinjam</th>
                        <th style="width: 9%;" class="text-center">Kembali</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $peminjaman)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>PJM-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ Str::limit($peminjaman->user->name, 13) }}</td>
                            <td>{{ Str::limit($peminjaman->alat->nama_alat, 15) }}</td>
                            <td class="text-center">{{ $peminjaman->jumlah }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d/m/y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d/m/y') }}</td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $peminjaman->status }}">
                                    {{ Str::limit(ucfirst($peminjaman->status), 7) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div>E-PUSTAKA System | Laporan Peminjaman</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>
</html>