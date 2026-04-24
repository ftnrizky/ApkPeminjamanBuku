<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna - E-PUSTAKA</title>
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
            border-bottom: 2px solid #334155;
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

        .summary-grid {
            margin-top: 30px;
            width: 100%;
            border: none;
        }
        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: center;
        }
        .summary-label {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
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
        <table class="header-top" style="border: none;">
            <tr>
                <td style="border: none; padding: 0;">
                    <h1 class="header-title">Daftar Pengguna Sistem</h1>
                    <div class="header-subtitle">E-PUSTAKA Management System</div>
                </td>
                <td style="border: none; padding: 0; text-align: right; vertical-align: middle;">
                    <div style="font-weight: bold; font-size: 9pt; color: #0f172a;">Total: {{ $totalUsers ?? 0 }} User</div>
                    <div style="font-size: 8pt; color: #64748b;">Dicetak: {{ date('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th style="width: 110px;" class="text-center">No. HP</th>
                <th style="width: 70px;" class="text-center">Role</th>
                <th style="width: 90px;" class="text-center">Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">{{ $user->no_hp ?? '-' }}</td>
                <td class="text-center">{{ strtoupper($user->role) }}</td>
                <td class="text-center">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-grid">
        <tr>
            <td style="border: none; padding-right: 10px;">
                <div class="summary-card">
                    <div class="summary-label">Peminjam</div>
                    <div class="summary-value">{{ $totalPeminjam ?? 0 }}</div>
                </div>
            </td>
            <td style="border: none; padding: 0 5px;">
                <div class="summary-card">
                    <div class="summary-label">Petugas</div>
                    <div class="summary-value">{{ $totalPetugas ?? 0 }}</div>
                </div>
            </td>
            <td style="border: none; padding-left: 10px;">
                <div class="summary-card">
                    <div class="summary-label">Administrator</div>
                    <div class="summary-value">{{ ($totalUsers ?? 0) - ($totalPeminjam ?? 0) - ($totalPetugas ?? 0) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left; width: 33%;">E-PUSTAKA System</td>
                <td style="border: none; text-align: center; width: 33%;" class="page-number"></td>
                <td style="border: none; text-align: right; width: 33%;">Laporan Keanggotaan</td>
            </tr>
        </table>
    </div>
</body>
</html>
