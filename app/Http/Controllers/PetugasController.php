<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
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
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'jumlah_disetujui' => 'nullable|integer|min:1',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $alat = $peminjaman->alat;

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
                'jumlah' => $jumlahDisetujui
            ]);

            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'title' => 'Peminjaman Disetujui',
                'message' => 'Peminjaman laptop ' . $alat->nama_alat . ' telah disetujui. Jumlah: ' . $jumlahDisetujui . ' unit.',
                'type' => 'success',
                'icon' => 'fas fa-check-circle',
                'action_url' => route('peminjam.kembali'),
            ]);

            session()->flash('peminjaman_disetujui', 'Peminjaman laptop ' . $alat->nama_alat . ' telah disetujui! Jumlah: ' . $jumlahDisetujui . ' unit');

            return back()->with('success', 'Peminjaman berhasil disetujui dengan jumlah ' . $jumlahDisetujui . ' unit!');

        } elseif ($request->status == 'ditolak') {
            $peminjaman->update(['status' => 'ditolak']);

            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'title' => 'Peminjaman Ditolak',
                'message' => 'Permintaan peminjaman laptop ' . $alat->nama_alat . ' telah ditolak.',
                'type' => 'danger',
                'icon' => 'fas fa-times-circle',
                'action_url' => route('peminjam.riwayat'),
            ]);

            return back()->with('success', 'Peminjaman berhasil ditolak!');

        } elseif ($request->has('kondisi')) {
            $peminjaman->update([
                'status' => 'dikembalikan',
                'catatan' => $request->kondisi
            ]);

            $alat->increment('stok_tersedia', $peminjaman->jumlah);
            return redirect()->back()->with('success', 'Alat berhasil dikembalikan!');
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
            'kondisi_unit' => 'required|array',
            'kondisi_unit.*' => 'required|in:baik,lecet,rusak,hilang,lainnya',
            'custom_biaya' => 'nullable|array',
            'custom_biaya.*' => 'nullable|numeric|min:0',
        ]);

        $pinjam = Peminjaman::with('alat')->findOrFail($id);
        
        if ($pinjam->status != 'dikembalikan') {
            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk dikonfirmasi!');
        }
        
        $kondisiUnits = $request->kondisi_unit;
        $waktuSekarang = Carbon::now('Asia/Jakarta');
        $total_denda = 0;
        $deadline = Carbon::parse($pinjam->tgl_kembali)->setTime(17, 0, 0);
        $dendaTerlambat = 0;
        
        if ($waktuSekarang->gt($deadline)) {
            $minutesLate = $deadline->diffInMinutes($waktuSekarang, false);
            $daysLate = (int) ceil($minutesLate / 1440);
            $dendaTerlambat = max(1, $daysLate) * 5000;
            $total_denda += $dendaTerlambat;
        }

        $hargaAlat = $pinjam->alat->harga_sewa ?? 0;
        $dendaKondisi = 0;
        
        $countBaik = 0;
        $countLecet = 0;
        $countRusak = 0;
        $countHilang = 0;
        $countCustom = 0;
        $customBiaya = $request->custom_biaya ?? [];
        
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
                    $customValue = isset($customBiaya[$index]) ? (int) $customBiaya[$index] : 0;
                    $dendaKondisi += max(0, $customValue);
                    $countCustom++;
                    break;
                case 'baik':
                    $countBaik++;
                    break;
            }
        }
        
        $total_denda += $dendaKondisi;
        $total_denda = max(0, $total_denda);
        $alat = $pinjam->alat;
        $stokDikembalikan = $countBaik + $countLecet + $countRusak + $countCustom;
        if ($stokDikembalikan > 0) {
            $alat->increment('stok_tersedia', $stokDikembalikan);
        }
        $ringkasanKondisi = "Baik:{$countBaik}, Lecet:{$countLecet}, Rusak:{$countRusak}, Hilang:{$countHilang}, Lainnya:{$countCustom}";
        
        $pinjam->update([
            'status'           => 'selesai',
            'kondisi'          => $kondisiUnits[0] ?? 'baik',
            'total_denda'      => $total_denda,
            'tgl_dikembalikan' => $waktuSekarang,
            'tujuan'           => $ringkasanKondisi,
        ]);

        Notifikasi::create([
            'user_id' => $pinjam->user_id,
            'title' => 'Pengembalian Dikonfirmasi',
            'message' => 'Pengembalian ' . $pinjam->alat->nama_alat . ' telah dikonfirmasi. Total denda: Rp ' . number_format($total_denda, 0, ',', '.'),
            'type' => 'success',
            'icon' => 'fas fa-check-circle',
            'action_url' => route('peminjam.riwayat'),
        ]);
        
        $message = "Pengembalian berhasil diproses! Ringkasan: {$ringkasanKondisi} | " .
                "Denda Terlambat: Rp " . number_format($dendaTerlambat, 0, ',', '.') . 
                " | Denda Kondisi: Rp " . number_format($dendaKondisi, 0, ',', '.') .
                " | Total Denda: Rp " . number_format($total_denda, 0, ',', '.');
        
        return redirect()->route('petugas.menyetujui_kembali')->with('success', $message);
    }

    public function kirimPengingat($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status != 'disetujui') {
            return response()->json(['success' => false, 'message' => 'Peminjaman belum disetujui atau sudah dikembalikan!']);
        }

        // Set session flash untuk peminjam
        session()->flash('pengembalian_reminder', 'Pengingat: Laptop ' . $peminjaman->alat->nama_alat . ' harus segera dikembalikan! Batas waktu: ' . \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d/m/Y'));

        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'title' => 'Pengingat Pengembalian',
            'message' => 'Laptop ' . $peminjaman->alat->nama_alat . ' harus segera dikembalikan. Batas waktu: ' . \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d/m/Y'),
            'type' => 'warning',
            'icon' => 'fas fa-bell',
            'action_url' => route('peminjam.kembali'),
        ]);

        return response()->json(['success' => true, 'message' => 'Pengingat berhasil dikirim ke peminjam!']);
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
