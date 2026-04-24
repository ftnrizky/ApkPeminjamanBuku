<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserBlacklistController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("FIELD(role, 'admin', 'petugas', 'peminjam')")
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'totalBlacklisted' => User::where('is_blacklisted', true)->count(),
            'totalActive' => User::where('is_blacklisted', false)->count(),
        ]);
    }

    public function blacklist(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()
                ->route('admin.users.blacklist.index')
                ->with('error', 'User tidak ditemukan.');
        }

        if ((int) Auth::id() === $user->id) {
            return redirect()
                ->route('admin.users.blacklist.index')
                ->with('error', 'Admin tidak bisa blacklist akun sendiri.');
        }

        if ($user->is_blacklisted) {
            return redirect()
                ->route('admin.users.blacklist.index')
                ->with('info', 'User tersebut sudah masuk blacklist.');
        }

        $user->forceFill([
            'is_blacklisted' => true,
        ])->save();

        return redirect()
            ->route('admin.users.blacklist.index', $request->only('search', 'page'))
            ->with('success', "User {$user->name} berhasil di-blacklist.");
    }

    public function unblacklist(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()
                ->route('admin.users.blacklist.index')
                ->with('error', 'User tidak ditemukan.');
        }

        if (!$user->is_blacklisted) {
            return redirect()
                ->route('admin.users.blacklist.index')
                ->with('info', 'User tersebut sudah aktif.');
        }

        $user->forceFill([
            'is_blacklisted' => false,
        ])->save();

        return redirect()
            ->route('admin.users.blacklist.index', $request->only('search', 'page'))
            ->with('success', "User {$user->name} berhasil diaktifkan kembali.");
    }
}
