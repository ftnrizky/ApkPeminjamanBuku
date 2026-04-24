<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Log Aktivitas - E-PUSTAKA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15mm;
            background: white;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #06b6d4;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #0f172a;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #06b6d4;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background-color: #f1f5f9;
        }
        tbody tr:hover {
            background-color: #f0f9ff;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .badge-cyan {
            background-color: #cffafe;
            color: #0369a1;
        }
        .badge-teal {
            background-color: #ccfbf1;
            color: #0d9488;
        }
        .badge-emerald {
            background-color: #d1fae5;
            color: #059669;
        }
        .badge-rose {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .summary {
            background: #f0f9ff;
            border-left: 4px solid #06b6d4;
            padding: 10px;
            margin: 20px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LOG AKTIVITAS SISTEM</h1>
            <p>E-PUSTAKA MANAGEMENT SYSTEM - Laporan Lengkap Aktivitas Pengguna</p>
        </div>

        <div class="summary">
            <strong>Total Aktivitas:</strong> {{ $logs->count() }} | 
            <strong>Periode:</strong> {{ $logs->first()?->created_at->translatedFormat('d M Y') ?? 'N/A' }} s/d {{ $logs->last()?->created_at->translatedFormat('d M Y') ?? 'N/A' }} |
            <strong>Dicetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d M Y - H:i') }} WIB
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Waktu</th>
                    <th style="width: 15%;">Pengguna</th>
                    <th style="width: 15%;">Tipe Aktivitas</th>
                    <th style="width: 40%;">Deskripsi</th>
                    <th style="width: 15%;">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td><strong>{{ $log->user_name ?? 'System' }}</strong></td>
                        <td>
                            <span class="badge {{ match($log->activity_type) {
                                'login', 'pinjam' => 'badge-cyan',
                                'kembali', 'setujui_kembali' => 'badge-teal',
                                'setujui_pinjam' => 'badge-emerald',
                                'tolak' => 'badge-rose',
                                default => 'badge-cyan',
                            } }}">
                                {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                            </span>
                        </td>
                        <td>{{ $log->activity_description }}</td>
                        <td><strong>{{ ucfirst($log->user_role ?? 'N/A') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>E-PUSTAKA Management System © 2026 | Laporan ini bersifat rahasia dan hanya untuk internal use</p>
        </div>
    </div>
</body>
</html>
