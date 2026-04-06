<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 1. TAMBAHKAN INI
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role  <-- 2. Tambahkan parameter role di sini
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 3. Cek apakah user login DAN apakah rolenya TIDAK SAMA dengan yang diminta
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}