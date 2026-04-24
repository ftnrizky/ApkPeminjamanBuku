<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DendaController extends Controller
{
    /**
     * Halaman manajemen denda (admin)
     * GET /admin/denda
     */
    public function index(Request $request)
    {
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');

        // Mengambil data dari Peminjaman agar SINKRON dengan dashboard
        $query = Peminjaman::with(['user', 'alat'])
            ->where('total_denda', '>', 0);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59']);
        }

        // Gunakan paginate agar bisa menampilkan SEMUA data tanpa lemot
        $data = $query->latest()->paginate(15)->withQueryString();

        // Hitung ringkasan denda (tanpa paginate untuk summary card)
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
     * Export denda ke PDF
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
     * Admin tandai lunas via cash
     * POST /admin/bayar-denda/{id}
     */
    public function bayarDenda(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->is_denda_lunas) {
            return response()->json(['success' => false, 'message' => 'Denda sudah lunas.'], 422);
        }

        $pinjam->update([
            'metode_bayar'      => 'cash',
            'status_pembayaran' => 'diterima',
            'is_denda_lunas'    => true,
            'tanggal_bayar'     => now(),
        ]);

        Notifikasi::create([
            'user_id'    => $pinjam->user_id,
            'title'      => '✅ Denda Lunas',
            'message'    => 'Pembayaran denda cash Rp ' . number_format($pinjam->total_denda, 0, ',', '.') . ' telah dikonfirmasi oleh admin.',
            'type'       => 'success',
            'icon'       => 'fas fa-check-circle',
            'action_url' => route('peminjam.riwayat'),
        ]);

        return response()->json(['success' => true, 'message' => 'Denda lunas (cash).']);
    }

    /**
     * Verifikasi pembayaran QRIS (terima / tolak)
     * POST /admin/verifikasi-denda/{id}
     */
    public function verifikasiDenda(Request $request, $id)
    {
        $request->validate([
            'aksi'              => 'required|in:terima,tolak',
            'catatan_penolakan' => 'nullable|string|max:255',
        ]);

        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->is_denda_lunas) {
            return response()->json(['success' => false, 'message' => 'Denda sudah lunas.'], 422);
        }

        if ($request->aksi === 'terima') {
            $pinjam->update([
                'status_pembayaran' => 'diterima',
                'is_denda_lunas'    => true,
                'tanggal_bayar'     => now(),
            ]);

            Notifikasi::create([
                'user_id'    => $pinjam->user_id,
                'title'      => '✅ Pembayaran Diterima',
                'message'    => 'Pembayaran denda Rp ' . number_format($pinjam->total_denda, 0, ',', '.') . ' telah diverifikasi dan diterima.',
                'type'       => 'success',
                'icon'       => 'fas fa-check-double',
                'action_url' => route('peminjam.riwayat'),
            ]);
        } else {
            $pinjam->update([
                'status_pembayaran' => 'ditolak'
            ]);

            Notifikasi::create([
                'user_id'    => $pinjam->user_id,
                'title'      => '❌ Pembayaran Ditolak',
                'message'    => 'Bukti pembayaran denda ditolak. Alasan: ' . ($request->catatan_penolakan ?? 'Tidak disebutkan'),
                'type'       => 'error',
                'icon'       => 'fas fa-times-circle',
                'action_url' => route('peminjam.riwayat'),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Kirim notifikasi reminder denda
     * POST /admin/denda/{id}/kirim-notif
     */
    public function kirimNotif(Request $request, $id)
    {
        $pinjam = Peminjaman::with('user')->findOrFail($id);
        $nama = $pinjam->user->name ?? 'User';

        Notifikasi::create([
            'user_id'    => $pinjam->user_id,
            'title'      => '🔔 Reminder Denda',
            'message'    => 'Halo ' . $nama . ', jangan lupa untuk melunasi denda Anda sebesar Rp ' . number_format($pinjam->total_denda, 0, ',', '.'),
            'type'       => 'warning',
            'icon'       => 'fas fa-bullhorn',
            'action_url' => route('peminjam.riwayat'),
        ]);

        return response()->json(['success' => true, 'message' => 'Notifikasi terkirim ke ' . $nama]);
    }
}
