@extends('layouts.app')

@section('content')
<style>
    @keyframes float-up-down {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .animate-float-image {
        animation: float-up-down 3.5s ease-in-out infinite;
    }
</style>
<div class="min-h-screen flex items-center justify-center p-6 bg-gradient-to-br from-gray-50 via-white to-gray-100 relative overflow-hidden">
    <!-- Decorative Background Blobs -->
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gray-200/40 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -left-32 w-72 h-72 bg-gray-200/30 rounded-full blur-3xl"></div>
    
    <div class="w-full max-w-5xl grid md:grid-cols-2 gap-8 items-center relative z-10">
        <!-- Left Side - Animated Image -->
        <div class="hidden md:flex justify-center">
            <div class="animate-float-image">
                <!-- Placeholder untuk gambar yang bisa diganti -->
               <div class="w-64 h-64 flex items-center justify-center">
                    <img src="{{ asset('image/register.png') }}" alt="Register" class="w-full h-full object-cover rounded-3xl">
                </div>
            </div>
        </div>
        
        <!-- Right Side - Register Form -->
        <div class="w-full bg-white rounded-2xl p-8 md:p-10 shadow-lg border border-gray-200 relative overflow-hidden">
            <!-- Header dengan Logo -->
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2.5 rounded-lg shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2-8H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight text-gray-900">E-<span class="text-blue-600">LAPTOP</span></span>
            </div>

            <!-- Title -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Buat <span class="text-blue-600">Akun</span></h2>
                <p class="text-gray-500 text-sm font-medium mt-2">Daftar untuk mulai meminjam laptop</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <!-- Nama Lengkap -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400" 
                        placeholder="Nama Anda">
                    @error('name')
                        <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- No. WhatsApp & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">No. WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400" 
                            placeholder="Ex: 08123456789">
                        @error('no_hp')
                            <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400" 
                            placeholder="anda@email.com">
                        @error('email')
                            <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Password</label>
                        <input type="password" name="password" required 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400" 
                            placeholder="••••••••">
                        @error('password')
                            <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400" 
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white py-3 rounded-lg font-bold text-sm uppercase tracking-wide shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m0 0h3m-6-9a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Daftar Sekarang
                    </button>
                </div>

                <!-- Login Link -->
                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-bold">Login</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection