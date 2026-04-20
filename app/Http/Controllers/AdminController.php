<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $totalMember = User::where('role', 'peminjam')->count(); 
        $totalAlat = Alat::count();
        $sewaAktif = Peminjaman::where('status', 'disetujui')->count();
        $dikembalikan = Peminjaman::where('status', 'selesai')->count();
        $totalPeminjaman = Peminjaman::count();
        $totalUnit = Peminjaman::sum('jumlah');
        $tgl_mulai = $request->query('tgl_mulai', now()->subDays(29)->format('Y-m-d'));
        $tgl_selesai = $request->query('tgl_selesai', now()->format('Y-m-d'));
        
        // Default timeframe
        $timeframe = $request->get('timeframe', 'bulan');
        $labels = [];
        $counts = [];
        $chartTitle = "";

        if ($timeframe === 'hari') {
            // 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('d M');
                $counts[] = Peminjaman::whereDate('created_at', $date)->count();
            }
            $chartTitle = "Aktivitas penyewaan 7 hari terakhir";
        } elseif ($timeframe === 'minggu') {
            // 4 minggu terakhir
            for ($i = 3; $i >= 0; $i--) {
                $startOfWeek = now()->subWeeks($i)->startOfWeek();
                $endOfWeek = now()->subWeeks($i)->endOfWeek();
                $labels[] = $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M');
                $counts[] = Peminjaman::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            }
            $chartTitle = "Aktivitas penyewaan 4 minggu terakhir";
        } elseif ($timeframe === 'tahun') {
            // 12 bulan terakhir
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $labels[] = $month->translatedFormat('M Y');
                $counts[] = Peminjaman::whereYear('created_at', $month->year)
                                     ->whereMonth('created_at', $month->month)
                                     ->count();
            }
            $chartTitle = "Aktivitas penyewaan 12 bulan terakhir";
        } else {
            // Default: 30 hari terakhir (bulan)
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('d M');
                $counts[] = Peminjaman::whereDate('created_at', $date)->count();
            }
            $chartTitle = "Aktivitas penyewaan 30 hari terakhir";
        }

        return view('admin.dashboard', compact(
            'totalMember', 'totalAlat', 'sewaAktif', 'dikembalikan', 'totalPeminjaman', 'totalUnit',
            'labels', 'counts', 'chartTitle', 'timeframe', 'tgl_mulai', 'tgl_selesai'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');

        $query = Peminjaman::with(['user', 'alat']);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereDate('created_at', '>=', $tgl_mulai)
                  ->whereDate('created_at', '<=', $tgl_selesai);
        } elseif ($tgl_mulai) {
            $query->whereDate('created_at', '>=', $tgl_mulai);
        } elseif ($tgl_selesai) {
            $query->whereDate('created_at', '<=', $tgl_selesai);
        }

        $laporans = $query->latest()->get();

        $pdf = Pdf::loadView('petugas.laporan_pdf', compact('laporans', 'tgl_mulai', 'tgl_selesai'));

        return $pdf->download('laporan-peminjaman-' . ($tgl_mulai ?? now()->format('Y-m-d')) . '.pdf');
    }
}