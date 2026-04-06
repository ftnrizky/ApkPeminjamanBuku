<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;

class AdminController extends Controller
{
    public function index()
    {
        return view ('admin.dashboard');
    }

    /* ======================
       CRUD USER
       ====================== */
    public function users()
    {
        $users = User::where('role', 'peminjam')->paginate(10);
        return view('admin.kelola_user', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        return back()->with('success', 'Member berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'no_hp' => 'nullable|string|max:15',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Data member diperbarui!');
    }

    public function destroyUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Member telah dihapus!');
    }

    /* ======================
       CRUD ALAT
       ====================== */
    public function alat(Request $request)
    {
        $query = Alat::query();

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        $alats = $query->latest()->get();
        return view('admin.alat', compact('alats'));
    }

    public function storeAlat(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required',
            'kategori' => 'required',
            'stok_total' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'kondisi' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        $path = $request->hasFile('foto') ? $request->file('foto')->store('alats', 'public') : null;

        Alat::create([
            'nama_alat' => $request->nama_alat,
            'slug' => Str::slug($request->nama_alat) . '-' . Str::random(5),
            'kategori' => $request->kategori,
            'stok_total' => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'harga_sewa' => $request->harga_sewa,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'foto' => $path,
        ]);

        return back()->with('success', 'Alat berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);
        
        $request->validate([
            'nama_alat' => 'required',
            'stok_total' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'kondisi' => 'required'
        ]);

        if ($request->hasFile('foto')) {
            if ($alat->foto) Storage::disk('public')->delete($alat->foto);
            $alat->foto = $request->file('foto')->store('alats', 'public');
        }

        $alat->update([
            'nama_alat' => $request->nama_alat,
            'kategori' => $request->kategori,
            'stok_total' => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'harga_sewa' => $request->harga_sewa,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Data diperbarui!');
    }

    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);

        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }

        $alat->delete();

        return redirect()->back()->with('success', 'Peralatan berhasil dihapus dari katalog.');
    }

    /* ======================
       CRUD PEMINJAMAN
       ====================== */
    public function peminjaman()
    {
        $users = User::where('role', 'atlet')->orderBy('name')->get();

        $alats = Alat::where('stok_tersedia', '>', 0)->get();

        $peminjamanBerlangsung = Peminjaman::with(['user', 'alat'])
                                ->where('status', 'disetujui')
                                ->latest()
                                ->get();

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
            'status'      => 'disetujui', // Langsung aktif
        ]);

        $alat->decrement('stok_tersedia', $request->jumlah);

        return redirect()->back()->with('success', 'Peminjaman berhasil diproses!');
    }

    public function kembalikanAlat($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Transaksi ini tidak valid untuk dikembalikan.');
        }

        $peminjaman->update([
            'status' => 'selesai',
            'tgl_dikembalikan' => now(), // Catat waktu asli pengembalian
        ]);

        $peminjaman->alat->increment('stok_tersedia', $peminjaman->jumlah);

        return redirect()->back()->with('success', 'Alat telah berhasil dikembalikan!');
    }

    /* ======================
       CRUD PENGEMBALIAN
       ====================== */
    public function pengembalian()
    {
        $riwayat = Peminjaman::with(['user', 'alat'])
            ->where('status', 'selesai')
            ->latest('updated_at')
            ->get();

        return view('admin.pengembalian', compact('riwayat'));
    }
}
