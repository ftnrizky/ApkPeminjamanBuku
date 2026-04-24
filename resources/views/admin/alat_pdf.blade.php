<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Katalog Alat - E-PUSTAKA</title>
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
            border-bottom: 2px solid #0f172a;
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
            font-size: 8pt;
            border: 1px solid #e2e8f0;
            padding: 8px 6px;
        }
        td {
            border: 1px solid #e2e8f0;
            padding: 6px;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .img-cell {
            text-align: center;
            padding: 4px;
        }
        .img-cell img {
            max-width: 60px;
            max-height: 60px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
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
                    <h1 class="header-title">Katalog Inventaris Alat</h1>
                    <div class="header-subtitle">E-PUSTAKA Management System</div>
                </td>
                <td style="border: none; padding: 0; text-align: right; vertical-align: middle;">
                    <div style="font-weight: bold; font-size: 9pt; color: #0f172a;">Total: {{ $totalAlat }} Alat</div>
                    <div style="font-size: 8pt; color: #64748b;">{{ $totalUnit }} Unit Tersedia</div>
                </td>
            </tr>
        </table>
        
        <table class="meta-info">
            <tr>
                <td style="border: none; padding: 0;">
                    Daftar lengkap aset alat, stok, dan kondisi fisik.
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <strong>Tanggal:</strong> {{ date('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 50px;" class="text-center">Foto</th>
                <th>Nama Alat</th>
                <th style="width: 80px;">Kategori</th>
                <th style="width: 50px;" class="text-center">Stok</th>
                <th style="width: 70px;" class="text-right">Biaya/Hari</th>
                <th style="width: 60px;" class="text-center">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alats as $index => $alat)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="img-cell">
                    @if ($alat->foto)
                        <img src="{{ public_path('storage/' . $alat->foto) }}" alt="Foto">
                    @else
                        <span style="color: #cbd5e1; font-size: 8pt;">N/A</span>
                    @endif
                </td>
                <td class="font-bold">{{ $alat->nama_alat }}</td>
                <td>{{ $alat->kategori }}</td>
                <td class="text-center">{{ $alat->stok_tersedia }}/{{ $alat->stok_total }}</td>
                <td class="text-right">Rp{{ number_format($alat->harga_sewa, 0, ',', '.') }}</td>
                <td class="text-center">{{ ucfirst($alat->kondisi) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left; width: 33%;">E-PUSTAKA Inventory</td>
                <td style="border: none; text-align: center; width: 33%;" class="page-number"></td>
                <td style="border: none; text-align: right; width: 33%;">Laporan Aset Resmi</td>
            </tr>
        </table>
    </div>
</body>
</html>
