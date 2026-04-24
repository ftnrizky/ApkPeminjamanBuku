<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\Notifikasi;
use App\Models\Kategori;
use Carbon\Carbon;

class PeminjamController extends Controller
{
    /* ======================
       DASHBOARD
       ====================== */
    public function index()
    {
        $peminjamanAktifList = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->latest()
            ->take(5)
            ->get();

        $dendaBelumLunas = Denda::whereHas('peminjaman', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('is_denda_lunas', false)
            ->sum('total_denda');

        return view('peminjam.dashboard', compact('peminjamanAktifList', 'dendaBelumLunas'));
    }

    /* ======================
       KATALOG
       ====================== */
    public function katalog(Request $request)
    {
        $query = Alat::with('kategori')
            ->whereIn('kondisi', ['baik', 'lecet']);

        if ($request->kategori && $request->kategori != 'Semua') {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama', $request->kategori);
            });
        }

        if ($request->search) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $alats      = $query->get();
        $categories = Kategori::all();

        return view('peminjam.katalog', compact('alats', 'categories'));
    }

    /* ======================
       AJUKAN PEMINJAMAN
       ====================== */
    public function ajukanPeminjaman($id)
    {
        $alat = Alat::findOrFail($id);
        return view('peminjam.ajukan', compact('alat'));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_alat'    => 'required|exists:alats,id',
            'jumlah'     => 'required|integer|min:1',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali'=> 'required|date|after_or_equal:tgl_pinjam',
            'tujuan'     => 'required|string|max:255',
        ]);

        $tglPinjam  = Carbon::parse($request->tgl_pinjam);
        $tglKembali = Carbon::parse($request->tgl_kembali);
        $durasi     = $tglPinjam->diffInDays($tglKembali);

        if ($durasi > 3) {
            return back()->with('error', 'Maksimal peminjaman 3 hari!');
        }

        $alat = Alat::findOrFail($request->id_alat);

        if ($request->jumlah > $alat->stok_tersedia) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $peminjaman = Peminjaman::create([
            'user_id'     => Auth::id(),
            'alat_id'     => $request->id_alat,
            'jumlah'      => $request->jumlah,
            'tgl_pinjam'  => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'tujuan'      => $request->tujuan,
            'status'      => 'pending',
        ]);

        ActivityLogger::ajukanPeminjaman($peminjaman->load('alat'));

        User::whereIn('role', ['admin', 'petugas'])->get()->each(function ($staff) use ($peminjaman) {
            Notifikasi::create([
                'user_id'    => $staff->id,
                'title'      => 'Permintaan Peminjaman',
                'message'    => Auth::user()->name . ' meminjam ' . $peminjaman->alat->nama_alat,
                'type'       => 'info',
                'icon'       => 'fas fa-bell',
                'action_url' => route('petugas.menyetujui_peminjaman'),
            ]);
        });

        return redirect()->route('peminjam.katalog')->with('success', 'Pengajuan berhasil!');
    }

    /* ======================
       PENGEMBALIAN
       ====================== */
    public function kembali()
    {
        $peminjamans = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($pinjam) {
                $deadline = Carbon::parse($pinjam->tgl_kembali)->setTime(17, 0, 0);
                $now      = Carbon::now('Asia/Jakarta');

                $pinjam->terlambat_hari = 0;
                $pinjam->estimasi_denda = 0;

                if ($now->gt($deadline)) {
                    $minutesLate            = $deadline->diffInMinutes($now);
                    $daysLate               = ceil($minutesLate / 1440);
                    $pinjam->terlambat_hari = $daysLate;
                    $pinjam->estimasi_denda = $daysLate * 5000;
                }

                return $pinjam;
            });

        return view('peminjam.kembali', compact('peminjamans'));
    }

    public function prosesKembali($id)
    {
        $pinjam = Peminjaman::with('alat')->findOrFail($id);

        if ($pinjam->user_id != Auth::id()) abort(403);

        if ($pinjam->status != 'disetujui') {
            return back()->with('error', 'Tidak bisa dikembalikan!');
        }

        $pinjam->update([
            'status'            => 'dikembalikan',
            'tgl_dikembalikan'  => Carbon::now('Asia/Jakarta'),
        ]);

        ActivityLogger::ajukanKembali($pinjam);

        User::whereIn('role', ['admin', 'petugas'])->get()->each(function ($staff) use ($pinjam) {
            Notifikasi::create([
                'user_id'    => $staff->id,
                'title'      => 'Pengembalian Diajukan',
                'message'    => Auth::user()->name . ' mengembalikan ' . $pinjam->alat->nama_alat,
                'type'       => 'info',
                'icon'       => 'fas fa-undo',
                'action_url' => route('petugas.menyetujui_kembali'),
            ]);
        });

        return redirect()->route('peminjam.kembali')->with('success', 'Pengembalian diajukan!');
    }

    /* ======================
       RIWAYAT
       ====================== */
    public function riwayat()
    {
        $riwayat = Peminjaman::with(['alat', 'denda'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('peminjam.riwayat', compact('riwayat'));
    }

    /* ======================
       LIST DENDA USER
       ====================== */
    public function dendaSaya()
    {
        $dendas = Denda::with(['peminjaman.alat'])
            ->whereHas('peminjaman', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('peminjam.denda', compact('dendas'));
    }

    /* ======================
       FORM BAYAR DENDA
       ====================== */
    public function formBayarDenda($dendaId)
    {
        $denda = Denda::with(['peminjaman.alat', 'peminjaman.user'])
            ->findOrFail($dendaId);

        if ($denda->peminjaman->user_id != Auth::id()) abort(403);

        if ($denda->is_denda_lunas) {
            return redirect()->route('peminjam.denda')
                ->with('info', 'Denda ini sudah lunas.');
        }

        return view('peminjam.bayar_denda', compact('denda'));
    }

    /* ======================
       PROSES BAYAR DENDA
       (Cash = tandai niat, QRIS = tidak lewat sini)
       ====================== */
    /**
     * POST /peminjam/denda/{id}/bayar
     * Dipakai untuk:
     *   - metode_bayar = 'cash'  → tandai status_pembayaran = 'menunggu_cash'
     *   - metode_bayar = 'qris'  → arahkan ke uploadBuktiDenda (atau handle inline)
     */
    public function prosesBayarDenda(Request $request, $id)
    {
        $request->validate([
            'metode_bayar' => 'required|in:cash,qris,transfer',
        ]);

        $denda = Denda::with(['peminjaman.alat', 'peminjaman.user'])->findOrFail($id);

        // Pastikan denda milik user ini
        if ($denda->peminjaman->user_id != Auth::id()) abort(403);

        if ($denda->is_denda_lunas) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Denda sudah lunas.'], 422);
            }
            return back()->with('info', 'Denda sudah lunas.');
        }

        if ($request->metode_bayar === 'cash') {
            /*
             * Cash: peminjam menyatakan akan bayar langsung.
             * Status diubah ke 'menunggu_cash' agar petugas tahu
             * dan bisa konfirmasi ketika uang sudah diterima.
             */
            $denda->update([
                'metode_bayar'      => 'cash',
                'status_pembayaran' => 'menunggu_cash',
            ]);

            // Notifikasi ke semua staff (admin + petugas)
            User::whereIn('role', ['admin', 'petugas'])->get()->each(function ($staff) use ($denda) {
                Notifikasi::create([
                    'user_id'    => $staff->id,
                    'title'      => '💵 Niat Bayar Cash',
                    'message'    => ($denda->peminjaman->user->name ?? 'Peminjam') .
                                   ' akan membayar denda Rp ' .
                                   number_format($denda->total_denda, 0, ',', '.') .
                                   ' secara cash. Harap konfirmasi.',
                    'type'       => 'info',
                    'icon'       => 'fas fa-money-bill',
                    'action_url' => route('petugas.denda'),
                ]);
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan bayar cash tercatat. Temui petugas untuk konfirmasi.',
                ]);
            }

            return redirect()->route('peminjam.denda')
                ->with('success', 'Permintaan bayar cash tercatat! Silakan temui petugas untuk konfirmasi pembayaran.');
        }

        // Untuk qris/transfer: arahkan ke upload bukti
        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => route('peminjam.denda.form', $id),
                'message'  => 'Silakan upload bukti transfer.',
            ]);
        }

        return redirect()->route('peminjam.denda.form', $id)
            ->with('info', 'Silakan upload bukti transfer.');
    }

    /* ======================
       UPLOAD BUKTI DENDA
       ====================== */
    public function uploadBuktiDenda(Request $request, $dendaId)
    {
        $denda = Denda::with(['peminjaman.user', 'peminjaman.alat'])->findOrFail($dendaId);

        if ($denda->peminjaman->user_id != Auth::id()) abort(403);

        if ($denda->is_denda_lunas) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Denda sudah lunas.'], 422);
            }
            return back()->with('error', 'Denda sudah lunas!');
        }

        $request->validate([
            'bukti_bayar'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'metode_bayar' => 'required|in:qris,transfer',
        ]);

        // Hapus bukti lama jika ada
        if ($denda->bukti_bayar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($denda->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti_denda', 'public');

        $denda->update([
            'bukti_bayar'       => $path,
            'metode_bayar'      => $request->metode_bayar,
            'status_pembayaran' => 'pending',
            'tgl_bayar'         => now(),
        ]);

        // Notifikasi ke semua staff
        User::whereIn('role', ['admin', 'petugas'])->get()->each(function ($staff) use ($denda) {
            Notifikasi::create([
                'user_id'    => $staff->id,
                'title'      => '💳 Bukti Bayar Denda',
                'message'    => ($denda->peminjaman->user->name ?? 'Peminjam') .
                               ' mengupload bukti denda Rp ' .
                               number_format($denda->total_denda, 0, ',', '.') .
                               ' via ' . strtoupper($denda->metode_bayar) . '. Harap verifikasi.',
                'type'       => 'info',
                'icon'       => 'fas fa-file-image',
                'action_url' => route('petugas.denda'),
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Bukti berhasil diupload, menunggu verifikasi.']);
        }

        return redirect()->route('peminjam.denda')
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi petugas.');
    }
}