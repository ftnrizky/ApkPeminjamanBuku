<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    /**
     * Halaman monitoring denda (admin - READ ONLY)
     * Menampilkan semua data denda yang disinkronkan dari tindakan Petugas.
     */
    public function index(Request $request)
    {
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');

        $query = Peminjaman::with(['user', 'alat'])
            ->where('total_denda', '>', 0);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59']);
        }

        $data = $query->latest()->paginate(15)->withQueryString();

        // Ringkasan monitoring
        $summaryQuery = Peminjaman::where('total_denda', '>', 0);
        if ($tgl_mulai && $tgl_selesai) {
            $summaryQuery->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59']);
        }
        
        $total_nominal = $summaryQuery->sum('total_denda');
        $belum_lunas   = $summaryQuery->where('is_denda_lunas', false)->count();
        $sudah_lunas   = $summaryQuery->where('is_denda_lunas', true)->count();

        return view('admin.denda', compact('data', 'tgl_mulai', 'tgl_selesai', 'total_nominal', 'belum_lunas', 'sudah_lunas'));
    }

    /**
     * Export laporan denda ke PDF
     */
    public function exportPdf(Request $request)
    {
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');

        $query = Peminjaman::with(['user', 'alat'])->where('total_denda', '>', 0);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59']);
        }

        $data = $query->latest()->get();
        $total_denda = $data->sum('total_denda');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.denda_pdf', compact('data', 'tgl_mulai', 'tgl_selesai', 'total_denda'));

        return $pdf->download('laporan-denda-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Fitur Admin: Memberikan notifikasi peringatan kepada peminjam
     */
    public function kirimNotif(Request $request, $id)
    {
        $pinjam = Peminjaman::with('user')->findOrFail($id);
        $nama = $pinjam->user->name ?? 'Peminjam';

        Notifikasi::create([
            'user_id'    => $pinjam->user_id,
            'title'      => '⚠️ Peringatan Denda',
            'message'    => 'Halo ' . $nama . ', Admin mengingatkan Anda untuk segera melunasi denda sebesar Rp ' . number_format($pinjam->total_denda, 0, ',', '.') . ' di meja petugas.',
            'type'       => 'warning',
            'icon'       => 'fas fa-exclamation-triangle',
            'action_url' => route('peminjam.riwayat'),
        ]);

        return response()->json(['success' => true, 'message' => 'Peringatan denda berhasil dikirim ke ' . $nama]);
    }
}
