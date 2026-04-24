<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * Middleware 'guest': jika user SUDAH login, redirect ke dashboard-nya.
     * Ini mencegah user yang sudah login mengakses halaman /login lagi.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect ke dashboard yang sesuai role — bukan ke /home yang tidak ada
                return redirect($this->redirectTo($user->role));
            }
        }

        return $next($request);
    }

    /**
     * Tentukan tujuan redirect berdasarkan role.
     */
    protected function redirectTo(string $role): string
    {
        return match($role) {
            'admin'    => '/admin/dashboard',
            'petugas'  => '/petugas/dashboard',
            'peminjam' => '/peminjam/dashboard',
            default    => '/',
        };
    }
}