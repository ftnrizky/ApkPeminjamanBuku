<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Peminjaman;

class UserController extends Controller
{
    public function users()
    {
        $users = User::whereIn('role', ['peminjam', 'petugas'])->paginate(10);
        return view('admin.kelola_user', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users',
            'no_hp'          => 'nullable|string|max:15',
            'password'       => 'required|string|min:6',
            'role'           => 'nullable|in:peminjam,petugas',
            'is_blacklisted' => 'sometimes|boolean',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'password'       => Hash::make($request->password),
            'role'           => $request->role ?? 'peminjam',
            'is_blacklisted' => $request->boolean('is_blacklisted'),
        ]);

        // ── Catat log ────────────────────────────────────────────────────────
        ActivityLogger::tambahUser($user);

        return redirect()->route('admin.kelola_user')->with('success', 'Member berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $id,
            'no_hp'          => 'nullable|string|max:15',
            'role'           => 'required|in:peminjam,petugas',
            'is_blacklisted' => 'sometimes|boolean',
        ]);

        $data = [
            'name'           => $request->name,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'role'           => $request->role,
            'is_blacklisted' => $request->boolean('is_blacklisted'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // ── Catat log ────────────────────────────────────────────────────────
        ActivityLogger::editUser($user->fresh());

        return redirect()->route('admin.kelola_user')->with('success', 'Data member diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $sedangMeminjam = Peminjaman::where('user_id', $id)
                                     ->whereIn('status', ['disetujui', 'pending', 'dikembalikan'])
                                     ->exists();

        if ($sedangMeminjam) {
            return back()->with('error', 'Member sedang memiliki peminjaman aktif, tidak dapat dihapus!');
        }

        // ── Catat log SEBELUM delete ──────────────────────────────────────────
        ActivityLogger::hapusUser($user);

        $user->delete();

        return redirect()->route('admin.kelola_user')->with('success', 'Member telah dihapus!');
    }

    public function exportPdf(Request $request)
    {
        $tgl_mulai   = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');

        $query = User::whereIn('role', ['peminjam', 'petugas']);

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereDate('created_at', '>=', $tgl_mulai)
                  ->whereDate('created_at', '<=', $tgl_selesai);
        } elseif ($tgl_mulai) {
            $query->whereDate('created_at', '>=', $tgl_mulai);
        } elseif ($tgl_selesai) {
            $query->whereDate('created_at', '<=', $tgl_selesai);
        }

        $users         = $query->orderBy('name')->get();
        $totalUsers    = $users->count();
        $totalPeminjam = $users->where('role', 'peminjam')->count();
        $totalPetugas  = $users->where('role', 'petugas')->count();

        $pdf = Pdf::loadView('admin.users_pdf', compact('users', 'tgl_mulai', 'tgl_selesai', 'totalUsers', 'totalPeminjam', 'totalPetugas'));

        return $pdf->download('laporan-member-' . now()->format('Y-m-d') . '.pdf');
    }
}