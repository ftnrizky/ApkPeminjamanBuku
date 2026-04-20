@extends('layouts.app')

@section('content')
    <style>
        @keyframes float-up-down {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float-image {
            animation: float-up-down 3.5s ease-in-out infinite;
        }
    </style>
    <div
        class="min-h-screen flex items-center justify-center p-6 bg-gradient-to-br from-gray-50 via-white to-gray-100 relative overflow-hidden">
        <!-- Decorative Background Blobs -->
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gray-200/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-72 h-72 bg-gray-200/30 rounded-full blur-3xl"></div>

        <div class="w-full max-w-5xl grid md:grid-cols-2 gap-8 items-center relative z-10">
            <div class="hidden md:flex justify-center">
                <div class="animate-float-image">
                    <div class="w-64 h-64 flex items-center justify-center">
                        <img src="{{ asset('image/login.png') }}" alt="Login"
                            class="w-full h-full object-cover rounded-3xl">
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full bg-white rounded-2xl p-8 md:p-10 shadow-lg border border-gray-200 relative overflow-hidden">

                <!-- Header dengan Logo -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2.5 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2-8H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-gray-900">E-<span
                            class="text-blue-600">LAPTOP</span></span>
                </div>

                <!-- Title -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat <span
                            class="text-blue-600">Datang!</span></h2>
                    <p class="text-gray-500 text-sm font-medium mt-2">Masuk untuk mulai meminjam laptop</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                        <!-- Email -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400 @error('email') border-red-500 @enderror"
                                placeholder="anda@email.com">
                            @error('email')
                                <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-semibold text-gray-700">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lupa Sandi?</a>
                                @endif
                            </div>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm text-gray-900 placeholder-gray-400 @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('password')
                                <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2">
                            <span class="font-medium">Ingat Saya</span>
                        </label>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white py-3 rounded-lg font-bold text-sm uppercase tracking-wide shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span>Masuk</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>

                        <!-- Register Link -->
                        <p class="text-center text-sm text-gray-600">
                            Belum punya akun? <a href="{{ route('register') }}"
                                class="text-blue-600 hover:text-blue-700 font-bold">Daftar Sekarang</a>
                        </p>
                    </form>
                    @if (session('status'))
                        <div class="mt-4 text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
            </div>
        </div>
    </div>
@endsection
