@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/10 border border-gray-100 relative overflow-hidden">
        
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-[#062c21] p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-running text-white text-lg"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight italic text-[#062c21]">SPORT<span class="text-emerald-500">RENT</span></span>
        </div>

        <div class="mb-8 text-center md:text-left">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight italic uppercase">Create <span class="text-emerald-500">Account</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Gabung bersama komunitas atlet kami</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold" placeholder="Nama Anda">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">No. WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold" placeholder="08xxxx">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold" placeholder="atlet@email.com">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold" placeholder="••••••••">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold" placeholder="••••••••">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#062c21] hover:bg-emerald-900 text-white py-4 rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-95">
                    Daftar Sekarang <i class="fas fa-user-plus ml-2"></i>
                </button>
            </div>

            <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Login Saja</a>
            </p>
        </form>
    </div>
</div>
@endsection