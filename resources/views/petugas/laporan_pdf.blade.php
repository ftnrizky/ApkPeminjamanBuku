<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman - E-PUSTAKA</title>
    <style>
        /* Konfigurasi Halaman */
        @page {
            size: A4;
            margin: 20mm 15mm;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* Header Laporan */
        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-top {
            width: 100%;
            margin-bottom: 10px;
        }
        .header-title {
            font-size: 18pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 10pt;
            color: #64748b;
            margin-top: 2px;
        }
        .meta-info {
            width: 100%;
            font-size: 9pt;
            color: #475569;
            margin-top: 10px;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 15px;
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            font-size: 8.5pt;
            border: 1px solid #e2e8f0;
            padding: 10px 8px;
        }
        td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Utility */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-red { color: #e11d48; }
        
        /* Summary Box */
        .summary-box {
            float: right;
            width: 250px;
            margin-top: 20px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 10px;
        }
        .summary-row {
            width: 100%;
            font-size: 9pt;
            margin-bottom: 4px;
        }
        .summary-total {
            border-top: 1px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 8px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 5px;
        }
        .page-number:after { content: "Halaman " counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-top">
            <tr>
                <td style="border: none; padding: 0;">
                    <h1 class="header-title">Laporan Peminjaman</h1>
                    <div class="header-subtitle">E-PUSTAKA Management System</div>
                </td>
                <td style="border: none; padding: 0; text-align: right; vertical-align: middle;">
                    <div style="font-weight: bold; font-size: 9pt; color: #0f172a;">ID: PJM-{{ date('YmdH') }}</div>
                </td>
            </tr>
        </table>
        
        <table class="meta-info">
            <tr>
                <td style="border: none; padding: 0;">
                    <strong>Periode:</strong> {{ $tgl_mulai ?? 'Semua' }} s/d {{ $tgl_selesai ?? 'Semua' }}
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <strong>Dicetak:</strong> {{ date('d/m/Y H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th style="width: 100px;">ID Transaksi</th>
                <th>Peminjam</th>
                <th>Item buku</th>
                <th style="width: 40px;" class="text-center">Qty</th>
                <th style="width: 90px;" class="text-center">Tgl Pinjam</th>
                <th style="width: 80px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#PJM-{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="font-bold">{{ $laporan->user->name ?? '-' }}</td>
                <td>{{ $laporan->alat->nama_alat ?? '-' }}</td>
                <td class="text-center">{{ $laporan->jumlah }}</td>
                <td class="text-center">{{ $laporan->created_at->format('d/m/Y') }}</td>
                <td class="text-center">{{ strtoupper($laporan->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; clear: both;">
        <div class="summary-box">
            <table style="width: 100%; border: none; margin: 0;">
                <tr>
                    <td style="border: none; padding: 2px;">Total Transaksi:</td>
                    <td style="border: none; padding: 2px; text-align: right;">{{ $laporans->count() }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px;">Total Unit:</td>
                    <td style="border: none; padding: 2px; text-align: right;">{{ $laporans->sum('jumlah') }}</td>
                </tr>
                <tr class="summary-total">
                    <td style="border: none; padding: 8px 2px 2px 2px; border-top: 1px solid #e2e8f0;">TOTAL DENDA:</td>
                    <td style="border: none; padding: 8px 2px 2px 2px; border-top: 1px solid #e2e8f0; text-align: right;" class="text-red">Rp{{ number_format($totalDenda ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left; width: 33%;">E-PUSTAKA System</td>
                <td style="border: none; text-align: center; width: 33%;" class="page-number"></td>
                <td style="border: none; text-align: right; width: 33%;">Oleh: {{ Auth::user()->name ?? 'Admin' }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
