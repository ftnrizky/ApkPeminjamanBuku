@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight">
                Log <span class="text-cyan-600">Aktivitas</span>
            </h1>
            <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">
                Pantau semua aktivitas pengguna dalam sistem
            </p>
        </div>
        <a href="{{ route('admin.activity_log.export_pdf', request()->query()) }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700
              text-white font-bold px-5 py-2.5 rounded-lg transition-all hover:shadow-lg shadow-md active:scale-95 text-sm">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>

    {{-- ── Filter Section ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fas fa-filter text-cyan-600 text-sm"></i>
            <h2 class="text-sm font-bold text-slate-800">Filter Log Aktivitas</h2>
        </div>

        <form method="GET" action="{{ route('admin.activity_log') }}" class="px-5 py-4">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">

                {{-- Search --}}
                <div class="col-span-2 lg:col-span-2 flex flex-col gap-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        Cari User / Aktivitas
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-2.5 flex items-center text-slate-400">
                            <i class="fas fa-search text-[10px]"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama atau deskripsi..."
                            class="w-full pl-7 pr-2 py-2 bg-slate-50 border border-slate-200 rounded-lg
                           focus:ring-2 focus:ring-cyan-400 outline-none text-[11px]">
                    </div>
                </div>

                {{-- Activity Type --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        Tipe Aktivitas
                    </label>
                    <select name="activity_type"
                        class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-lg
                       focus:ring-2 focus:ring-cyan-400 outline-none text-[11px]">
                        <option value="">Semua Tipe</option>
                        @foreach ($activityTypes as $type)
                            <option value="{{ $type->activity_type }}"
                                {{ request('activity_type') === $type->activity_type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type->activity_type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Role --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        Role Pengguna
                    </label>
                    <select name="user_role"
                        class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-lg
                       focus:ring-2 focus:ring-cyan-400 outline-none text-[11px]">
                        <option value="">Semua Role</option>
                        @foreach ($userRoles as $role)
                            <option value="{{ $role->user_role }}"
                                {{ request('user_role') === $role->user_role ? 'selected' : '' }}>
                                {{ ucfirst($role->user_role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date --}}
                <div class="col-span-2 lg:col-span-2 flex flex-col gap-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        Rentang Tanggal
                    </label>
                    <div class="flex gap-1 items-center">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full min-w-0 px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg
                           focus:ring-2 focus:ring-cyan-400 outline-none text-[11px]">
                        <span class="text-slate-400 text-xs">—</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full min-w-0 px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg
                           focus:ring-2 focus:ring-cyan-400 outline-none text-[11px]">
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-4 pt-3 border-t border-slate-100">
                <button type="submit"
                    class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white font-bold px-4 py-2 rounded-lg text-xs">
                    Terapkan Filter
                </button>

                @if (request()->hasAny(['search', 'activity_type', 'user_role', 'start_date', 'end_date']))
                    <a href="{{ route('admin.activity_log') }}" class="text-xs text-slate-500 hover:text-rose-500">
                        Reset
                    </a>
                @endif

                <span class="ml-auto text-[10px] font-bold text-slate-400">
                    {{ $logs->total() }} log
                </span>
            </div>
        </form>
    </div>

    {{-- ── Activity Log Table ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-600 uppercase tracking-widest border-b-2 border-slate-200">
                        <th class="pb-4 pt-5 px-5">Waktu</th>
                        <th class="pb-4 pt-5 px-5">Pengguna</th>
                        <th class="pb-4 pt-5 px-5">Tipe Aktivitas</th>
                        <th class="pb-4 pt-5 px-5">Deskripsi</th>
                        <th class="pb-4 pt-5 px-5 text-center">Role</th>
                        <th class="pb-4 pt-5 px-5 text-center">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="group hover:bg-cyan-50/50 transition-colors duration-200">
                            <td class="py-3.5 px-5">
                                <span class="text-xs font-semibold text-slate-900 whitespace-nowrap">
                                    {{ $log->created_at->translatedFormat('d M Y') }}
                                </span>
                                <br>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ $log->created_at->format('H:i:s') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="text-xs font-bold text-slate-900">
                                    {{ $log->user_name ?? 'System' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span
                                    class="text-[10px] font-bold px-2.5 py-1 rounded-lg whitespace-nowrap {{ $log->activity_badge_color }}">
                                    <i class="fas {{ $log->activity_icon }} mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 max-w-xs">
                                <span class="text-xs text-slate-700 font-medium line-clamp-2">
                                    {{ $log->activity_description }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span
                                    class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 whitespace-nowrap">
                                    {{ ucfirst($log->user_role ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="text-[10px] font-mono text-slate-400">
                                    {{ $log->ip_address ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <i class="fas fa-inbox text-slate-300 text-4xl mb-3 block"></i>
                                <p class="text-slate-500 font-bold uppercase text-xs">Tidak ada aktivitas ditemukan</p>
                                @if (request()->hasAny(['search', 'activity_type', 'user_role', 'start_date', 'end_date']))
                                    <a href="{{ route('admin.activity_log') }}"
                                        class="mt-3 inline-block text-xs text-cyan-600 hover:underline font-semibold">
                                        ← Hapus filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
