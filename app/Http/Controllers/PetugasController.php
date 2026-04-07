<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function index() 
    {
        $waitingApproval = Peminjaman::whereIn('status', ['pending', 'menunggu_kembali'])->count();

        $alatDipinjam = Peminjaman::where('status', 'dipinjam')->count();

        $selesaiHariIni = Peminjaman::where('status', 'kembali')
                                    ->whereDate('updated_at', Carbon::today())
                                    ->count();

        $antreanTugas = Peminjaman::with(['user', 'alat'])
                                    ->whereIn('status', ['pending', 'menunggu_kembali'])
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
            ->where('status', 'dikembalikan') // Mencari yang diajukan peminjam
            ->latest()
            ->get();

        return view('petugas.menyetujui_kembali', compact('pengembalians'));
    }

    public function prosesKonfirmasiKembali(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update([
            'status' => 'selesai', // SEKARANG BARU SELESAI
            'kondisi' => $request->kondisi, // Petugas input kondisi alat (baik/rusak)
        ]);

        // Stok alat bertambah kembali
        $pinjam->alat->increment('stok_tersedia', $pinjam->jumlah);

        return redirect()->back()->with('success', 'Verifikasi berhasil! Data otomatis masuk ke riwayat Admin.');
    }

    public function cetakLaporan() 
    {
        return view ('petugas.laporan');
    }
}
