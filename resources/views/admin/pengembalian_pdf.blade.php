<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengembalian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        html {
            font-size: 9px;
        }

        body {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            color: #1a1a1a;
            background: #fff;
            line-height: 1.2;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding: 7mm;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        @media print {

            html,
            body {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 0;
            }

            .page {
                width: 210mm;
                min-height: 297mm;
                max-height: 297mm;
                padding: 7mm;
                margin: 0;
                overflow: hidden;
            }
        }

        .header {
            margin-bottom: 6px;
            border-bottom: 1.5px solid #0ea5e9;
            padding-bottom: 5px;
            flex-shrink: 0;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .brand {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
        }

        .subtitle {
            font-size: 7px;
            color: #475569;
            margin-top: 1px;
        }

        .meta {
            font-size: 7px;
            color: #475569;
            text-align: right;
        }

        .meta-line {
            margin: 1px 0;
        }

        .summary {
            margin: 4px 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
            flex-shrink: 0;
        }

        .summary-card {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            border-radius: 3px;
            text-align: center;
        }

        .summary-label {
            font-size: 6px;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        .summary-value {
            font-size: 10px;
            font-weight: 800;
            color: #0f172a;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            font-size: 6.6px;
            table-layout: fixed;
        }

        th,
        td {
            padding: 1.8px 2px;
            border: 0.5px solid #cbd5e1;
            overflow-wrap: anywhere;
            word-break: break-word;
            vertical-align: middle;
        }

        th {
            background: #0ea5e9;
            color: #fff;
            text-align: left;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.03em;
            line-height: 1.1;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .denda-positive {
            color: #dc2626;
            font-weight: 700;
        }

        .denda-zero {
            color: #059669;
            font-weight: 700;
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
            font-size: 7px;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <div class="header-row">
                <div>
                    <div class="brand">E-PUSTAKA Management</div>
                    <div class="subtitle">Laporan Pengembalian & Denda</div>
                </div>
                <div class="meta">
                    <div class="meta-line">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB
                    </div>
                    <div class="meta-line">Total Denda:
                        <strong>Rp{{ number_format($totalDenda ?? 0, 0, '', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="summary">
            <div class="summary-card">
                <div class="summary-label">Total Pengembalian</div>
                <div class="summary-value">{{ $totalPengembalian ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Denda</div>
                <div class="summary-value">Rp{{ number_format($totalDenda ?? 0, 0, '', '.') }}</div>
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 7%;">Kode</th>
                        <th style="width: 14%;">Peminjam</th>
                        <th style="width: 15%;">buku</th>
                        <th style="width: 5%;" class="text-center">Qty</th>
                        <th style="width: 9%;" class="text-center">Kembali</th>
                        <th style="width: 15%;" class="text-center">Kondisi</th>
                        <th style="width: 13%;" class="text-center">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $pengembalian)
                        @php
                            $countBaik = 0;
                            $countLecet = 0;
                            $countRusak = 0;
                            $countHilang = 0;
                            if (str_contains($pengembalian->tujuan ?? '', 'Baik:')) {
                                preg_match(
                                    '/Baik:(\d+), Lecet:(\d+), Rusak:(\d+), Hilang:(\d+)/',
                                    $pengembalian->tujuan,
                                    $matches,
                                );
                                if (count($matches) == 5) {
                                    $countBaik = $matches[1];
                                    $countLecet = $matches[2];
                                    $countRusak = $matches[3];
                                    $countHilang = $matches[4];
                                }
                            }

                            $dendaTerlambat = 0;
                            if ($pengembalian->tgl_dikembalikan && $pengembalian->tgl_kembali) {
                                $deadline = \Carbon\Carbon::parse($pengembalian->tgl_kembali)->startOfDay();
                                $kembali = \Carbon\Carbon::parse($pengembalian->tgl_dikembalikan)->startOfDay();
                                if ($kembali->gt($deadline)) {
                                    $selisihHari = $deadline->diffInDays($kembali);
                                    $dendaTerlambat = $selisihHari * 5000;
                                }
                            }

                            $dendaKondisi = $countLecet * 50000 + $countRusak * 200000;
                            if ($countHilang > 0) {
                                $dendaKondisi += $countHilang * 500000;
                            }
                            $totalDendaItem = $dendaTerlambat + $dendaKondisi;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>PJM-{{ str_pad($pengembalian->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ Str::limit($pengembalian->user->name, 12) }}</td>
                            <td>{{ Str::limit($pengembalian->alat->nama_alat, 14) }}</td>
                            <td class="text-center">{{ $pengembalian->jumlah }}</td>
                            <td class="text-center">
                                {{ $pengembalian->tgl_dikembalikan ? \Carbon\Carbon::parse($pengembalian->tgl_dikembalikan)->format('d/m/y') : '-' }}
                            </td>
                            <td class="text-center" style="font-size: 6px;">
                                @if ($countBaik > 0)
                                    B:{{ $countBaik }}
                                @endif
                                @if ($countLecet > 0)
                                    L:{{ $countLecet }}
                                @endif
                                @if ($countRusak > 0)
                                    R:{{ $countRusak }}
                                @endif
                                @if ($countHilang > 0)
                                    H:{{ $countHilang }}
                                @endif
                                @if ($countBaik == 0 && $countLecet == 0 && $countRusak == 0 && $countHilang == 0)
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($totalDendaItem > 0)
                                    <span
                                        class="denda-positive">Rp{{ number_format($totalDendaItem, 0, '', '.') }}</span>
                                @else
                                    <span class="denda-zero">Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">Tidak ada data pengembalian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div>E-PUSTAKA System | Laporan Pengembalian</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>

</html>
