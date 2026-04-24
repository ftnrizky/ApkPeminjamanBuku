<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Member E-PUSTAKA</title>
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
        .content { flex: 1; display: flex; flex-direction: column; min-height: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 7px; }
        th, td { padding: 2px 2px; border: 0.5px solid #cbd5e1; font-size: 7px; }
        th { text-align: left; background: #0ea5e9; color: #fff; text-transform: uppercase; font-weight: 700; letter-spacing: 0.03em; line-height: 1.1; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary { margin-top: 4px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; flex-shrink: 0; }
        .metric { background: #f8fafc; border: 1px solid #cbd5e1; padding: 4px 6px; border-radius: 3px; text-align: center; }
        .metric-label { font-size: 6px; color: #475569; text-transform: uppercase; margin-bottom: 1px; }
        .metric-value { font-size: 11px; font-weight: 800; color: #0f172a; }
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
                    <div class="subtitle">Laporan Anggota & Member</div>
                </div>
                <div class="meta">
                    <div class="meta-line">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB</div>
                    <div class="meta-line">Total: <strong>{{ $totalUsers ?? 0 }} Member</strong></div>
                </div>
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 18%;">Nama</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 13%;" class="text-center">No. HP</th>
                        <th style="width: 10%;" class="text-center">Role</th>
                        <th style="width: 12%;" class="text-center">Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ Str::limit($user->name, 16) }}</td>
                        <td>{{ Str::limit($user->email, 20) }}</td>
                        <td class="text-center">{{ Str::limit($user->no_hp ?? '-', 11) }}</td>
                        <td class="text-center">{{ ucfirst($user->role) }}</td>
                        <td class="text-center">{{ $user->created_at ? $user->created_at->translatedFormat('d/m/y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="no-data">Tidak ada data member</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="summary">
            <div class="metric">
                <div class="metric-label">Total Member</div>
                <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
            </div>
            <div class="metric">
                <div class="metric-label">Peminjam</div>
                <div class="metric-value">{{ $totalPeminjam ?? 0 }}</div>
            </div>
            <div class="metric">
                <div class="metric-label">Petugas</div>
                <div class="metric-value">{{ $totalPetugas ?? 0 }}</div>
            </div>
            <div class="metric">
                <div class="metric-label">Admin</div>
                <div class="metric-value">{{ $totalAdmin ?? 0 }}</div>
            </div>
        </div>

        <div class="footer">
            <div>E-PUSTAKA System | Laporan Member</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>
            </div>
            <div class="metric">
                <div class="metric-label">Admin</div>
                <div class="metric-value">{{ ($totalUsers ?? 0) - ($totalPeminjam ?? 0) - ($totalPetugas ?? 0) }}</div>
            </div>
        </div>

        <div class="footer">
            <div>E-PUSTAKA System | Laporan Member</div>
            <div>Hal. 1/1</div>
        </div>
    </div>
</body>
</html>
            <div>
                <strong>E-PUSTAKA Management System</strong><br>
                Laporan data member profesional.
            </div>
            <div>
                Halaman 1 / 1
            </div>
        </div>
    </div>
</body>
</html>
