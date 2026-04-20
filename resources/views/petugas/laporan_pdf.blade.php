<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Laptop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 9px; }
        body { font-family: Arial, sans-serif; font-size: 8px; line-height: 1.2; color: #1a1a1a; background: #fff; }
        .container { width: 210mm; height: 297mm; padding: 8mm 8mm; margin: 0 auto; display: flex; flex-direction: column; }
        @media print {
            body { margin: 0; padding: 0; }
            .container { max-width: 100%; height: auto; padding: 8mm; margin: 0; }
        }
        .header { text-align: center; margin-bottom: 6px; border-bottom: 1.5px solid #06b6d4; padding-bottom: 5px; flex-shrink: 0; }
        .header h1 { font-size: 12px; font-weight: 800; color: #0f172a; margin-bottom: 1px; }
        .header p { font-size: 7px; color: #475569; margin: 0px; line-height: 1.1; }
        .company-name { font-size: 9px; font-weight: 700; color: #06b6d4; }
        .content { flex: 1; display: flex; flex-direction: column; min-height: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 7px; }
        th { background: #06b6d4; color: white; text-transform: uppercase; padding: 2px 2px; text-align: left; font-size: 6px; font-weight: 700; border: 0.5px solid #0891b2; line-height: 1.1; }
        td { border: 0.5px solid #e2e8f0; padding: 2px 2px; font-size: 7px; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .status-badge { display: inline-block; padding: 1px 3px; border-radius: 2px; font-size: 6px; font-weight: 700; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-disetujui { background: #cffafe; color: #0c4a6e; }
        .status-selesai { background: #ccfbf1; color: #134e4a; }
        .status-ditolak { background: #fee2e2; color: #7f1d1d; }
        .summary-section { margin-top: 4px; padding: 6px; background: #f0f9ff; border-left: 3px solid #06b6d4; border-radius: 2px; flex-shrink: 0; }
        .summary-title { font-size: 7px; font-weight: 700; color: #0f172a; margin-bottom: 3px; text-transform: uppercase; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; }
        .summary-item { text-align: center; padding: 4px; background: white; border: 1px solid #e2e8f0; border-radius: 2px; }
        .summary-label { font-size: 6px; color: #475569; text-transform: uppercase; font-weight: 700; margin-bottom: 1px; }
        .summary-value { font-size: 10px; font-weight: 800; color: #06b6d4; }
        .footer { margin-top: 4px; padding-top: 3px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 6px; color: #64748b; flex-shrink: 0; }
        .no-data { text-align: center; padding: 15px 0; color: #64748b; font-size: 7px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">E-Laptop Management System</div>
            <h1>Laporan Peminjaman Laptop</h1>
            <p>Periode: {{ $tgl_mulai ?? 'N/A' }} - {{ $tgl_selesai ?? 'N/A' }} | Dicetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB</p>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 7%;">Kode</th>
                        <th style="width: 14%;">Peminjam</th>
                        <th style="width: 15%;">Laptop</th>
                        <th style="width: 5%;" class="text-center">Qty</th>
                        <th style="width: 8%;" class="text-center">Pinjam</th>
                        <th style="width: 8%;" class="text-center">Kembali</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $index => $laporan)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>PJM-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ Str::limit($laporan->user->name, 12) }}</td>
                            <td>{{ Str::limit($laporan->alat->nama_alat, 14) }}</td>
                            <td class="text-center">{{ $laporan->jumlah }}</td>
                            <td class="text-center">{{ $laporan->created_at->format('d/m/y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($laporan->tgl_kembali)->format('d/m/y') }}</td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $laporan->status }}">
                                    {{ Str::limit(ucfirst($laporan->status), 8) }}
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

        <div class="summary-section">
            <div class="summary-title">📊 Ringkasan Data</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total</div>
                    <div class="summary-value">{{ $laporans->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Pending</div>
                    <div class="summary-value">{{ $laporans->where('status', 'pending')->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Disetujui</div>
                    <div class="summary-value">{{ $laporans->where('status', 'disetujui')->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Selesai</div>
                    <div class="summary-value">{{ $laporans->where('status', 'selesai')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div>E-Laptop System | Laporan Peminjaman</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>
                    <div class="summary-label">Disetujui</div>
                    <div class="summary-value">{{ $laporans->where('status', 'disetujui')->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Selesai</div>
                    <div class="summary-value">{{ $laporans->where('status', 'selesai')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div>E-Laptop System | Petugas: {{ Auth::user()->name ?? 'Administrator' }}</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>
</html>
        
        .footer-left {
            font-size: 10px;
            color: #666;
        }
        
        .footer-right {
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        .generated-date {
            color: #999;
            font-size: 10px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                margin: 0;
                padding: 15mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">E-LAPTOP SYSTEM</div>
            <h1>REKAPITULASI PEMINJAMAN LAPTOP</h1>
            
            @if(isset($tgl_mulai) && isset($tgl_selesai) && $tgl_mulai && $tgl_selesai)
                <div class="period-info">
                    <strong>Periode:</strong> {{ \Carbon\Carbon::parse($tgl_mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tgl_selesai)->translatedFormat('d F Y') }}
                </div>
            @endif
            
            <p class="generated-date">Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y - H:i') }} WIB</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 20%;">Peminjam</th>
                    <th style="width: 25%;">Laptop</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 15%;" class="text-center">Status</th>
                    <th style="width: 13%;" class="text-center">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $data->created_at->translatedFormat('d M Y') }}</td>
                    <td>{{ $data->user->name ?? '-' }}</td>
                    <td>{{ $data->alat->nama_alat ?? '-' }}</td>
                    <td class="text-center">{{ $data->jumlah }} Unit</td>
                    <td class="text-center">
                        <span class="status-badge status-{{ strtolower($data->status) }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $data->alat->kategori ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">📋 Tidak ada data peminjaman untuk periode ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($laporans->count() > 0)
        <div class="summary-section">
            <div class="summary-title">📊 Ringkasan Data</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Transaksi</div>
                    <div class="summary-value">{{ $laporans->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Unit</div>
                    <div class="summary-value">{{ $laporans->sum('jumlah') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Disetujui</div>
                    <div class="summary-value">{{ $laporans->where('status', 'disetujui')->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Selesai</div>
                    <div class="summary-value">{{ $laporans->where('status', 'selesai')->count() }}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="footer">
            <div class="footer-left">
                <strong>E-Laptop Management System</strong><br>
                Sistem Manajemen Peminjaman Laptop Profesional
            </div>
            <div class="footer-right">
                Halaman: <span id="page"></span> / <span id="total-pages"></span><br>
                <strong>© 2026 E-Laptop</strong>
            </div>
        </div>
    </div>

    <script>
        // Script untuk nomor halaman saat printing
        function updatePageNumbers() {
            document.getElementById('page').textContent = 1;
            document.getElementById('total-pages').textContent = 1;
        }
        updatePageNumbers();
    </script>
</body>
</html>