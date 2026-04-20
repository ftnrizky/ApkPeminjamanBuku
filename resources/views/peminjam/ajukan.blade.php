@extends('layouts.peminjam')

@section('title', 'Form Peminjaman')

@section('content')
    <a href="{{ route('peminjam.katalog') }}" class="inline-flex items-center gap-2 text-xs font-bold text-cyan-600 uppercase tracking-widest mb-8 hover:gap-3 hover:text-cyan-700 transition-all duration-200 group">
        <i class="fas fa-arrow-left group-hover:scale-110 transition-transform duration-200"></i> Kembali ke Katalog
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- CARD INFO LAPTOP --}}
        <div class="lg:col-span-1">
            <div class="group bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-28 transition-all hover:shadow-xl hover:border-cyan-200">
                <div class="relative bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl aspect-square mb-6 flex items-center justify-center overflow-hidden shadow-inner border border-slate-200">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <i class="fas fa-laptop text-slate-300 text-6xl group-hover:scale-110 group-hover:text-cyan-500/20 transition-all duration-500"></i>
                    @endif
                </div>
                <p class="text-[10px] font-bold text-cyan-600 uppercase tracking-widest mb-1 text-center">{{ $alat->kategori }}</p>
                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight text-center leading-tight">{{ $alat->nama_alat }}</h3>
                
                <div class="mt-6 space-y-3 border-t border-slate-100 pt-6">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest">
                        <span class="text-slate-500">Harga Sewa</span>
                        <span class="text-cyan-600 font-black">Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}<span class="text-[9px] text-slate-400 normal-case ml-1">/ hari</span></span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest">
                        <span class="text-slate-500">Tersedia</span>
                        <span class="text-slate-900 font-semibold">{{ $alat->stok_tersedia }} Unit</span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest">
                        <span class="text-slate-500">Kondisi</span>
                        <span class="{{ $alat->kondisi == 'baik' ? 'text-teal-600 font-black' : 'text-amber-600 font-black' }} capitalize">{{ ucfirst($alat->kondisi) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM INPUT --}}
        <div class="lg:col-span-2">
            {{-- Alert Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-md flex items-center gap-3 animate-pulse">
                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                    <span class="text-[10px] font-bold uppercase tracking-widest">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-5 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl">
                    <ul class="space-y-1.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-rose-700 text-[10px] font-bold uppercase tracking-tight flex items-center gap-2">
                                <i class="fas fa-times-circle text-rose-600"></i> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-lg relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-cyan-500/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-teal-500/5 rounded-full blur-3xl"></div>
                
                <h2 class="text-2xl font-bold text-slate-900 uppercase tracking-tight mb-8 flex items-center gap-3">
                    <i class="fas fa-laptop text-cyan-600"></i> Formulir <span class="text-cyan-600">Peminjaman</span>
                </h2>

                <form action="{{ route('peminjam.store') }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    <input type="hidden" name="id_alat" value="{{ $alat->id }}">

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Jumlah Pinjam (Unit) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-cubes absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $alat->stok_tersedia }}" placeholder="Maksimal: {{ $alat->stok_tersedia }}" required
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none transition-all font-medium text-sm hover:border-slate-300">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400">Max: {{ $alat->stok_tersedia }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Tgl Pinjam <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="date" id="tgl_pinjam" name="tgl_pinjam" 
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly
                                       class="w-full pl-12 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl outline-none font-medium text-sm cursor-not-allowed opacity-70">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Tgl Kembali <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-calendar-times absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="date" id="tgl_kembali" name="tgl_kembali" 
                                       min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required 
                                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none transition-all font-medium text-sm hover:border-slate-300">
                            </div>
                        </div>
                    </div>

                    {{-- ESTIMASI BIAYA --}}
                    <div class="bg-gradient-to-br from-cyan-50 to-teal-50 p-5 rounded-xl border border-cyan-200 shadow-sm">
                        <p class="text-[10px] font-bold text-cyan-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-calculator text-cyan-600"></i> Estimasi Biaya Peminjaman
                        </p>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between pb-2.5 border-b border-cyan-200">
                                <span class="text-slate-600 font-medium">Harga Per Hari:</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pb-2.5 border-b border-cyan-200" id="durasiRow">
                                <span class="text-slate-600 font-medium">Durasi Pinjam:</span>
                                <span class="font-bold text-cyan-700" id="durasiText">- hari</span>
                            </div>
                            <div class="flex justify-between pb-2.5 border-b border-cyan-200" id="jumlahRow">
                                <span class="text-slate-600 font-medium">Jumlah Unit:</span>
                                <span class="font-bold text-slate-900" id="jumlahText">- unit</span>
                            </div>
                            <div class="flex justify-between pt-2 bg-white p-3 rounded-lg" id="estimasiRow">
                                <span class="text-slate-700 font-bold">TOTAL BIAYA:</span>
                                <span class="font-black text-cyan-700 text-lg" id="estimasiTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Tujuan Peminjaman <span class="text-rose-500">*</span></label>
                        <textarea name="tujuan" rows="3" placeholder="Contoh: Keperluan pekerjaan, pengembangan skill, project tertentu..." required
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none transition-all font-medium text-sm resize-none hover:border-slate-300"></textarea>
                    </div>

                    {{-- KETENTUAN BOX --}}
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-5 rounded-xl border border-amber-200 flex gap-4 shadow-sm">
                        <div class="bg-amber-500/10 p-3 rounded-lg h-fit flex-shrink-0">
                            <i class="fas fa-alert-circle text-amber-600 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] text-amber-900 font-bold uppercase tracking-widest mb-3">Kebijakan & Denda Sistem:</p>
                            <ul class="space-y-2 text-[10px] text-amber-800 font-medium">
                                <li class="flex items-start gap-2">
                                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <span><strong class="text-amber-900">Durasi Maksimal:</strong> 3 Hari</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <span><strong class="text-rose-600 font-bold">Terlambat:</strong> Rp 5.000 per hari</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <span><strong class="text-amber-900">Kondisi Rusak/Hilang:</strong> Denda sesuai nilai kerusakan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <span><strong class="text-amber-900">Lecet Ringan:</strong> Rp 15.000 per unit</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95 text-xs tracking-wider uppercase flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> KIRIM PERMINTAAN PINJAM
                        </button>
                    </div>
                </form>
            </div>
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
            if (tglPinjamInput.value && tglKembaliInput.value && jumlahInput.value) {
                const tglPinjam = new Date(tglPinjamInput.value);
                const tglKembali = new Date(tglKembaliInput.value);
                
                // Hitung durasi dalam hari
                const diffTime = Math.abs(tglKembali - tglPinjam);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                const jumlah = parseInt(jumlahInput.value) || 0;
                const total = diffDays * hargaSewa * jumlah;
                
                durasiText.innerText = diffDays + ' hari';
                jumlahText.innerText = jumlah + ' unit';
                estimasiTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                durasiText.innerText = '- hari';
                jumlahText.innerText = '- unit';
                estimasiTotal.innerText = 'Rp 0';
            }
        }
        
        function updateConstraints() {
            if (tglPinjamInput.value) {
                let startDate = new Date(tglPinjamInput.value);
                let maxDate = new Date(startDate);
                maxDate.setDate(startDate.getDate() + 3);
                
                tglKembaliInput.min = tglPinjamInput.value;
                tglKembaliInput.max = maxDate.toISOString().split('T')[0];
                
                // Jika tanggal kembali melebihi max, set ke max
                if (tglKembaliInput.value && new Date(tglKembaliInput.value) > maxDate) {
                    tglKembaliInput.value = maxDate.toISOString().split('T')[0];
                }
                
                hitungEstimasi();
            }
        }
        
        // Event listeners
        tglKembaliInput.addEventListener('change', hitungEstimasi);
        jumlahInput.addEventListener('input', hitungEstimasi);
        
        updateConstraints();
    });
</script>
@endsection