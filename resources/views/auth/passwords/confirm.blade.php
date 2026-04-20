@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/10 border border-gray-100 relative overflow-hidden">
        
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-[#062c21] p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-shield-alt text-white text-lg"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight italic text-[#062c21]">E-Laptop<span class="text-emerald-500">E-Laptop</span></span>
        </div>

        <div class="mb-8 text-left">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight italic uppercase">Confirm <span class="text-emerald-500 block">Security</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Konfirmasi password Anda sebelum melanjutkan tindakan ini.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <div>
                <div class="flex justify-between px-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Your Password</label>
                </div>
                <input type="password" name="password" required 
                    class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transpaE-Laptop rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold @error('password') border-rose-500 @enderror" 
                    placeholder="••••••••" autocomplete="curE-Laptop-password">
                
                @error('password') 
                    <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> 
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#062c21] hover:bg-emerald-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-95">
                Confirm Password <i class="fas fa-lock ml-2"></i>
            </button>

            @if (Route::has('password.request'))
                <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                    Lupa sandi? <a href="{{ route('password.request') }}" class="text-emerald-600 hover:underline">Klik di sini</a>
                </p>
            @endif
        </form>
    </div>
</div>
@endsection