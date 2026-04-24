@extends('layouts.admin')

@section('title', 'Blacklist User')

@section('content')
    <div class="space-y-6">
        @foreach ([
            'success' => ['border-emerald-200', 'bg-emerald-50', 'text-emerald-700', 'fa-check-circle'],
            'error' => ['border-rose-200', 'bg-rose-50', 'text-rose-700', 'fa-circle-exclamation'],
            'info' => ['border-sky-200', 'bg-sky-50', 'text-sky-700', 'fa-circle-info'],
        ] as $key => $alert)
            @if (session($key))
                <div class="flex items-start gap-3 rounded-2xl border {{ $alert[0] }} {{ $alert[1] }} px-5 py-4 shadow-sm">
                    <i class="fas {{ $alert[3] }} mt-0.5 {{ $alert[2] }}"></i>
                    <p class="text-sm font-medium {{ $alert[2] }}">{{ session($key) }}</p>
                </div>
            @endif
        @endforeach

        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-slate-400">Security Center</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Blacklist User</h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">
                    Kelola status blacklist pengguna. Akun yang diblokir akan otomatis logout dan tidak bisa mengakses sistem.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-emerald-500">Aktif</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700">{{ $totalActive }}</p>
                </div>
                <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-rose-500">Blacklist</p>
                    <p class="mt-2 text-2xl font-black text-rose-700">{{ $totalBlacklisted }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.users.blacklist.index') }}"
                class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="relative flex-1">
                    <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email user..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:bg-white">
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Cari User
                    </button>
                    <a href="{{ route('admin.users.blacklist.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.22em] text-slate-400">User</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Email</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Role</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f766e&color=fff"
                                            alt="Avatar {{ $user->name }}" class="h-11 w-11 rounded-2xl shadow-sm">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400">ID #{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-600">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->is_blacklisted)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                            Blacklist
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end">
                                        @if (auth()->id() === $user->id)
                                            <span class="inline-flex rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-400">
                                                Akun Anda
                                            </span>
                                        @elseif ($user->is_blacklisted)
                                            <form method="POST" action="{{ route('admin.users.unblacklist', $user->id) }}"
                                                onsubmit="return confirm('Aktifkan kembali user ini?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="search" value="{{ $search }}">
                                                <input type="hidden" name="page" value="{{ $users->currentPage() }}">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                                    Unblacklist
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.blacklist', $user->id) }}"
                                                onsubmit="return confirm('Blacklist user ini? User akan otomatis logout saat mencoba mengakses sistem.')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="search" value="{{ $search }}">
                                                <input type="hidden" name="page" value="{{ $users->currentPage() }}">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                                                    Blacklist
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-base font-semibold text-slate-700">User tidak ditemukan</p>
                                    <p class="mt-2 text-sm text-slate-500">Coba kata kunci lain untuk nama atau email.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 bg-slate-50/70 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                </p>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
