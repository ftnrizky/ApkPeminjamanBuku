<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PetugasController extends Controller
{
    public function index()
    {
        $waitingApproval = Peminjaman::where('status', 'pending')->count();
        $alatDipinjam    = Peminjaman::where('status', 'disetujui')->sum('jumlah');
        $selesaiHariIni  = Peminjaman::where('status', 'selesai')
                                     ->whereDate('tgl_dikembalikan', Carbon::today())
                                     ->count();

        $antreanTugas = Peminjaman::with(['user', 'alat'])
                                   ->whereIn('status', ['pending', 'dikembalikan'])
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)
                                   ->get();

        return view('petugas.dashboard', compact(
            'waitingApproval',
            'alatDipinjam',
            'selesaiHariIni',
            'antreanTugas'
        ));
    }

    /* ============================
       PROSES MENYETUJUI PEMINJAMAN
       ============================ */
    public function menyetujuiPeminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
                                  ->where('status', 'pending')
                                  ->latest()
                                  ->get();

        return view('petugas.menyetujui_pinjam', compact('peminjamans'));
    }

    public function prosesPersetujuanPinjam(Request $request, $id)
    {
        $request->validate([
            'status'            => 'required|in:disetujui,ditolak',
            'jumlah_disetujui'  => 'nullable|integer|min:1',
        ]);

        $peminjaman = Peminjaman::with(['user', 'alat'])->findOrFail($id);
        $alat       = $peminjaman->alat;

        if ($request->status == 'disetujui') {
            $jumlahDisetujui = $request->jumlah_disetujui ?? $peminjaman->jumlah;

            if ($jumlahDisetujui > $peminjaman->jumlah) {
                return back()->with('error', 'Jumlah yang disetujui tidak boleh melebihi jumlah yang diajukan!');
            }

            if ($alat->stok_tersedia < $jumlahDisetujui) {
                return back()->with('error', 'Stok alat tidak mencukupi!');
            }

            $alat->decrement('stok_tersedia', $jumlahDisetujui);
            $peminjaman->update([
                'status' => 'disetujui',
                'jumlah' => $jumlahDisetujui,
            ]);

            ActivityLogger::setujuiPeminjaman($peminjaman, $jumlahDisetujui);

            Notifikasi::create([
                'user_id'    => $peminjaman->user_id,
                'title'      => 'Peminjaman Disetujui',
                'message'    => 'Peminjaman ' . $alat->nama_alat . ' telah disetujui. Jumlah: ' . $jumlahDisetujui . ' unit.',
                'type'       => 'success',
                'icon'       => 'fas fa-check-circle',
                'action_url' => route('peminjam.kembali'),
            ]);

            return back()->with('success', 'Peminjaman berhasil disetujui dengan jumlah ' . $jumlahDisetujui . ' unit!');

        } elseif ($request->status == 'ditolak') {
            $peminjaman->update(['status' => 'ditolak']);

            ActivityLogger::tolakPeminjaman($peminjaman);

            Notifikasi::create([
                'user_id'    => $peminjaman->user_id,
                'title'      => 'Peminjaman Ditolak',
                'message'    => 'Permintaan peminjaman ' . $alat->nama_alat . ' telah ditolak.',
                'type'       => 'danger',
                'icon'       => 'fas fa-times-circle',
                'action_url' => route('peminjam.riwayat'),
            ]);

            return back()->with('success', 'Peminjaman berhasil ditolak!');
        }

        return back()->with('error', 'Status tidak valid!');
    }

    /* ==============================
       PROSES MENYETUJUI PENGEMBALIAN
       ============================== */
    public function menyetujuiPengembalian()
    {
        $pengembalians = Peminjaman::with(['user', 'alat'])
                                    ->where('status', 'dikembalikan')
                                    ->latest()
                                    ->get();

        return view('petugas.menyetujui_kembali', compact('pengembalians'));
    }

    public function prosesKonfirmasiKembali(Request $request, $id)
    {
        $request->validate([
            'kondisi_unit'    => 'required|array',
            'kondisi_unit.*'  => 'required|in:baik,lecet,rusak,hilang,lainnya',
            'custom_biaya'    => 'nullable|array',
            'custom_biaya.*'  => 'nullable|numeric|min:0',
        ]);

        $pinjam = Peminjaman::with(['user', 'alat'])->findOrFail($id);

        if ($pinjam->status != 'dikembalikan') {
            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk dikonfirmasi!');
        }

        $kondisiUnits  = $request->kondisi_unit;
        $customBiaya   = $request->custom_biaya ?? [];
        $waktuSekarang = Carbon::now('Asia/Jakarta');
        $total_denda   = 0;
        $deadline      = Carbon::parse($pinjam->tgl_kembali)->setTime(17, 0, 0);

        /* ── Denda keterlambatan ───────────────────────────────────── */
        $dendaTerlambat = 0;
        if ($waktuSekarang->gt($deadline)) {
            $minutesLate    = $deadline->diffInMinutes($waktuSekarang, false);
            $daysLate       = (int) ceil($minutesLate / 1440);
            $dendaTerlambat = max(1, $daysLate) * 5000;
            $total_denda   += $dendaTerlambat;
        }

        /* ── Denda kondisi per unit ────────────────────────────────── */
        $dendaKondisi = 0;
        $countBaik    = 0;
        $countLecet   = 0;
        $countRusak   = 0;
        $countHilang  = 0;
        $countCustom  = 0;

        foreach ($kondisiUnits as $index => $kondisi) {
            switch ($kondisi) {
                case 'hilang':
                    $dendaKondisi += 500000;
                    $countHilang++;
                    break;
                case 'rusak':
                    $dendaKondisi += 200000;
                    $countRusak++;
                    break;
                case 'lecet':
                    $dendaKondisi += 50000;
                    $countLecet++;
                    break;
                case 'lainnya':
                    $customValue   = isset($customBiaya[$index]) ? (int) $customBiaya[$index] : 0;
                    $dendaKondisi += max(0, $customValue);
                    $countCustom++;
                    break;
                case 'baik':
                    $countBaik++;
                    break;
            }
        }

        $total_denda += $dendaKondisi;
        $total_denda  = max(0, (int) $total_denda);

        /* ── Update stok ──────────────────────────────────────────── */
        $alat             = $pinjam->alat;
        $stokDikembalikan = $countBaik + $countLecet + $countRusak + $countCustom;
        if ($stokDikembalikan > 0) {
            $alat->increment('stok_tersedia', $stokDikembalikan);
        }

        $ringkasanKondisi = "Baik:{$countBaik}, Lecet:{$countLecet}, Rusak:{$countRusak}, Hilang:{$countHilang}, Lainnya:{$countCustom}";

        /* ── Update peminjaman ────────────────────────────────────── */
        $pinjam->update([
            'status'           => 'selesai',
            'kondisi'          => $kondisiUnits,
            'total_denda'      => $total_denda,
            'tgl_dikembalikan' => $waktuSekarang,
            'tujuan'           => $ringkasanKondisi,
        ]);

        /* ── Buat record denda jika ada nominal ───────────────────── */
        if ($total_denda > 0) {
            $dendaExisting = Denda::where('peminjaman_id', $pinjam->id)->first();

            if (!$dendaExisting) {
                $denda = Denda::create([
                    'peminjaman_id'     => $pinjam->id,
                    'total_denda'       => $total_denda,
                    'is_denda_lunas'    => false,
                    'status_pembayaran' => Denda::STATUS_BELUM_BAYAR,
                    'catatan'           => "Terlambat: Rp" . number_format($dendaTerlambat, 0, ',', '.') .
                                          " | Kondisi: Rp" . number_format($dendaKondisi, 0, ',', '.') .
                                          " | Ringkasan: " . $ringkasanKondisi,
                ]);

                Notifikasi::create([
                    'user_id'    => $pinjam->user_id,
                    'title'      => '⚠️ Denda Pengembalian',
                    'message'    => 'Kamu memiliki denda sebesar Rp ' . number_format($total_denda, 0, ',', '.') . '. Segera lakukan pembayaran.',
                    'type'       => 'danger',
                    'icon'       => 'fas fa-exclamation-triangle',
                    'action_url' => route('peminjam.denda'),
                ]);
            }
        }

        ActivityLogger::konfirmasiKembali($pinjam, $total_denda, $ringkasanKondisi);

        Notifikasi::create([
            'user_id'    => $pinjam->user_id,
            'title'      => 'Pengembalian Dikonfirmasi',
            'message'    => 'Pengembalian ' . $pinjam->alat->nama_alat . ' telah dikonfirmasi. Total denda: Rp ' . number_format($total_denda, 0, ',', '.'),
            'type'       => 'success',
            'icon'       => 'fas fa-check-circle',
            'action_url' => route('peminjam.riwayat'),
        ]);

        $message = "Pengembalian berhasil! {$ringkasanKondisi} | " .
                   "Denda Terlambat: Rp " . number_format($dendaTerlambat, 0, ',', '.') .
                   " | Denda Kondisi: Rp " . number_format($dendaKondisi, 0, ',', '.') .
                   " | Total: Rp " . number_format($total_denda, 0, ',', '.');

        return redirect()->route('petugas.menyetujui_kembali')->with('success', $message);
    }

    /* ============================
       DENDA — Khusus Petugas
       ============================ */

    /**
     * Halaman kelola denda petugas
     * GET /petugas/denda
     */
    public function denda()
    {
        $dendas = Denda::with(['peminjaman.user', 'peminjaman.alat'])
            ->where('is_denda_lunas', false)
            ->latest()
            ->get();

        return view('petugas.denda', compact('dendas'));
    }

    /**
     * Petugas catat pembayaran cash
     * POST /petugas/denda/{id}/cash
     */
    public function catatBayarCash(Request $request, $id)
    {
        $denda = Denda::with('peminjaman')->findOrFail($id);

        if ($denda->is_denda_lunas) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Denda sudah lunas.'], 422);
            }
            return back()->with('error', 'Denda sudah lunas!');
        }

        $denda->update([
            'metode_bayar'      => 'cash',
            'status_pembayaran' => 'diterima',
            'is_denda_lunas'    => true,
            'tgl_bayar'         => now(),
            'catatan'           => ($denda->catatan ?? '') . ' | Dicatat lunas cash oleh petugas.',
        ]);

        Notifikasi::create([
            'user_id'    => $denda->peminjaman->user_id,
            'title'      => '✅ Denda Lunas',
            'message'    => 'Pembayaran denda cash Rp ' . number_format($denda->total_denda, 0, ',', '.') . ' telah dikonfirmasi oleh petugas.',
            'type'       => 'success',
            'icon'       => 'fas fa-check-circle',
            'action_url' => route('peminjam.riwayat'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Denda berhasil dicatat lunas (cash)!');
    }

    /**
     * Petugas verifikasi bukti transfer/QRIS
     * POST /petugas/denda/{id}/verifikasi
     */
    public function verifikasiBukti(Request $request, $id)
    {
        $denda = Denda::with('peminjaman')->findOrFail($id);
        $aksi  = $request->aksi; // 'terima' atau 'tolak'

        if ($aksi === 'terima') {
            $denda->update([
                'status_pembayaran' => 'diterima',
                'is_denda_lunas'    => true,
                'tgl_bayar'         => now(),
            ]);

            Notifikasi::create([
                'user_id'    => $denda->peminjaman->user_id,
                'title'      => '✅ Pembayaran Diterima',
                'message'    => 'Bukti pembayaran denda Rp ' . number_format($denda->total_denda, 0, ',', '.') . ' telah diterima.',
                'type'       => 'success',
                'icon'       => 'fas fa-check-circle',
                'action_url' => route('peminjam.riwayat'),
            ]);

            $message = 'Pembayaran diterima, denda lunas!';
        } else {
            $catatan = $request->catatan_penolakan ?? 'Bukti tidak valid.';
            $denda->update([
                'status_pembayaran' => 'ditolak',
                'catatan_penolakan' => $catatan,
                'bukti_bayar'       => null,
            ]);

            Notifikasi::create([
                'user_id'    => $denda->peminjaman->user_id,
                'title'      => '❌ Bukti Pembayaran Ditolak',
                'message'    => 'Bukti pembayaran denda kamu ditolak. Alasan: ' . $catatan . '. Silakan upload ulang.',
                'type'       => 'danger',
                'icon'       => 'fas fa-times-circle',
                'action_url' => route('peminjam.denda'),
            ]);

            $message = 'Bukti pembayaran ditolak!';
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return back()->with('success', $message);
    }

    /**
     * Petugas kirim notifikasi pengingat denda ke peminjam
     * POST /petugas/denda/{id}/notif
     *
     * ✅ Method ini adalah perbaikan dari error:
     * "Method App\Http\Controllers\PetugasController::kirimNotifDenda does not exist"
     */
    public function kirimNotifDenda(Request $request, $id)
    {
        $denda  = Denda::with('peminjaman.user')->findOrFail($id);
        $userId = $denda->peminjaman->user_id ?? null;
        $nama   = $denda->peminjaman->user->name ?? 'Peminjam';

        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
            }
            return back()->with('error', 'User peminjam tidak ditemukan.');
        }

        Notifikasi::create([
            'user_id'    => $userId,
            'title'      => '⚠️ Reminder Denda',
            'message'    => 'Kamu masih memiliki denda belum lunas sebesar Rp ' .
                            number_format($denda->total_denda, 0, ',', '.') . '. Segera selesaikan.',
            'type'       => 'warning',
            'icon'       => 'fas fa-bell',
            'action_url' => route('peminjam.denda'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Notifikasi terkirim ke ' . $nama]);
        }
        return back()->with('success', 'Notifikasi berhasil dikirim ke ' . $nama);
    }

    /* ============================
       KIRIM PENGINGAT PEMINJAMAN
       ============================ */
    public function kirimPengingat($id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if ($peminjaman->status != 'disetujui') {
            return response()->json(['success' => false, 'message' => 'Peminjaman belum disetujui atau sudah dikembalikan!']);
        }

        Notifikasi::create([
            'user_id'    => $peminjaman->user_id,
            'title'      => 'Pengingat Pengembalian',
            'message'    => $peminjaman->alat->nama_alat . ' harus segera dikembalikan. Batas waktu: ' . Carbon::parse($peminjaman->tgl_kembali)->format('d/m/Y'),
            'type'       => 'warning',
            'icon'       => 'fas fa-bell',
            'action_url' => route('peminjam.kembali'),
        ]);

        return response()->json(['success' => true, 'message' => 'Pengingat berhasil dikirim!']);
    }

    /* ============================
       LAPORAN
       ============================ */
    public function cetakLaporan()
    {
        $laporans = Peminjaman::with(['user', 'alat'])->latest()->get();
        return view('petugas.laporan', compact('laporans'));
    }

    public function exportPdf(Request $request)
    {
        $tgl_mulai   = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;

        $query = Peminjaman::with(['user', 'alat']);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereDate('created_at', '>=', $tgl_mulai)
                  ->whereDate('created_at', '<=', $tgl_selesai);
        }

        $laporans = $query->latest()->get();
        $totalDenda = $laporans->sum('total_denda');
        $pdf      = Pdf::loadView('petugas.laporan_pdf', compact('laporans', 'tgl_mulai', 'tgl_selesai', 'totalDenda'));

        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }
}
