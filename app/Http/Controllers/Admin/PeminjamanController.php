<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    /* ======================
       CRUD PEMINJAMAN
       ====================== */
    public function peminjaman()
    {
        $users = User::where('role', 'peminjam')->orderBy('name')->get();

        $alats = Alat::where('stok_tersedia', '>', 0)->get();

        $peminjamanBerlangsung = Peminjaman::with(['user', 'alat'])->latest()->paginate(10);

        return view('admin.peminjaman', compact('users', 'alats', 'peminjamanBerlangsung'));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'alat_id' => 'required|exists:alats,id',
            'jumlah'  => 'required|integer|min:1',
            'durasi'  => 'required|integer|min:1',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        if ($alat->stok_tersedia < $request->jumlah) {
            return back()->with('error', 'Stok alat tidak mencukupi!');
        }

        $tgl_pinjam = now();
        $tgl_kembali = now()->addDays($request->durasi);

        Peminjaman::create([
            'user_id'     => $request->user_id,
            'alat_id'     => $request->alat_id,
            'jumlah'      => $request->jumlah,
            'tgl_pinjam'  => $tgl_pinjam,
            'tgl_kembali' => $tgl_kembali,
            'status'      => 'disetujui',
        ]);

        $alat->decrement('stok_tersedia', $request->jumlah);

        return redirect()->back()->with('success', 'Peminjaman berhasil diproses!');
    }

    public function updatePeminjaman(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $alat = Alat::findOrFail($pinjam->alat_id);
        $alat->increment('stok_tersedia', $pinjam->jumlah);

        if ($alat->fresh()->stok_tersedia < $request->jumlah) {
            $alat->decrement('stok_tersedia', $pinjam->jumlah);
            return back()->with('error', 'Stok tidak mencukupi untuk perubahan ini!');
        }

        $pinjam->update([
            'user_id' => $request->user_id,
            'jumlah' => $request->jumlah,
            'tgl_kembali' => now()->addDays($request->durasi),
        ]);

        $alat->decrement('stok_tersedia', $request->jumlah);

        return redirect()->back()->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    public function destroyPeminjaman($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status == 'disetujui' || $pinjam->status == 'menunggu') {
            $pinjam->alat->increment('stok_tersedia', $pinjam->jumlah);
        }

        $pinjam->delete();
        return redirect()->back()->with('success', 'Data peminjaman telah dihapus.');
    }

    public function verifikasiPeminjaman(Request $request, $id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $status = $request->status;

        if ($status == 'ditolak') {
            $pinjam->alat->increment('stok_tersedia', $pinjam->jumlah);
        }

        $pinjam->update(['status' => $status]);

        return redirect()->back()->with('success', 'Status peminjaman diperbarui menjadi ' . $status);
    }

    public function kembalikanPeminjaman($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status == 'disetujui') {
            // 1. Gunakan startOfDay() agar perhitungan murni berdasarkan tanggal (abaikan jam)
            $tgl_kembali = \Carbon\Carbon::parse($pinjam->tgl_kembali)->startOfDay();
            $tgl_sekarang = now()->startOfDay();
            $total_denda = 0;

            // 2. Cek keterlambatan
            if ($tgl_sekarang->gt($tgl_kembali)) {
                // Gunakan diffInDays agar hasilnya integer (angka bulat)
                $hari_terlambat = $tgl_sekarang->diffInDays($tgl_kembali);
                $total_denda = $hari_terlambat * 5000; 
            }

            // 3. Update stok alat
            if($pinjam->alat) {
                $pinjam->alat->increment('stok_tersedia', $pinjam->jumlah);
            }

            // 4. Update data peminjaman
            $pinjam->update([
                'status' => 'selesai',
                'tgl_dikembalikan' => now(), // Catat waktu asli pengembalian
                'total_denda' => $total_denda 
            ]);

            return redirect()->back()->with('success', 'Alat berhasil dikembalikan! Denda: Rp ' . number_format($total_denda, 0, ',', '.'));
        }

        return redirect()->back()->with('error', 'Status tidak valid untuk dikembalikan.');
    }

    /* ======================
       CRUD PENGEMBALIAN
       ====================== */
    public function pengembalian(Request $request)
    {

        $query = Peminjaman::with(['user', 'alat'])
                    ->whereIn('status', ['selesai', 'dikembalikan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('alat', function($qAlat) use ($search) {
                    $qAlat->where('nama_alat', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function($qUser) use ($search) {
                    $qUser->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $peminjamans = $query->latest('tgl_dikembalikan')->paginate(10);

        return view('admin.pengembalian', compact('peminjamans'));
    }
}
