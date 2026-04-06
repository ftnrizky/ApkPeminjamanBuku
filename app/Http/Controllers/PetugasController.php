<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;

class PetugasController extends Controller
{
    public function index() 
    {
        return view ('petugas.dashboard');
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
        $peminjaman = Peminjaman::findOrFail($id);
        $alat = $peminjaman->alat;

        if ($request->status == 'disetujui') {
            // PERBAIKAN: Gunakan kolom yang konsisten (stok_tersedia)
            if ($alat->stok_tersedia < $peminjaman->jumlah) {
                return back()->with('error', 'Stok alat tidak mencukupi!');
            }
            
            $alat->decrement('stok_tersedia', $peminjaman->jumlah);
            $peminjaman->update(['status' => 'disetujui']);
            
        } elseif ($request->status == 'ditolak') {
            $peminjaman->update(['status' => 'ditolak']);
            
        } elseif ($request->has('kondisi')) {
            $peminjaman->update([
                'status' => 'dikembalikan',
                'catatan' => $request->kondisi
            ]);
            
            $alat->increment('stok_tersedia', $peminjaman->jumlah);
            return redirect()->back()->with('success', 'Alat berhasil dikembalikan!');
        }

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    /* ==============================
       PROSES MENYETUJUI PENGEMBALIAN
       ============================== */
    public function menyetujuiPengembalian() 
    {
        $pengembalians = Peminjaman::with(['user', 'alat'])
            ->where('status', 'disetujui') 
            ->latest()
            ->get();

        return view('petugas.menyetujui_kembali', compact('pengembalians'));
    }

    public function prosesKonfirmasiKembali(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update([
            'status' => 'selesai', 
            'kondisi' => $request->kondisi,
            'tgl_dikembalikan' => now(), 
        ]);

        $pinjam->alat->increment('stok_tersedia', $pinjam->jumlah);

        return redirect()->back()->with('success', 'Verifikasi berhasil! Data telah masuk ke riwayat Admin.');
    }

    public function cetakLaporan() 
    {
        return view ('petugas.laporan');
    }
}
