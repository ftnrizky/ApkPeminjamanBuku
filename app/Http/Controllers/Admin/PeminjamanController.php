<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    /* ======================
       CRUD PEMINJAMAN
       ====================== */
    public function peminjaman()
    {
        $users = User::where('role', 'peminjam')->orderBy('name')->get();
        $alats = Alat::where('stok_tersedia', '>', 0)->get();
        $peminjamanBerlangsung = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->paginate(10);

        return view('admin.peminjaman', compact('users', 'alats', 'peminjamanBerlangsung'));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'alat_id' => 'required|exists:alats,id',
            'jumlah'  => 'required|integer|min:1',
            'tgl_kembali' => 'required|date|after_or_equal:today',
            'tujuan'  => 'required|string|max:500',
        ]);

        $tglPinjam = now()->startOfDay(); 
        $tglKembali = Carbon::parse($request->tgl_kembali)->startOfDay();
        $durasi = $tglPinjam->diffInDays($tglKembali);

        if ($durasi > 3) {
            return back()->with('error', "Batas maksimal peminjaman adalah 3 hari!");
        }

        $alat = Alat::findOrFail($request->alat_id);
        if ($alat->stok_tersedia < $request->jumlah) {
            return back()->with('error', 'Stok alat tidak mencukupi!');
        }

        $peminjaman = Peminjaman::create([
            'user_id'     => $request->user_id,
            'alat_id'     => $request->alat_id,
            'jumlah'      => $request->jumlah,
            'tgl_pinjam'  => now(),
            'tgl_kembali' => $request->tgl_kembali,
            'tujuan'      => $request->tujuan,
            'status'      => 'pending',
        ]);

        User::whereIn('role', ['admin', 'petugas'])->get()->each(function ($staff) use ($peminjaman) {
            Notifikasi::create([
                'user_id' => $staff->id,
                'title' => 'Permintaan Peminjaman Baru',
                'message' => 'Pengguna ' . $peminjaman->user->name . ' mengajukan peminjaman ' . $peminjaman->alat->nama_alat . ' x ' . $peminjaman->jumlah . ' unit.',
                'type' => 'info',
                'icon' => 'fas fa-folder-open',
                'action_url' => route('petugas.menyetujui_peminjaman'),
            ]);
        });
        
        return redirect()->back()->with('success', 'Permintaan peminjaman terkirim!');
    }

    public function destroyPeminjaman($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        if ($pinjam->status == 'disetujui') {
            $pinjam->alat->increment('stok_tersedia', $pinjam->jumlah);
        }

        $pinjam->delete();
        return redirect()->back()->with('success', 'Data peminjaman telah dihapus.');
    }

    public function verifikasiPeminjaman(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'jumlah_disetujui' => 'nullable|integer|min:1',
        ]);

        $pinjam = Peminjaman::findOrFail($id);
        $status = $request->status;

        if ($status == 'disetujui') {
            $jumlahDisetujui = $request->jumlah_disetujui ?? $pinjam->jumlah;

            if ($jumlahDisetujui > $pinjam->jumlah) {
                return back()->with('error', 'Jumlah yang disetujui tidak boleh melebihi jumlah yang diajukan!');
            }

            if ($pinjam->alat->stok_tersedia < $jumlahDisetujui) {
                return back()->with('error', 'Stok alat tidak mencukupi! Stok tersedia: ' . $pinjam->alat->stok_tersedia);
            }

            $pinjam->alat->decrement('stok_tersedia', $jumlahDisetujui);
            $pinjam->update([
                'status' => 'disetujui',
                'jumlah' => $jumlahDisetujui
            ]);

            Notifikasi::create([
                'user_id' => $pinjam->user_id,
                'title' => 'Peminjaman Disetujui',
                'message' => 'Peminjaman laptop ' . $pinjam->alat->nama_alat . ' telah disetujui dengan jumlah ' . $jumlahDisetujui . ' unit.',
                'type' => 'success',
                'icon' => 'fas fa-check-circle',
                'action_url' => route('peminjam.kembali'),
            ]);

            session()->flash('peminjaman_disetujui', 'Peminjaman laptop ' . $pinjam->alat->nama_alat . ' telah disetujui! Jumlah: ' . $jumlahDisetujui . ' unit');

            return back()->with('success', 'Peminjaman berhasil DISETUJUI dengan jumlah ' . $jumlahDisetujui . ' unit!');

        } elseif ($status == 'ditolak') {
            $pinjam->update(['status' => 'ditolak']);

            Notifikasi::create([
                'user_id' => $pinjam->user_id,
                'title' => 'Peminjaman Ditolak',
                'message' => 'Permintaan peminjaman laptop ' . $pinjam->alat->nama_alat . ' telah ditolak.',
                'type' => 'danger',
                'icon' => 'fas fa-times-circle',
                'action_url' => route('peminjam.riwayat'),
            ]);

            return back()->with('success', 'Peminjaman DITOLAK!');
        }

        return back()->with('error', 'Status tidak valid!');
    }

    public function kembalikanPeminjaman(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|in:baik,lecet,rusak,hilang',
            'catatan' => 'nullable|string|max:500'
        ]);

        $pinjam = Peminjaman::with(['alat', 'user'])->findOrFail($id);
        
        if ($pinjam->status != 'disetujui') {
            return redirect()->back()->with('error', 'Status peminjaman harus "disetujui" untuk diproses pengembalian!');
        }
        
        $kondisi = $request->kondisi;
        $waktuSekarang = Carbon::now('Asia/Jakarta');
        $total_denda = 0;
        $deadline = Carbon::parse($pinjam->tgl_kembali)->setTime(17, 0, 0);
        
        if ($waktuSekarang->gt($deadline)) {
            $minutesLate = $deadline->diffInMinutes($waktuSekarang, false);
            $daysLate = (int) ceil($minutesLate / 1440);
            $total_denda += max(1, $daysLate) * 5000;
        }

        switch ($kondisi) {
            case 'hilang':
                $total_denda += 500000 * $pinjam->jumlah;
                break;
            case 'rusak':
                $total_denda += 200000;
                break;
            case 'lecet':
                $total_denda += 50000;
                break;
            case 'baik':
                $total_denda += 0;
                break;
        }
        
        $total_denda = max(0, $total_denda);
        
        $updateData = [
            'status'           => 'selesai',
            'kondisi'          => $kondisi,
            'total_denda'      => $total_denda,
            'tgl_dikembalikan' => $waktuSekarang,
        ];
        
        if ($request->filled('catatan')) {
            $updateData['tujuan'] = $request->catatan;
        }
        
        $pinjam->update($updateData);
        
        if ($kondisi == 'baik' || $kondisi == 'lecet' || $kondisi == 'rusak') {
            $pinjam->alat()->increment('stok_tersedia', $pinjam->jumlah);
        }

        Notifikasi::create([
            'user_id' => $pinjam->user_id,
            'title' => 'Pengembalian Diproses',
            'message' => 'Pengembalian ' . $pinjam->alat->nama_alat . ' telah diproses. Total denda: Rp ' . number_format($total_denda, 0, ',', '.'),
            'type' => 'success',
            'icon' => 'fas fa-undo-alt',
            'action_url' => route('peminjam.riwayat'),
        ]);
        
        $message = "Pengembalian berhasil diproses! Kondisi: " . ucfirst($kondisi) . 
                   " | Total Denda: Rp " . number_format($total_denda, 0, ',', '.');
        
        return redirect()->route('admin.pengembalian')->with('success', $message);
    }

    /* ======================
    CRUD PENGEMBALIAN (RIWAYAT)
    ====================== */
    public function pengembalian(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat'])
                ->where('status', 'selesai')
                ->whereNotNull('tgl_dikembalikan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('alat', function($qAlat) use ($search) {
                    $qAlat->where('nama_alat', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%");
                });
            });
        }

        $peminjamans = $query->latest('tgl_dikembalikan')->paginate(10);
            
        return view('admin.pengembalian', compact('peminjamans'));
    }

    public function exportPeminjamanPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('alat', function($qAlat) use ($search) {
                    $qAlat->where('nama_alat', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%");
                });
            });
        }

        $peminjamans = $query->latest()->get();
        $totalPeminjaman = $peminjamans->count();
        $totalDisetujui = $peminjamans->where('status', 'disetujui')->count();
        $totalPending = $peminjamans->where('status', 'pending')->count();
        $totalDitolak = $peminjamans->where('status', 'ditolak')->count();

        $pdf = Pdf::loadView('admin.peminjaman_pdf', compact('peminjamans', 'totalPeminjaman', 'totalDisetujui', 'totalPending', 'totalDitolak'));
        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportPengembalianPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat'])
            ->whereNotNull('tgl_dikembalikan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('alat', function($qAlat) use ($search) {
                    $qAlat->where('nama_alat', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%");
                });
            });
        }

        $peminjamans = $query->latest('tgl_dikembalikan')->get();
        $totalPengembalian = $peminjamans->count();
        $totalDenda = $peminjamans->sum('total_denda');

        $pdf = Pdf::loadView('admin.pengembalian_pdf', compact('peminjamans', 'totalPengembalian', 'totalDenda'));
        return $pdf->download('laporan-pengembalian-' . now()->format('Y-m-d') . '.pdf');
    }
}