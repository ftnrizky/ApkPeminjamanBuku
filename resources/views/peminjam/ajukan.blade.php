@extends('layouts.peminjam')

@section('title', 'Form Peminjaman')

@section('content')
    <div class="space-y-6">
        <a href="{{ route('peminjam.katalog') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition duration-200 hover:text-blue-600">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke Katalog
        </a>

        <div class="grid gap-6 lg:grid-cols-[320px,1fr]">
            <aside class="rounded-3xl border border-slate-200 bg-white shadow-sm lg:sticky lg:top-24">
                <div class="aspect-[4/3] overflow-hidden rounded-t-3xl bg-slate-100">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}"
                            class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-slate-400">
                            <i class="fas fa-book text-4xl"></i>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $alat->kategori->nama ?? '-' }}</p>
                    <h1 class="mt-2 text-xl font-semibold text-slate-900">{{ $alat->nama_alat }}</h1>

                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-500">Harga sewa</span>
                            <span class="text-sm font-semibold text-slate-900">Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}/hari</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-500">Stok tersedia</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $alat->stok_tersedia }} unit</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-500">Kondisi</span>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $alat->kondisi === 'baik' ? 'bg-emerald-100 text-emerald-700' : ($alat->kondisi === 'lecet' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ ucfirst($alat->kondisi) }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @if(session('error'))
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4">
                        <p class="text-sm font-semibold text-rose-700">Ada beberapa input yang perlu diperbaiki:</p>
                        <ul class="mt-3 space-y-2 text-sm text-rose-600">
                            @foreach($errors->all() as $error)
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-circle text-[8px] mt-1.5"></i>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="border-b border-slate-200 pb-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Formulir</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-900">Ajukan Peminjaman</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Isi detail peminjaman dengan lengkap. Estimasi biaya akan dihitung otomatis sesuai durasi dan jumlah unit.
                    </p>
                </div>

                <form action="{{ route('peminjam.store') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_alat" value="{{ $alat->id }}">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Unit <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-cubes absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $alat->stok_tersedia }}"
                                placeholder="Masukkan jumlah unit" required
                                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-28 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">
                                Maks: {{ $alat->stok_tersedia }}
                            </span>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="date" id="tgl_pinjam" name="tgl_pinjam"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Kembali <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-calendar-xmark absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="date" id="tgl_kembali" name="tgl_kembali"
                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <i class="fas fa-calculator text-blue-500"></i>
                            Estimasi Denda apabila terlambat 
                        </div>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3">
                                <span class="text-sm text-slate-500">Harga per hari</span>
                                <span class="text-sm font-semibold text-slate-900">Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3">
                                <span class="text-sm text-slate-500">Durasi</span>
                                <span id="durasiText" class="text-sm font-semibold text-slate-900">- hari</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3">
                                <span class="text-sm text-slate-500">Jumlah unit</span>
                                <span id="jumlahText" class="text-sm font-semibold text-slate-900">- unit</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-blue-50 px-4 py-3 ring-1 ring-blue-100">
                                <span class="text-sm font-semibold text-blue-700">Total Biaya</span>
                                <span id="estimasiTotal" class="text-lg font-bold text-blue-700">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tujuan Peminjaman <span class="text-rose-500">*</span></label>
                        <textarea name="tujuan" rows="4"
                            placeholder="Contoh: Digunakan untuk keperluan belajar mandiri"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></textarea>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-amber-600 ring-1 ring-amber-200">
                                <i class="fas fa-circle-info"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-amber-800">Kebijakan & Denda</h3>
                                <ul class="mt-3 space-y-2 text-sm leading-6 text-amber-700">
                                    <li><strong>Durasi maksimal:</strong> 3 hari kerja</li>
                                    <li><strong>Terlambat:</strong> Rp 5.000 per hari</li>
                                    <li><strong>Rusak / hilang:</strong> Denda sesuai nilai kerusakan</li>
                                    <li><strong>Lecet ringan:</strong> Rp 15.000 per unit</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-500 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-400 hover:shadow-md">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Kirim Permintaan Pinjam
                    </button>
                </form>
            </section>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tglPinjamInput = document.getElementById('tgl_pinjam');
            const tglKembaliInput = document.getElementById('tgl_kembali');
            const jumlahInput = document.getElementById('jumlah');
            const hargaSewa = {{ $alat->harga_sewa }};

            const durasiText = document.getElementById('durasiText');
            const jumlahText = document.getElementById('jumlahText');
            const estimasiTotal = document.getElementById('estimasiTotal');

            function hitungEstimasi() {
                if (jumlahInput.value && parseInt(jumlahInput.value) <= 0) {
                    alert("Jumlah unit tidak boleh kurang dari 1");
                    jumlahInput.value = 1;
                }

                if (!tglPinjamInput.value || !tglKembaliInput.value) {
                    durasiText.innerText = '- hari';
                    jumlahText.innerText = '- unit';
                    estimasiTotal.innerText = 'Rp 0';
                    return;
                }

                const tglPinjam = new Date(tglPinjamInput.value);
                const tglKembali = new Date(tglKembaliInput.value);

                if (tglKembali < tglPinjam) {
                    alert("Tanggal kembali tidak boleh lebih kecil dari tanggal pinjam");
                    tglKembaliInput.value = "";
                    return;
                }

                const diffTime = Math.abs(tglKembali - tglPinjam);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                const jumlah = parseInt(jumlahInput.value) || 0;

                if (jumlah > 100) {
                    alert("Jumlah unit tidak boleh lebih dari 100");
                    jumlahInput.value = 100;
                    return;
                }

                const total = diffDays * hargaSewa * jumlah;

                durasiText.innerText = diffDays + ' hari';
                jumlahText.innerText = jumlah + ' unit';
                estimasiTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
            }

            function updateConstraints() {
                if (tglPinjamInput.value) {
                    let startDate = new Date(tglPinjamInput.value);
                    let maxDate = new Date(startDate);
                    maxDate.setDate(startDate.getDate() + 3);

                    tglKembaliInput.min = tglPinjamInput.value;
                    tglKembaliInput.max = maxDate.toISOString().split('T')[0];

                    if (tglKembaliInput.value && new Date(tglKembaliInput.value) > maxDate) {
                        alert("Maksimal peminjaman hanya 3 hari");
                        tglKembaliInput.value = maxDate.toISOString().split('T')[0];
                    }

                    hitungEstimasi();
                }
            }

            tglKembaliInput.addEventListener('change', hitungEstimasi);
            jumlahInput.addEventListener('input', hitungEstimasi);
            tglPinjamInput.addEventListener('change', updateConstraints);

            updateConstraints();
        });
    </script>
@endsection
