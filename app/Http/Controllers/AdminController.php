<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;

class AdminController extends Controller
{
    public function index()
    {
        $totalMember = User::where('role', 'peminjam')->count(); 
        $totalAlat = Alat::count();
        $sewaAktif = Peminjaman::where('status', 'disetujui')->count();
        $dikembalikan = Peminjaman::where('status', 'selesai')->count();
        
        $labels = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->translatedFormat('l'); 
            $counts[] = Peminjaman::whereDate('created_at', $date)->count();
        }

        return view('admin.dashboard', compact(
            'totalMember', 
            'totalAlat', 
            'sewaAktif', 
            'dikembalikan', 
            'labels', 
            'counts'
        ));
    }
}
