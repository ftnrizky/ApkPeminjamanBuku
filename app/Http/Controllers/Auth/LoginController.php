<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Setelah login berhasil
     */
    protected function authenticated(Request $request, $user)
    {
        ActivityLogger::login($user);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role === 'petugas') {
            return redirect('/petugas/dashboard');
        }

        return redirect('/peminjam/dashboard');
    }

    /**
     * Logout + logging
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            ActivityLogger::logout($user);
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}