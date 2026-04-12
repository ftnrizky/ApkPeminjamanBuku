<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PetugasController extends Controller
{
    public function index() 
    {
        $waitingApproval = Peminjaman::where('status', 'pending')->count();
        $alatDipinjam = Peminjaman::where('status', 'disetujui')->sum('jumlah');
        $selesaiHariIni = Peminjaman::where('status', 'selesai')
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
        $peminjaman = Peminjaman::findOrFail($id);
        $alat = $peminjaman->alat;

        if ($request->status == 'disetujui') {
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
            ->where('status', 'dikembalikan')
            ->latest()
            ->get();

        return view('petugas.menyetujui_kembali', compact('pengembalians'));
    }

    public function prosesKonfirmasiKembali(Request $request, $id)
    {
        $request->validate([
            'kondisi_unit' => 'required|array',
            'kondisi_unit.*' => 'required|in:baik,lecet,rusak,hilang',
        ]);

        $pinjam = Peminjaman::with('alat')->findOrFail($id);
        
        if ($pinjam->status != 'dikembalikan') {
            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk dikonfirmasi!');
        }
        
        $kondisiUnits = $request->kondisi_unit;
        $waktuSekarang = Carbon::now('Asia/Jakarta');
        $total_denda = 0;
        $deadline = Carbon::parse($pinjam->tgl_kembali)->startOfDay();
        $tanggalKembali = $waktuSekarang->copy()->startOfDay();
        $dendaTerlambat = 0;
        
        if ($tanggalKembali->gt($deadline)) {
            $selisihHari = $deadline->diffInDays($tanggalKembali);
            $dendaTerlambat = $selisihHari * 5000;
            $total_denda += $dendaTerlambat;
        }

        $hargaAlat = $pinjam->alat->harga_asli ?? $pinjam->alat->harga_sewa ?? 0;
        $dendaKondisi = 0;
        
        $countBaik = 0;
        $countLecet = 0;
        $countRusak = 0;
        $countHilang = 0;
        
        foreach ($kondisiUnits as $kondisi) {
            switch ($kondisi) {
                case 'hilang':
                    $dendaKondisi += $hargaAlat;
                    $countHilang++;
                    break;
                case 'rusak':
                    $dendaKondisi += 50000;
                    $countRusak++;
                    break;
                case 'lecet':
                    $dendaKondisi += 15000;
                    $countLecet++;
                    break;
                case 'baik':
                    $countBaik++;
                    break;
            }
        }
        
        $total_denda += $dendaKondisi;
        $total_denda = max(0, $total_denda);
        $alat = $pinjam->alat;
        $stokDikembalikan = $countBaik + $countLecet;
        if ($stokDikembalikan > 0) {
            $alat->increment('stok_tersedia', $stokDikembalikan);
        }
        $ringkasanKondisi = "Baik:{$countBaik}, Lecet:{$countLecet}, Rusak:{$countRusak}, Hilang:{$countHilang}";
        
        $pinjam->update([
            'status'           => 'selesai',
            'kondisi'          => $kondisiUnits[0] ?? 'baik',
            'total_denda'      => $total_denda,
            'tgl_dikembalikan' => $waktuSekarang,
            'tujuan'           => $ringkasanKondisi,
        ]);
        
        $message = "Pengembalian berhasil diproses! Ringkasan: {$ringkasanKondisi} | " .
                "Denda Terlambat: Rp " . number_format($dendaTerlambat, 0, ',', '.') . 
                " | Denda Kondisi: Rp " . number_format($dendaKondisi, 0, ',', '.') .
                " | Total Denda: Rp " . number_format($total_denda, 0, ',', '.');
        
        return redirect()->route('petugas.menyetujui_kembali')->with('success', $message);
    }

    public function cetakLaporan() 
    {
        $laporans = \App\Models\Peminjaman::with(['user', 'alat'])->latest()->get();

        return view('petugas.laporan', compact('laporans'));
    }

    public function exportPdf(Request $request)
    {
        $tgl_mulai = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;

        $query = Peminjaman::with(['user', 'alat']);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereDate('created_at', '>=', $tgl_mulai)
                ->whereDate('created_at', '<=', $tgl_selesai);
        }

        $laporans = $query->latest()->get();

        $pdf = Pdf::loadView('petugas.laporan_pdf', compact('laporans', 'tgl_mulai', 'tgl_selesai'));
        
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }
}
