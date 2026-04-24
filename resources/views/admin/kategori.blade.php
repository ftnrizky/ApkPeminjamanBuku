@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
    <!-- Success & Error Alerts -->
    @if (session('success'))
        <div
            class="mb-6 p-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-500/20 flex items-center gap-3 border border-emerald-400/30">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 p-4 bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-xl shadow-lg shadow-rose-500/20 flex items-center gap-3 border border-rose-400/30">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
            <div>
                <h1 class="text-4xl font-900 text-slate-900 mb-2">Kelola Kategori Buku</h1>
                <p class="text-slate-600 font-500">Atur kategori buku dengan desain dan icon yang menarik</p>
            </div>

            <!-- Add Button -->
            <button onclick="toggleModal('modal-tambah')"
                class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-3 rounded-xl font-700 flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/20 transition-all active:scale-95 text-sm whitespace-nowrap">
                <i class="fas fa-plus-circle"></i> Tambah Kategori
            </button>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kategoris as $kategori)
            <div
                class="bg-white rounded-2xl border border-slate-200 hover:border-cyan-400/50 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                <!-- Header dengan Warna -->
                <div
                    class="h-24 bg-gradient-to-r from-cyan-500/20 to-cyan-600/20 border-b-2 border-cyan-500/30 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-transparent"></div>
                    <i class="fas {{ $kategori->icon }} text-5xl text-cyan-600 relative z-10 opacity-90"></i>
                </div>

                <!-- Content Section -->
                <div class="p-5 space-y-3">
                    <!-- Nama Kategori -->
                    <div>
                        <h3 class="text-lg font-900 text-slate-900">{{ $kategori->nama }}</h3>
                        <p class="text-xs text-slate-400 font-600 uppercase tracking-wider mt-1">Kategori Buku</p>
                    </div>

                    <!-- Deskripsi -->
                    @if ($kategori->deskripsi)
                        <p class="text-sm text-slate-600 line-clamp-2">{{ $kategori->deskripsi }}</p>
                    @endif

                    <!-- Info Cards -->
                    <div class="grid grid-cols-2 gap-2 py-2">
                        <div class="bg-slate-50 rounded-lg p-2">
                            <p class="text-xs text-slate-400 font-600">Buku Terdaftar</p>
                            <p class="text-lg font-900 text-slate-900">{{ $kategori->alats()->count() }}</p>
                        </div>
                        <div class="bg-cyan-50 rounded-lg p-2">
                            <p class="text-xs text-cyan-600 font-600">Dibuat</p>
                            <p class="text-xs font-700 text-slate-900">
                                {{ $kategori->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 pt-2 border-t border-slate-100">
                        <button
                            onclick="openEditKategori(
                            '{{ $kategori->id }}',
                            '{{ addslashes($kategori->nama) }}',
                            '{{ $kategori->icon }}',
                            '{{ addslashes($kategori->deskripsi) }}',
                            '{{ $kategori->warna }}'
                        )"
                            class="flex-1 px-3 py-2 rounded-lg bg-cyan-50 text-cyan-600 hover:bg-cyan-100 font-700 text-xs transition-colors flex items-center justify-center gap-1.5">
                            <i class="fas fa-edit text-xs"></i> Edit
                        </button>
                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="flex-1"
                            onsubmit="return confirm('Hapus kategori ini? Pastikan tidak digunakan oleh buku manapun.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full px-3 py-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 font-700 text-xs transition-colors flex items-center justify-center gap-1.5">
                                <i class="fas fa-trash text-xs"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center">
                <div class="bg-slate-50 rounded-2xl p-8 text-center">
                    <i class="fas fa-inbox text-5xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 font-700 uppercase text-sm tracking-wide">Belum ada kategori</p>
                    <p class="text-slate-400 text-xs mt-2">Mulai dengan membuat kategori baru</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MODAL TAMBAH KATEGORI                                      --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-2">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
            onclick="toggleModal('modal-tambah')"></div>
        <div
            class="bg-white rounded-xl w-full max-w-sm p-4 relative z-10 shadow-2xl transition-all max-h-[95vh] overflow-y-auto">
            <h2 class="text-lg font-900 text-slate-900 mb-3">
                Tambah <span class="bg-gradient-to-r from-cyan-500 to-cyan-600 bg-clip-text text-transparent">Kategori
                    Baru</span>
            </h2>

            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-3">
                @csrf

                <!-- Nama Kategori -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">
                        Nama Kategori <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="nama" required placeholder="Contoh: Dongeng, belajar, Sastra"
                        value="{{ old('nama') }}"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-xs transition-all">
                    @error('nama')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon Selection -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">
                        Pilih Icon <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-1.5">
                        @php
                            $icons = [
                                'fa-book' => 'Umum',
                                'fa-book-open' => 'Novel',
                                'fa-feather' => 'Sastra',
                                'fa-user-graduate' => 'Pendidikan',
                                'fa-flask' => 'Sains',
                                'fa-laptop-code' => 'Teknologi',
                                'fa-globe' => 'Sejarah',
                                'fa-lightbulb' => 'Pengembangan Diri',
                                'fa-children' => 'Anak-anak',
                                'fa-landmark' => 'Biografi',
                            ];
                        @endphp
                        @foreach ($icons as $icon => $label)
                            <label
                                class="icon-label-tambah flex flex-col items-center gap-0.5 p-1.5 rounded-lg border-2 cursor-pointer transition-all
                        {{ old('icon') === $icon ? 'border-cyan-500 bg-cyan-50 ring-2 ring-cyan-500' : 'border-slate-200 hover:border-cyan-500 hover:bg-cyan-50' }}">
                                <input type="radio" name="icon" value="{{ $icon }}" required class="hidden"
                                    {{ old('icon') === $icon ? 'checked' : '' }} onchange="selectIconTambah(this)">
                                <i class="fas {{ $icon }} text-lg text-slate-600"></i>
                                <span class="text-xs text-slate-500 text-center line-clamp-1">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('icon')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" placeholder="Jelaskan kategori ini..."
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-xs resize-none transition-all">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Warna -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">
                        Warna Tema <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-1.5">
                        @php
                            $warna_options = [
                                'cyan' => 'bg-cyan-500',
                                'blue' => 'bg-blue-500',
                                'purple' => 'bg-purple-500',
                                'pink' => 'bg-pink-500',
                                'rose' => 'bg-rose-500',
                                'emerald' => 'bg-emerald-500',
                                'amber' => 'bg-amber-500',
                                'orange' => 'bg-orange-500',
                                'red' => 'bg-red-500',
                                'indigo' => 'bg-indigo-500',
                            ];
                        @endphp
                        @foreach ($warna_options as $key => $bg)
                            <label
                                class="warna-tambah flex flex-col items-center gap-1 p-1.5 rounded-lg border-2 cursor-pointer transition-all group
                        {{ old('warna') === $key ? 'border-cyan-500 ring-2 ring-cyan-500' : 'border-slate-200 hover:border-slate-400' }}">
                                <input type="radio" name="warna" value="{{ $key }}" required class="hidden"
                                    {{ old('warna') === $key ? 'checked' : '' }} onchange="selectWarnaTambah(this)">
                                <div
                                    class="w-5 h-5 rounded-full {{ $bg }} shadow-md group-hover:scale-110 transition-transform">
                                </div>
                                <span class="text-xs text-slate-500">{{ ucfirst($key) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('warna')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="toggleModal('modal-tambah')"
                        class="flex-1 px-4 py-2 rounded-lg font-800 text-xs uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-4 py-2 rounded-lg font-800 text-xs uppercase tracking-wider shadow-lg shadow-cyan-500/20 transition-all active:scale-95">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MODAL EDIT KATEGORI                                        --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-2">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
            onclick="toggleModal('modal-edit')"></div>
        <div
            class="bg-white rounded-xl w-full max-w-sm p-4 relative z-10 shadow-2xl transition-all max-h-[95vh] overflow-y-auto">
            <h2 class="text-lg font-900 text-slate-900 mb-3">
                Edit <span class="bg-gradient-to-r from-cyan-500 to-cyan-600 bg-clip-text text-transparent">Kategori</span>
            </h2>

            <form id="form-edit" action="#" method="POST" class="space-y-3">
                @csrf @method('PUT')

                <!-- Nama Kategori -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">
                        Nama Kategori <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="edit-nama" name="nama" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-xs transition-all">
                </div>

                <!-- Icon Selection -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">
                        Pilih Icon <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-1.5">
                        @foreach ($icons as $icon => $label)
                            <label
                                class="icon-label-edit flex flex-col items-center gap-0.5 p-1.5 rounded-lg border-2 border-slate-200 cursor-pointer hover:border-cyan-500 hover:bg-cyan-50 transition-all">
                                <input type="radio" name="icon" value="{{ $icon }}" class="hidden"
                                    onchange="selectIconEdit(this)">
                                <i class="fas {{ $icon }} text-lg text-slate-600"></i>
                                <span class="text-xs text-slate-500 text-center line-clamp-1">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">Deskripsi</label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="2"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 outline-none font-medium text-xs resize-none transition-all"></textarea>
                </div>

                <!-- Warna -->
                <div>
                    <label class="text-xs font-800 text-slate-600 uppercase tracking-wider block mb-1">
                        Warna Tema <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-1.5">
                        @foreach ($warna_options as $key => $bg)
                            <label
                                class="warna-edit flex flex-col items-center gap-1 p-1.5 rounded-lg border-2 border-slate-200 cursor-pointer hover:border-slate-400 transition-all group">
                                <input type="radio" name="warna" value="{{ $key }}" class="hidden"
                                    onchange="selectWarnaEdit(this)">
                                <div
                                    class="w-5 h-5 rounded-full {{ $bg }} shadow-md group-hover:scale-110 transition-transform">
                                </div>
                                <span class="text-xs text-slate-500">{{ ucfirst($key) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="toggleModal('modal-edit')"
                        class="flex-1 px-4 py-2 rounded-lg font-800 text-xs uppercase tracking-wider text-slate-600 hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-4 py-2 rounded-lg font-800 text-xs uppercase tracking-wider shadow-lg shadow-cyan-500/20 transition-all active:scale-95">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── Toggle modal open/close ──────────────────────────────────────────────
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (!modal) return;
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        // ── Highlight icon pada modal tambah ─────────────────────────────────────
        function selectIconTambah(input) {
            document.querySelectorAll('#modal-tambah .icon-label-tambah').forEach(l => {
                l.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-500', 'bg-cyan-50');
                l.classList.add('border-slate-200');
            });
            input.parentElement.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-500', 'bg-cyan-50');
            input.parentElement.classList.remove('border-slate-200');
        }

        // ── Highlight warna pada modal tambah ────────────────────────────────────
        function selectWarnaTambah(input) {
            document.querySelectorAll('#modal-tambah .warna-tambah').forEach(l => {
                l.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-500');
                l.classList.add('border-slate-200');
            });
            input.parentElement.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-500');
            input.parentElement.classList.remove('border-slate-200');
        }

        // ── Highlight icon pada modal edit ───────────────────────────────────────
        function selectIconEdit(input) {
            document.querySelectorAll('#modal-edit .icon-label-edit').forEach(l => {
                l.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-500', 'bg-cyan-50');
                l.classList.add('border-slate-200');
            });
            input.parentElement.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-500', 'bg-cyan-50');
            input.parentElement.classList.remove('border-slate-200');
        }

        // ── Highlight warna pada modal edit ──────────────────────────────────────
        function selectWarnaEdit(input) {
            document.querySelectorAll('#modal-edit .warna-edit').forEach(l => {
                l.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-500');
                l.classList.add('border-slate-200');
            });
            input.parentElement.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-500');
            input.parentElement.classList.remove('border-slate-200');
        }

        // ── Isi form edit lalu buka modal ─────────────────────────────────────────
        function openEditKategori(id, nama, icon, deskripsi, warna) {
            document.getElementById('form-edit').action = `/admin/kategori/${id}`;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-deskripsi').value = deskripsi;

            // Set icon radio + highlight
            document.querySelectorAll('#modal-edit input[name="icon"]').forEach(input => {
                const label = input.parentElement;
                label.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-500', 'bg-cyan-50');
                label.classList.add('border-slate-200');
                if (input.value === icon) {
                    input.checked = true;
                    label.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-500', 'bg-cyan-50');
                    label.classList.remove('border-slate-200');
                }
            });

            // Set warna radio + highlight
            document.querySelectorAll('#modal-edit .warna-edit').forEach(label => {
                label.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-500');
                label.classList.add('border-slate-200');
                const radio = label.querySelector('input[type="radio"]');
                if (radio && radio.value === warna) {
                    radio.checked = true;
                    label.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-500');
                    label.classList.remove('border-slate-200');
                }
            });

            toggleModal('modal-edit');
        }

        // ── Tutup semua modal dengan Escape ──────────────────────────────────────
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="modal-"]').forEach(m => {
                    m.classList.add('hidden');
                    m.classList.remove('flex');
                });
            }
        });

        // ── SweetAlert notifikasi ─────────────────────────────────────────────────
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#10b981',
                color: '#ffffff'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: '{{ session('error') }}',
                timer: 4000,
                showConfirmButton: true,
                toast: true,
                position: 'top-end',
                background: '#ef4444',
                color: '#ffffff'
            });
        @endif
    </script>
@endsection
