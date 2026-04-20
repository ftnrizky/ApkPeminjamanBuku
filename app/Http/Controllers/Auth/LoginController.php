<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // Redirect berdasarkan role user
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } 
        
        if ($user->role === 'petugas') {
            return redirect('/petugas/dashboard');
        }

        // Default untuk peminjam (jika tidak ada role admin/petugas)
        return redirect('/peminjam/dashboard');
    }

    // Override home untuk mengarahkan dengan benar
    protected function redirectTo()
    {
        return '/peminjam/dashboard';
    }
}
