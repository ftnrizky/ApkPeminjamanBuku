@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/10 border border-gray-100 relative overflow-hidden">
        
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-[#062c21] p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-running text-white text-lg"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight italic text-[#062c21]">SPORT<span class="text-emerald-500">RENT</span></span>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight italic uppercase">Welcome <span class="text-emerald-500 block">Back!</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Masuk untuk mengelola peminjaman</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold @error('email') border-rose-500 @enderror" placeholder="email@example.com">
                @error('email') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex justify-between px-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Password</label>
                </div>
                <input type="password" name="password" required class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold @error('password') border-rose-500 @enderror" placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between px-1">
                <label class="flex items-center gap-2 text-xs font-bold text-gray-500 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"> Ingat Saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Lupa Sandi?</a>
                @endif
            </div>

            <button type="submit" class="w-full bg-[#062c21] hover:bg-emerald-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-95">
                Sign In <i class="fas fa-arrow-right ml-2"></i>
            </button>

            <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">Daftar Sekarang</a>
            </p>
        </form>
    </div>
</div>
@endsection