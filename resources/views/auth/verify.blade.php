@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/10 border border-gray-100 relative overflow-hidden text-center">
        
        <div class="flex justify-center items-center gap-3 mb-8">
            <div class="bg-[#062c21] p-2 rounded-xl rotate-3 shadow-lg shadow-emerald-500/20">
                <i class="fas fa-envelope-open-text text-white text-lg"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight italic text-[#062c21]">SPORT<span class="text-emerald-500">RENT</span></span>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight italic uppercase">Verify <span class="text-emerald-500 block">Your Email</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-2 px-4 leading-relaxed">
                Hampir selesai! Silakan cek Inbox atau folder Spam email Anda untuk link verifikasi.
            </p>
        </div>

        @if (session('resent'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-2xl flex items-center justify-center animate-bounce">
                <i class="fas fa-check-circle mr-2 text-sm"></i>
                Link baru telah dikirim ke email Anda!
            </div>
        @endif

        <div class="space-y-6">
            <div class="p-6 bg-gray-50 rounded-[1.5rem] border border-dashed border-gray-200">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tidak menerima email?</p>
                <form class="mt-3" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="text-xs font-black text-emerald-600 hover:text-emerald-700 underline uppercase tracking-widest transition-all">
                        Kirim Ulang Link Verifikasi
                    </button>
                </form>
            </div>

            <a href="{{ route('login') }}" class="inline-block text-[10px] font-black text-gray-400 hover:text-[#062c21] uppercase tracking-[0.2em] transition-all">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
            </a>
        </div>
    </div>
</div>
@endsection