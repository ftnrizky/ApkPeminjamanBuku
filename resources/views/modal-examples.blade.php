<!-- ====================================
     MODAL SCROLL CONTROL - IMPLEMENTASI LENGKAP
     ==================================== -->

@extends('layouts.admin')

@section('title', 'Modal Examples')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-scroll-control.css') }}">
@endpush

@section('content')
<!-- ===== HEADER ===== -->
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">Modal Scroll Control Examples</h1>
    <p class="text-gray-600">Contoh implementasi lengkap untuk mengendalikan scroll saat modal terbuka</p>
</div>

<!-- ===== BUTTONS DEMO ===== -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <button onclick="modalScroll.openModal('modal-example-1')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all active:scale-95">
        <i class="fas fa-window-restore mr-2"></i> Modal 1
    </button>
    <button onclick="modalScroll.openModal('modal-example-2')" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-lg font-bold transition-all active:scale-95">
        <i class="fas fa-window-maximize mr-2"></i> Modal 2
    </button>
    <button onclick="modalScroll.openModal('modal-example-3')" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-bold transition-all active:scale-95">
        <i class="fas fa-form mr-2"></i> Modal 3 (Form)
    </button>
    <button onclick="modalScroll.openModal('modal-example-4')" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold transition-all active:scale-95">
        <i class="fas fa-list mr-2"></i> Modal 4 (Long)
    </button>
</div>

<!-- ===== SCROLL TEST CONTENT ===== -->
<div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Scroll Test Content</h2>
    <p class="text-gray-600 mb-4">
        Cobalah scroll halaman ini sebelum membuka modal. Ketika modal terbuka, scroll akan otomatis dinonaktifkan di halaman ini. 
        Anda hanya bisa scroll di dalam modal jika modal memiliki konten yang panjang.
    </p>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @for($i = 1; $i <= 12; $i++)
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
            <h3 class="font-bold text-blue-900 mb-2">Card {{ $i }}</h3>
            <p class="text-sm text-blue-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <div class="bg-blue-200 h-40 rounded flex items-center justify-center text-blue-800 font-bold">
                Placeholder {{ $i }}
            </div>
        </div>
        @endfor
    </div>
</div>

<!-- ====================================
     MODAL 1 - SIMPLE MODAL
     ==================================== -->
<div id="modal-example-1" class="modal">
    <!-- Backdrop -->
    <div class="modal-backdrop"></div>
    
    <!-- Content -->
    <div class="modal-content">
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
            <h2 class="text-2xl font-bold text-gray-900">Modal Sederhana</h2>
            <button onclick="modalScroll.closeModal('modal-example-1')" class="text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            <p class="text-gray-600">
                Ini adalah contoh modal sederhana dengan scroll control. Coba scroll di halaman utama - scroll akan terkunci!
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">
                    ✅ Body scroll otomatis dinonaktifkan saat modal terbuka
                </p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700">
                    ✅ Anda bisa scroll di dalam modal jika konten panjang
                </p>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <p class="text-sm text-purple-700">
                    ✅ Tekan ESC untuk menutup modal (atau klik backdrop)
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6 flex gap-3">
            <button onclick="modalScroll.closeModal('modal-example-1')" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-bold transition-all">
                Batal
            </button>
            <button onclick="modalScroll.closeModal('modal-example-1')" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ====================================
     MODAL 2 - MODAL DENGAN LONG CONTENT
     ==================================== -->
<div id="modal-example-2" class="modal">
    <div class="modal-backdrop"></div>
    
    <div class="modal-content">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
            <h2 class="text-2xl font-bold text-gray-900">Modal dengan Konten Panjang</h2>
            <button onclick="modalScroll.closeModal('modal-example-2')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <p class="text-gray-600 font-semibold">Scroll di dalam modal ini untuk melihat bagaimana konten panjang ditangani:</p>
            
            @for($i = 1; $i <= 15; $i++)
            <div class="bg-gradient-to-r from-cyan-50 to-blue-50 p-4 rounded-lg border border-cyan-200">
                <h3 class="font-bold text-cyan-900 mb-2">Section {{ $i }}</h3>
                <p class="text-sm text-cyan-700">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>
            @endfor
        </div>

        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6">
            <button onclick="modalScroll.closeModal('modal-example-2')" class="w-full px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-bold transition-all">
                Tutup Modal
            </button>
        </div>
    </div>
</div>

<!-- ====================================
     MODAL 3 - FORM MODAL
     ==================================== -->
<div id="modal-example-3" class="modal">
    <div class="modal-backdrop"></div>
    
    <div class="modal-content">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
            <h2 class="text-2xl font-bold text-gray-900">Form Modal</h2>
            <button onclick="modalScroll.closeModal('modal-example-3')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form class="p-6 space-y-4" onsubmit="event.preventDefault(); alert('Form submitted!'); modalScroll.closeModal('modal-example-3');">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Masukkan nama">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Masukkan email">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pesan</label>
                <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="4" placeholder="Masukkan pesan"></textarea>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">
                    ✅ Form input berfungsi normal di dalam modal
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="modalScroll.closeModal('modal-example-3')" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition-all">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ====================================
     MODAL 4 - NESTED/MULTIPLE MODALS TEST
     ==================================== -->
<div id="modal-example-4" class="modal">
    <div class="modal-backdrop"></div>
    
    <div class="modal-content">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
            <h2 class="text-2xl font-bold text-gray-900">Test Multiple Modals</h2>
            <button onclick="modalScroll.closeModal('modal-example-4')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <p class="text-gray-600">
                Coba buka modal lain dari sini untuk test nested modals.
            </p>

            <div class="grid grid-cols-2 gap-3">
                <button onclick="modalScroll.openModal('modal-nested-1')" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-bold transition-all">
                    <i class="fas fa-plus mr-1"></i> Modal Nested 1
                </button>
                <button onclick="modalScroll.openModal('modal-nested-2')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold transition-all">
                    <i class="fas fa-plus mr-1"></i> Modal Nested 2
                </button>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                <p class="text-sm text-green-700">
                    ✅ Scroll tetap terkunci meskipun membuka multiple modals
                </p>
            </div>
        </div>

        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6">
            <button onclick="modalScroll.closeModal('modal-example-4')" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ===== NESTED MODAL 1 ===== -->
<div id="modal-nested-1" class="modal">
    <div class="modal-backdrop"></div>
    <div class="modal-content" style="max-width: 400px;">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900">Nested Modal 1</h2>
            <button onclick="modalScroll.closeModal('modal-nested-1')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">Ini adalah nested modal 1. Scroll tetap terkunci!</p>
            <button onclick="modalScroll.closeModal('modal-nested-1')" class="w-full px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-bold">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ===== NESTED MODAL 2 ===== -->
<div id="modal-nested-2" class="modal">
    <div class="modal-backdrop"></div>
    <div class="modal-content" style="max-width: 400px;">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900">Nested Modal 2</h2>
            <button onclick="modalScroll.closeModal('modal-nested-2')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">Ini adalah nested modal 2. Multiple modals berfungsi dengan sempurna!</p>
            <button onclick="modalScroll.closeModal('modal-nested-2')" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/modal-scroll-control.js') }}"></script>
@endpush
