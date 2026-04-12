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
    public function index()
    {
        $userId = Auth::id();
        $peminjamanAktifList = Peminjaman::with('alat')
            ->where('user_id', $userId)
            ->where('status', 'disetujui')
            ->latest()
            ->take(5)
            ->get();
        
        return view('peminjam.dashboard', compact('peminjamanAktifList'));
    }

    public function katalog(Request $request)
    {
        $query = Alat::whereIn('kondisi', ['baik', 'lecet']);

        if ($request->has('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $alats = $query->get();
        $categories = Alat::distinct()->pluck('kategori');
        return view('peminjam.katalog', compact('alats', 'categories'));
    }

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
            'tgl_pinjam'  => 'required|date|after_or_equal:today',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'tujuan'      => 'required|string|max:255',
        ]);

        $tglPinjam = Carbon::parse($request->tgl_pinjam);
        $tglKembali = Carbon::parse($request->tgl_kembali);
        $durasi = $tglPinjam->diffInDays($tglKembali);

        if ($durasi > 3) {
            return back()->with('error', 'Gagal! Batas maksimal peminjaman adalah 3 hari.');
        }

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
            'status'      => 'pending',
        ]);

        return redirect()->route('peminjam.katalog')->with('success', 'Permintaan pinjam berhasil dikirim!');
    }

    public function kembali()
    {
        $peminjamans = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($pinjam) {
                $deadline = Carbon::parse($pinjam->tgl_kembali)->startOfDay();
                $hariIni = Carbon::now('Asia/Jakarta')->startOfDay();
                $pinjam->estimasi_denda = 0;
                $pinjam->terlambat_hari = 0;

                if ($hariIni->gt($deadline)) {
                    $pinjam->terlambat_hari = $deadline->diffInDays($hariIni);
                    $pinjam->estimasi_denda = $pinjam->terlambat_hari * 5000;
                }
                return $pinjam;
            });

        return view('peminjam.kembali', compact('peminjamans'));
    }

    public function prosesKembali($id)
    {
        $pinjam = Peminjaman::with('alat')->findOrFail($id);
        
        if ($pinjam->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke peminjaman ini!');
        }

        if ($pinjam->status != 'disetujui') {
            return redirect()->back()->with('error', 'Peminjaman ini tidak dapat diproses! Status saat ini: ' . $pinjam->status);
        }

        $pinjam->update([
            'status' => 'dikembalikan',
            'tgl_dikembalikan' => Carbon::now('Asia/Jakarta'),
        ]);
        
        return redirect()->route('peminjam.kembali')->with('success', 'Pengembalian berhasil diajukan! Silahkan serahkan alat ke petugas untuk dicek kondisinya.');
    }
    
    public function riwayat()
    {
        $riwayat = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->orderByRaw("FIELD(status, 'pending', 'disetujui', 'dikembalikan', 'selesai', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('peminjam.riwayat', compact('riwayat'));
    }
}