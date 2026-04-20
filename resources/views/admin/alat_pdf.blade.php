<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Katalog Alat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 8px;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #1a1a1a;
            background: #fff;
            line-height: 1.2;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding: 6mm;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                max-width: 100%;
                height: auto;
                padding: 5mm;
                margin: 0;
            }
        }

        .header {
            margin-bottom: 4px;
            border-bottom: 1px solid #0ea5e9;
            padding-bottom: 3px;
            flex-shrink: 0;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .brand {
            font-size: 10px;
            font-weight: 800;
            color: #0f172a;
        }

        .subtitle {
            font-size: 6px;
            color: #475569;
            margin-top: 1px;
            line-height: 1.1;
        }

        .meta {
            font-size: 6px;
            color: #475569;
            text-align: left;
        }

        .meta strong {
            color: #0f172a;
            font-weight: 700;
        }

        .meta-line {
            margin: 1px 0;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3%;
            font-size: 6px;
            flex: 1;
        }

        thead {
            flex-shrink: 0;
        }

        tbody {
            overflow-y: auto;
        }

        th,
        td {
            padding: 2px 1.5px;
            border: 0.5px solid #cbd5e1;
            text-align: left;
        }

        th {
            background: #0ea5e9;
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            line-height: 1.1;
        }

        td {
            color: #334155;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .img-cell {
            padding: 2px;
            text-align: center;
        }

        .img-cell img {
            max-width: 18mm;
            max-height: 18mm;
            object-fit: cover;
            border-radius: 2px;
        }

        .no-img {
            font-size: 6px;
            color: #cbd5e1;
        }

        .footer {
            margin-top: 4px;
            padding-top: 3px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 6px;
            color: #64748b;
            flex-shrink: 0;
        }

        .no-data {
            text-align: center;
            padding: 15px 0;
            color: #64748b;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <div class="header-row">
                <div>
                    <div class="brand">E-Laptop Management</div>
                    <div class="subtitle">Laporan Katalog Alat - Stok, Harga, Kondisi & Foto</div>
                </div>
                <div class="meta">
                    <div class="meta-line">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB
                    </div>
                    <div class="meta-line">Total: <strong>{{ $totalAlat }} Alat</strong> |
                        <strong>{{ $totalUnit }} Unit</strong></div>
                </div>
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 12%;">Nama Laptop</th>
                        <th style="width: 9%;">Kategori</th>
                        <th style="width: 7%;" class="text-center">Stok</th>
                        <th style="width: 10%;" class="text-center">Sewa/Hari</th>
                        <th style="width: 8%;" class="text-center">Kondisi</th>
                        <th style="width: 12%;" class="text-center">Deskripsi</th>
                        <th style="width: 19%;" class="text-center">Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alats as $index => $alat)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ Str::limit($alat->nama_alat, 15) }}</td>
                            <td>{{ Str::limit($alat->kategori, 10) }}</td>
                            <td class="text-center">{{ $alat->stok_tersedia }}/{{ $alat->stok_total }}</td>
                            <td class="text-center">Rp{{ number_format($alat->harga_sewa, 0, '', '.') }}</td>
                            <td class="text-center">{{ Str::limit(ucfirst($alat->kondisi), 7) }}</td>
                            <td>{{ Str::limit($alat->deskripsi ?? '-', 12) }}</td>
                            <td class="img-cell">
                                @if ($alat->foto)
                                    <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}">
                                @else
                                    <span class="no-img">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">Tidak ada data alat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div>E-Laptop System | Laporan Katalog Alat</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>

</html>
