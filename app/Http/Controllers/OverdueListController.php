<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OverdueListController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat'])
            ->whereNotIn('status', ['kembali', 'selesai', 'ditolak']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->whereHas('alat', function ($q) use ($request) {
                $q->where('kategori', $request->kategori);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('alat', function ($a) use ($request) {
                    $a->where('nama_alat', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->boolean('overdue_only')) {
            $query->where('tgl_kembali', '<', now());
        }

        $peminjamanList = $query->orderBy('tgl_kembali', 'ASC')->paginate(20);

        // FIX PENTING (jangan pakai map langsung)
        $peminjamanList->getCollection()->transform(function ($p) {
            $deadline = $p->tgl_kembali ? Carbon::parse($p->tgl_kembali) : now();
            $diff = now()->diffInDays($deadline, false);

            $p->is_overdue = $diff < 0;
            $p->days_remaining = abs($diff);
            $p->is_critical = $diff < 0 && abs($diff) > 3;

            return $p;
        });

        $kategoris = \App\Models\Alat::select('kategori')->distinct()->get();
        $statuses = ['pinjam', 'disetujui'];

        return view('admin.overdue_list', compact('peminjamanList', 'kategoris', 'statuses'));
    }

    public function staffIndex(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat'])
            ->whereNotIn('status', ['kembali', 'selesai', 'ditolak']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('alat', function ($a) use ($request) {
                    $a->where('nama_alat', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->boolean('overdue_only')) {
            $query->where('tgl_kembali', '<', now());
        }

        $peminjamanList = $query->orderBy('tgl_kembali', 'ASC')->paginate(20);

        // FIX UTAMA DI SINI
        $peminjamanList->getCollection()->transform(function ($p) {
            $deadline = $p->tgl_kembali ? Carbon::parse($p->tgl_kembali) : now();
            $diff = now()->diffInDays($deadline, false);

            $p->is_overdue = $diff < 0;
            $p->days_remaining = abs($diff);
            $p->is_critical = $diff < 0 && abs($diff) > 3;

            return $p;
        });

        return view('petugas.overdue_list', compact('peminjamanList'));
    }

    public function sendReminder($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if (in_array($peminjaman->status, ['kembali', 'selesai'])) {
            return response()->json(['message' => 'Sudah dikembalikan'], 400);
        }

        $deadline = Carbon::parse($peminjaman->tgl_kembali);
        $diff = now()->diffInDays($deadline, false);

        $message = $diff < 0
            ? "Terlambat {$this->formatDays(abs($diff))}"
            : "Sisa {$this->formatDays($diff)}";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    private function formatDays($days)
    {
        if ($days == 0) return 'hari ini';
        if ($days == 1) return '1 hari';
        return "$days hari";
    }
}