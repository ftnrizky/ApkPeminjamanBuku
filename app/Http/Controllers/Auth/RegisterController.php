<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * 1. Ubah tujuan redirect setelah register
     */
    protected $redirectTo = '/peminjam/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 2. Tambahkan validasi 'no_hp' di sini
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_hp' => ['required', 'string', 'max:15'], // Tambahan dari kode lama
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * 3. Masukkan 'no_hp' dan 'role' ke database
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'], // Tambahan
            'password' => Hash::make($data['password']),
            'role' => 'peminjam', // Role default sesuai permintaanmu
        ]);
    }
}