<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     * Hanya bisa diakses user yang belum login (dijaga middleware 'guest' di route).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login: autentikasi → regenerate session → redirect by role.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Autentikasi user (lempar ValidationException jika gagal)
        $request->authenticate();

        // 2. Regenerate session ID — WAJIB untuk mencegah session fixation attack
        $request->session()->regenerate();

        // 3. Ambil user yang baru saja login
        $user = Auth::user();

        if ($user->is_blacklisted) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Akun Anda telah diblokir oleh admin');
        }

        // 4. Catat activity log login (jika service tersedia)
        if (class_exists(\App\Services\ActivityLogger::class)) {
            ActivityLogger::login($user);
        }

        // 5. Redirect berdasarkan role
        return match($user->role) {
            'admin'    => redirect()->intended('/admin/dashboard'),
            'petugas'  => redirect()->intended('/petugas/dashboard'),
            'peminjam' => redirect()->intended('/peminjam/dashboard'),
            // Fallback aman jika role tidak dikenali
            default    => redirect('/'),
        };
    }

    /**
     * Logout: catat log → invalidate session → redirect ke home.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Catat activity log logout sebelum session dihapus
        if ($user && class_exists(\App\Services\ActivityLogger::class)) {
            ActivityLogger::logout($user);
        }

        Auth::guard('web')->logout();

        // Invalidate seluruh session & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
