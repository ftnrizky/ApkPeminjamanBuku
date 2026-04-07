@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/10 border border-gray-100 relative overflow-hidden">
        
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-[#062c21] p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-lock text-white text-lg"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight italic text-[#062c21]">SPORT<span class="text-emerald-500">RENT</span></span>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight italic uppercase">Reset <span class="text-emerald-500 block">Password</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Buat kata sandi baru yang lebih aman</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold @error('email') border-rose-500 @enderror" readonly>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">New Password</label>
                <input type="password" name="password" required class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold @error('password') border-rose-500 @enderror" placeholder="••••••••">
                @error('password') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-[#062c21] hover:bg-emerald-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-95">
                Update Password <i class="fas fa-check-circle ml-2"></i>
            </button>
        </form>
    </div>
</div>
@endsection