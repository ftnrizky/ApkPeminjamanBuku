@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="min-h-screen bg-slate-50">
        <div class="grid min-h-screen lg:grid-cols-2">
            <div class="relative hidden overflow-hidden bg-gradient-to-br from-slate-900 via-blue-800 to-sky-600 lg:flex">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.18),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.12),transparent_25%)]"></div>
                <div class="relative flex w-full flex-col justify-between p-12 text-white">
                    <div class="inline-flex w-fit items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                            <i class="fas fa-book-open text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-100">E-Pustaka</p>
                            <p class="text-lg font-semibold">New Member Access</p>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-100">Create Account</p>
                        <h1 class="mt-4 text-5xl font-bold leading-tight">
                            Mulai pengalaman meminjam dengan antarmuka yang lebih modern.
                        </h1>
                        <p class="mt-6 text-base leading-8 text-blue-50/90">
                            Buat akun untuk mengakses katalog, mengajukan peminjaman, dan melacak aktivitas perpustakaan Anda secara real-time.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-blue-100">Proses cepat</p>
                            <p class="mt-2 text-2xl font-bold">1 akun</p>
                            <p class="mt-2 text-sm text-blue-50/80">Akses katalog, riwayat, dan denda dari satu tempat.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-sm text-blue-100">Pengalaman rapi</p>
                            <p class="mt-2 text-2xl font-bold">Mobile ready</p>
                            <p class="mt-2 text-sm text-blue-50/80">Tampilan nyaman digunakan di desktop maupun ponsel.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center px-4 py-10 sm:px-6 lg:px-10">
                <div class="w-full max-w-xl">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500 text-white shadow-sm">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">E-Pustaka</p>
                            <p class="text-lg font-semibold text-slate-900">Create Account</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_20px_60px_-32px_rgba(15,23,42,0.28)] sm:p-10">
                        <div class="mb-8">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Register</p>
                            <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Buat akun baru</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                Lengkapi data di bawah ini untuk mulai menggunakan layanan perpustakaan digital.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4">
                                <p class="text-sm font-semibold text-rose-700">Ada beberapa input yang perlu diperbaiki:</p>
                                <ul class="mt-3 space-y-2 text-sm text-rose-600">
                                    @foreach ($errors->all() as $error)
                                        <li class="flex items-start gap-2">
                                            <i class="fas fa-circle text-[8px] mt-1.5"></i>
                                            <span>{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                                        placeholder="Nama Anda">
                                </div>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">No. WhatsApp</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                            class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                                            placeholder="08123456789">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                                            placeholder="anda@email.com">
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="password" required
                                            class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                                            placeholder="Masukkan password">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                                    <div class="relative">
                                        <i class="fas fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="password_confirmation" required
                                            class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                                            placeholder="Ulangi password">
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-500 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                                <i class="fas fa-user-plus text-xs"></i>
                                Daftar Sekarang
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Sudah punya akun?
                                <a href="{{ route('login') }}"
                                    class="font-semibold text-blue-600 transition duration-200 hover:text-blue-500">
                                    Login
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
