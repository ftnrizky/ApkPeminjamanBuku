<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Denda - E-PUSTAKA</title>
    <style>
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

        .header {
            border-bottom: 2px solid #e11d48;
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
            background-color: #fff1f2;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-red { color: #e11d48; }
        
        .summary-box {
            float: right;
            width: 280px;
            margin-top: 20px;
            border: 1px solid #e2e8f0;
            background-color: #fff1f2;
            padding: 10px;
            border-radius: 4px;
        }
        .summary-total {
            border-top: 1.5px solid #fecdd3;
            margin-top: 8px;
            padding-top: 8px;
            font-weight: bold;
            color: #be123c;
            font-size: 11pt;
        }

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
                    <h1 class="header-title">Rekapitulasi Denda</h1>
                    <div class="header-subtitle">E-PUSTAKA Management System</div>
                </td>
                <td style="border: none; padding: 0; text-align: right; vertical-align: middle;">
                    <div style="font-weight: bold; font-size: 9pt; color: #0f172a;">ID: DND-{{ date('YmdH') }}</div>
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
                <th style="width: 80px;" class="text-center">Tanggal</th>
                <th>Peminjam</th>
                <th>buku</th>
                <th style="width: 100px;" class="text-right">Nominal</th>
                <th style="width: 70px;" class="text-center">Metode</th>
                <th style="width: 80px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->created_at->format('d/m/y') }}</td>
                <td class="font-bold">{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->alat->nama_alat ?? '-' }}</td>
                <td class="text-right font-bold">Rp{{ number_format($item->total_denda, 0, ',', '.') }}</td>
                <td class="text-center">{{ strtoupper($item->metode_bayar ?? '-') }}</td>
                <td class="text-center">{{ $item->is_denda_lunas ? 'LUNAS' : 'BELUM' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; clear: both;">
        <div class="summary-box">
            <table style="width: 100%; border: none; margin: 0;">
                <tr>
                    <td style="border: none; padding: 2px;">Total Kasus:</td>
                    <td style="border: none; padding: 2px; text-align: right;">{{ $data->count() }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px;">Kasus Lunas:</td>
                    <td style="border: none; padding: 2px; text-align: right; color: #10b981;">{{ $data->where('is_denda_lunas', true)->count() }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px;">Kasus Belum Lunas:</td>
                    <td style="border: none; padding: 2px; text-align: right; color: #e11d48;">{{ $data->where('is_denda_lunas', false)->count() }}</td>
                </tr>
                <tr class="summary-total">
                    <td style="border: none; padding: 10px 2px 2px 2px; border-top: 1.5px solid #fecdd3;">TOTAL NOMINAL:</td>
                    <td style="border: none; padding: 10px 2px 2px 2px; border-top: 1.5px solid #fecdd3; text-align: right;">Rp{{ number_format($total_denda ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left; width: 33%;">E-PUSTAKA System</td>
                <td style="border: none; text-align: center; width: 33%;" class="page-number"></td>
                <td style="border: none; text-align: right; width: 33%;">Dokumen Resmi Manajemen</td>
            </tr>
        </table>
    </div>
</body>
</html>
