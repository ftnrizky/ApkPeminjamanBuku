@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="min-h-screen bg-slate-50">
        <div class="grid min-h-screen lg:grid-cols-2">
            <div class="relative hidden overflow-hidden bg-gradient-to-br from-blue-600 via-sky-500 to-cyan-400 lg:flex">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.28),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.18),transparent_30%)]"></div>
                <div class="relative flex w-full flex-col justify-between p-12 text-white">
                    <div class="inline-flex w-fit items-center gap-3 rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                            <i class="fas fa-book-open text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-100">E-Pustaka</p>
                            <p class="text-lg font-semibold">Borrowing Platform</p>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-100">Welcome Back</p>
                        <h1 class="mt-4 text-5xl font-bold leading-tight">
                            Akses perpustakaan digital dengan tampilan yang lebih rapi.
                        </h1>
                        <p class="mt-6 text-base leading-8 text-blue-50/90">
                            Masuk untuk meminjam buku, memantau riwayat, dan mengelola aktivitas Anda dalam satu dashboard yang nyaman digunakan.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <p class="text-sm text-blue-100">Akses cepat</p>
                                <p class="mt-2 text-2xl font-bold">24/7</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-100">Riwayat aman</p>
                                <p class="mt-2 text-2xl font-bold">Realtime</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-100">Antarmuka</p>
                                <p class="mt-2 text-2xl font-bold">Clean</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center px-4 py-10 sm:px-6 lg:px-10">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500 text-white shadow-sm">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">E-Pustaka</p>
                            <p class="text-lg font-semibold text-slate-900">Borrower Access</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_20px_60px_-32px_rgba(15,23,42,0.28)] sm:p-10">
                        <div class="mb-8">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Sign In</p>
                            <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Masuk ke akun Anda</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                Gunakan email dan password Anda untuk melanjutkan ke dashboard.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            @if (session('error'))
                                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Email Address</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-blue-400 focus:ring-blue-100' }} bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:ring-2"
                                        placeholder="anda@email.com">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <label class="text-sm font-semibold text-slate-700">Password</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"
                                            class="text-xs font-semibold text-blue-600 transition duration-200 hover:text-blue-500">
                                            Lupa Sandi?
                                        </a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="password" name="password" required
                                        class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-blue-400 focus:ring-blue-100' }} bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:ring-2"
                                        placeholder="Masukkan password">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="flex items-center gap-3 text-sm text-slate-600">
                                <input type="checkbox" name="remember"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-500 focus:ring-blue-400">
                                <span>Ingat saya di perangkat ini</span>
                            </label>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-500 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                                <span>Masuk</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Belum punya akun?
                                <a href="{{ route('register') }}"
                                    class="font-semibold text-blue-600 transition duration-200 hover:text-blue-500">
                                    Daftar sekarang
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
