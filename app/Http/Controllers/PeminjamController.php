<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::query();

        if ($request->has('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $alats = $query->get();
        $categories = Alat::distinct()->pluck('kategori');
        return view('peminjam.dashboard', compact('alats', 'categories'));
    }

    /* ======================
       PROSES PEMINJAMAN
       ====================== */
    public function ajukanPeminjaman($id)
    {
        $alat = Alat::findOrFail($id);
        return view('peminjam.ajukan', compact('alat'));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_alat'     => 'required|exists:alats,id',
            'jumlah'      => 'required|integer|min:1',
            'tgl_pinjam'  => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'tujuan'      => 'required|string|max:255',
        ], [
            'tgl_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam!',
        ]);

        $alat = Alat::find($request->id_alat);
        if ($request->jumlah > $alat->stok_tersedia) {
            return back()->with('error', 'Maaf, stok tidak mencukupi.');
        }

        Peminjaman::create([
            'user_id'     => Auth::id(),
            'alat_id'     => $request->id_alat,
            'jumlah'      => $request->jumlah,
            'tgl_pinjam'  => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'tujuan'      => $request->tujuan,
            'status'      => 'pending', // Default status
        ]);

        return redirect()->route('peminjam.dashboard')->with('success', 'Permintaan pinjam berhasil dikirim. Menunggu verifikasi petugas!');
    }

    /* ======================
       PROSES PENGEMBALIAN
       ====================== */
    public function kembali()
    {
        $peminjamans = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui') 
            ->get();

        return view('peminjam.kembali', compact('peminjamans'));
    }

    public function prosesKembali($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        $hariIni = now();
        $deadline = $pinjam->tgl_kembali; // Pastikan kolom ini ada di database
        $totalDenda = 0;

        // Hitung denda jika telat
        if ($hariIni->gt($deadline)) {
            $selisihHari = $hariIni->diffInDays($deadline);
            $totalDenda = $selisihHari * 5000;
        }

        $pinjam->update([
            'status' => 'dikembalikan', // STATUS PERANTARA
            'tgl_dikembalikan' => $hariIni,
            'total_denda' => $totalDenda
        ]);

        return redirect()->back()->with('success', 'Permintaan pengembalian dikirim! Silakan serahkan alat ke petugas.');
    }
}
