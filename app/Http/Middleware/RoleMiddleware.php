<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Alur:
     * 1. Kalau belum login → redirect ke /login (ditangani middleware 'auth' sebelumnya,
     *    tapi kita tetap handle sebagai safety net)
     * 2. Kalau sudah login tapi role tidak sesuai → redirect ke dashboard role-nya sendiri
     * 3. Kalau sudah login dan role sesuai → lanjut
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Safety net: belum login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Role sesuai → lanjutkan request
        if ($user->role === $role) {
            return $next($request);
        }

        // Role tidak sesuai → redirect ke dashboard role yang benar
        // Ini mencegah loop jika user salah akses URL
        return redirect($this->dashboardFor($user->role))
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }

    /**
     * Kembalikan URL dashboard berdasarkan role user.
     */
    protected function dashboardFor(string $role): string
    {
        return match($role) {
            'admin'    => '/admin/dashboard',
            'petugas'  => '/petugas/dashboard',
            'peminjam' => '/peminjam/dashboard',
            default    => '/',
        };
    }
}