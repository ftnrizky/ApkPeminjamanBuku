@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/10 border border-gray-100 relative overflow-hidden">
        
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-[#062c21] p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-key text-white text-lg"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight italic text-[#062c21]">SPORT<span class="text-emerald-500">RENT</span></span>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight italic uppercase">Forgot <span class="text-emerald-500 block">Password?</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Kami akan kirimkan link reset ke email Anda</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-2xl flex items-center">
                <i class="fas fa-check-circle mr-2 text-sm"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-bold @error('email') border-rose-500 @enderror" placeholder="email@example.com">
                @error('email') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-[#062c21] hover:bg-emerald-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-95">
                Send Reset Link <i class="fas fa-paper-plane ml-2"></i>
            </button>

            <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                Ingat passwordnya? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Kembali Login</a>
            </p>
        </form>
    </div>
</div>
@endsection