<!-- BUTTON -->
<div class="relative inline-block text-left z-50">
    <button id="notifButton" type="button"
        class="relative inline-flex items-center justify-center w-12 h-12 rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:shadow-md hover:bg-slate-50 transition-all duration-200">
        
        <i class="fas fa-bell text-lg"></i>

        @if(!empty($unreadCount) && $unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center h-5 min-w-[1.25rem] rounded-full bg-rose-500 text-white text-[10px] font-bold px-1.5 shadow-md">
                {{ $unreadCount }}
            </span>
        @endif
    </button>
</div>


<!-- POPUP (WAJIB DI LUAR CONTAINER / TARUH DI AKHIR BODY) -->
<div id="notificationMenu"
    class="hidden fixed w-full md:w-96 max-w-[95vw] rounded-2xl border border-slate-200 bg-white shadow-2xl ring-1 ring-slate-900/5 overflow-hidden z-[99999]">

    <!-- HEADER -->
    <div class="px-4 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-slate-900">Notifikasi Anda</p>
            <p class="text-[11px] text-slate-500">Pesan terbaru & penting</p>
        </div>

        @if(!empty($unreadCount) && $unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read_all') }}">
                @csrf
                <button type="submit" class="text-xs font-semibold text-cyan-600 hover:underline">
                    Baca Semua
                </button>
            </form>
        @endif
    </div>

    <!-- LIST -->
    <div class="max-h-80 overflow-y-auto">
        @forelse($notifications ?? [] as $notification)
            <div class="px-4 py-3 border-b hover:bg-slate-50 transition">
                <div class="flex gap-3">

                    <div class="w-9 h-9 rounded-xl bg-cyan-100 flex items-center justify-center">
                        <i class="{{ $notification->icon ?? 'fas fa-bell' }}"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">
                            {{ $notification->title }}
                        </p>

                        <p class="text-xs text-slate-600 line-clamp-2">
                            {{ $notification->message }}
                        </p>

                        <p class="text-[10px] text-slate-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @unless($notification->is_read)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button class="text-[10px] underline text-slate-500 hover:text-slate-700">
                                Tandai
                            </button>
                        </form>
                    @endunless

                </div>
            </div>
        @empty
            <div class="p-6 text-center text-slate-500">
                <i class="fas fa-inbox text-3xl text-slate-300 mb-2 block"></i>
                Belum ada notifikasi
            </div>
        @endforelse
    </div>
</div>

<style>
#notificationMenu {
    transition: all 0.2s ease;
}

#notificationMenu.hidden {
    display: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menu = document.getElementById('notificationMenu');
    const button = document.getElementById('notifButton');

    if (!menu || !button) return;

    function openMenu() {
        const rect = button.getBoundingClientRect();

        let top = rect.bottom + 10;
        let left = rect.right - 380;

        // mobile fix
        if (window.innerWidth < 768) {
            left = 10;
        }

        menu.style.top = top + 'px';
        menu.style.left = left + 'px';

        menu.style.opacity = 0;
        menu.style.transform = "translateY(-10px)";

        menu.classList.remove('hidden');

        setTimeout(() => {
            menu.style.opacity = 1;
            menu.style.transform = "translateY(0)";
        }, 10);
    }

    function closeMenu() {
        menu.classList.add('hidden');
    }

    // toggle click
    button.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.contains('hidden') ? openMenu() : closeMenu();
    });

    // klik luar
    document.addEventListener('click', function (e) {
        if (!menu.contains(e.target) && !button.contains(e.target)) {
            closeMenu();
        }
    });

    // ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });

    // 🔥 anti bug reload / pindah halaman
    window.addEventListener('pageshow', closeMenu);
    window.addEventListener('load', closeMenu);
});
</script>
