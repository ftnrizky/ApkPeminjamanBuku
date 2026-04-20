@extends('layouts.petugas')

@section('title', 'Menyetujui Kembali')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl shadow-lg shadow-teal-500/20 flex items-center gap-3 animate-pulse border border-teal-400">
    <i class="fas fa-check-circle text-xl"></i>
    <span class="font-bold text-sm uppercase tracking-wider">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl shadow-lg shadow-rose-500/20 flex items-center gap-3 border border-rose-400">
    <i class="fas fa-exclamation-circle text-xl"></i>
    <span class="font-bold text-sm uppercase tracking-wider">{{ session('error') }}</span>
</div>
@endif

<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Verifikasi <span class="text-cyan-600">Pengembalian</span></h1>
        <p class="text-slate-500 font-medium text-sm tracking-wider mt-1">Periksa kondisi fisik laptop & hitung denda otomatis</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 px-6 py-3 rounded-xl border border-cyan-200 shadow-sm flex items-center gap-3 hover:shadow-md transition-shadow duration-300">
            <div class="w-2.5 h-2.5 bg-cyan-500 rounded-full animate-pulse"></div>
            <span class="text-xs font-bold text-cyan-700 uppercase tracking-wider">{{ $pengembalians->count() }} Laptop Menunggu Verifikasi</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-600 uppercase tracking-widest border-b-2 border-slate-200">
                    <th class="pb-4 px-4 w-32">Kode Pinjam</th>
                    <th class="pb-4 px-4">Laptop</th>
                    <th class="pb-4 px-4">Peminjam</th> 
                    <th class="pb-4 px-4 text-center">Qty</th>
                    <th class="pb-4 px-4 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengembalians as $data)
                    @php
                        // Hitung denda terlambat (bulat, tanpa pecahan)
                        $deadline = \Carbon\Carbon::parse($data->tgl_kembali)->format('Y-m-d');
                        $sekarang = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d');
                        $estimasiTerlambat = 0;
                        if ($sekarang > $deadline) {
                            $deadlineObj = \Carbon\Carbon::parse($deadline);
                            $sekarangObj = \Carbon\Carbon::parse($sekarang);
                            $selisihHari = $deadlineObj->diffInDays($sekarangObj);
                            $estimasiTerlambat = $selisihHari * 5000;
                        }
                    @endphp
                    <tr class="group hover:bg-cyan-50/50 transition-colors duration-200 text-sm">
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900 text-xs bg-slate-100 px-3 py-2 rounded-lg">
                                PJM-{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-900 text-sm">{{ $data->alat->nama_alat }}</p>
                            <span class="text-[10px] text-slate-500 font-medium">Rp {{ number_format($data->alat->harga_sewa, 0, ',', '.') }}/hari</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900">{{ $data->user->name }}</span><br>
                            <span class="text-[9px] text-slate-500 font-medium">Batas: {{ \Carbon\Carbon::parse($data->tgl_kembali)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="text-lg font-bold text-slate-900">{{ $data->jumlah }}</span>
                            <span class="text-[10px] text-slate-500 block font-medium">Unit</span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button type="button" 
                                    onclick="openKondisiModal({{ $data->id }}, '{{ $data->alat->nama_alat }}', '{{ $data->user->name }}', {{ $data->jumlah }}, {{ $data->alat->harga_sewa ?? 0 }}, {{ $estimasiTerlambat }})" 
                                    class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white text-[10px] font-bold px-4 py-2 rounded-lg transition-all hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                <i class="fas fa-clipboard-check mr-1"></i> Periksa
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center px-4">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mb-6 border-2 border-teal-100">
                                    <i class="fas fa-check-double text-teal-500 text-3xl"></i>
                                </div>
                                <p class="text-slate-500 font-bold uppercase tracking-wider text-xs">Semua laptop sudah diverifikasi</p>
                                <p class="text-slate-400 text-[11px] mt-1 font-medium">Tidak ada antrian pengembalian yang menunggu</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL VERIFIKASI KONDISI -->
<div id="modal-kondisi" class="fixed inset-0 z-[100] hidden items-center justify-center p-3 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
        
        {{-- Header Modal --}}
        <div class="bg-gradient-to-r from-cyan-500 to-teal-600 rounded-t-xl p-4 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/20 rounded-md flex items-center justify-center">
                        <i class="fas fa-laptop text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base">Verifikasi Kondisi Laptop</h3>
                        <p class="text-cyan-100 text-[10px] font-medium">Periksa setiap unit dengan teliti</p>
                    </div>
                </div>
                <button onclick="closeKondisiModal()" class="text-white hover:text-cyan-100 text-xl leading-none">&times;</button>
            </div>
        </div>
        
        <form id="formKondisi" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <div class="p-4 space-y-3">
                
                {{-- Info Section --}}
                <div class="bg-slate-50 p-3 rounded-md border border-slate-200 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-bold text-slate-500 uppercase w-16">Kode:</span>
                        <span id="modal-kode" class="text-xs font-bold text-slate-900 bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded-md"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-bold text-slate-500 uppercase w-16">Peminjam:</span>
                        <span id="modal-peminjam" class="text-xs font-semibold text-slate-900"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-bold text-slate-500 uppercase w-16">Laptop:</span>
                        <span id="modal-alat" class="text-xs font-semibold text-slate-900"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-bold text-slate-500 uppercase w-16">Qty:</span>
                        <span id="modal-jumlah" class="text-xs font-bold text-cyan-700 bg-cyan-100 px-2 py-0.5 rounded-md"></span>
                    </div>
                </div>
                
                {{-- Daftar Unit --}}
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Kondisi Unit</p>
                    <div id="unit-list" class="space-y-1 max-h-60 overflow-y-auto bg-slate-50 p-2 rounded-md border border-slate-200">
                        <!-- JS -->
                    </div>
                </div>
                
                {{-- Ringkasan Denda --}}
                <div class="bg-gradient-to-r from-cyan-50 to-teal-50 p-3 rounded-md border border-cyan-200 space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-600 font-medium">Denda Terlambat:</span>
                        <span id="denda-terlambat" class="font-bold text-rose-600">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-600 font-medium">Denda Kondisi:</span>
                        <span id="denda-kondisi" class="font-bold text-rose-600">Rp 0</span>
                    </div>
                    <div class="border-t border-cyan-200 pt-1.5 flex justify-between">
                        <span class="font-bold text-slate-900 text-xs">TOTAL DENDA:</span>
                        <span id="total-denda" class="text-base font-black text-rose-700">Rp 0</span>
                    </div>
                </div>
            </div>
            
            <div class="px-4 pb-4 flex gap-2">
                <button type="button" onclick="closeKondisiModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-bold py-2 rounded-md text-xs">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-600 hover:to-teal-700 text-white font-bold py-2 rounded-md text-xs uppercase tracking-wider">
                    <i class="fas fa-check-circle mr-1"></i> Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentId = null;
    let kondisiUnit = [];
    let customBiaya = [];
    let currentHargaAsli = 0;
    let dendaTerlambatValue = 0;
    
    function openKondisiModal(id, namaAlat, peminjam, jumlah, hargaAsli, dendaTerlambat) {
        currentId = id;
        currentHargaAsli = hargaAsli;
        dendaTerlambatValue = dendaTerlambat;
        
        document.getElementById('modal-kode').innerText = 'PJM-' + String(id).padStart(4, '0');
        document.getElementById('modal-peminjam').innerText = peminjam;
        document.getElementById('modal-alat').innerText = namaAlat;
        document.getElementById('modal-jumlah').innerText = jumlah + ' Unit';
        document.getElementById('denda-terlambat').innerHTML = 'Rp ' + dendaTerlambatValue.toLocaleString('id-ID');
        
        document.getElementById('formKondisi').action = '/petugas/kembali/proses/' + id;
        
        // Reset kondisi
        kondisiUnit = [];
        customBiaya = [];
        for (let i = 0; i < jumlah; i++) {
            kondisiUnit.push('baik');
            customBiaya.push(0);
        }
        
        generateUnitList(jumlah);
        updateSummary();
        
        document.getElementById('modal-kondisi').classList.remove('hidden');
        document.getElementById('modal-kondisi').classList.add('flex');
    }
    
    function generateUnitList(jumlah) {
        const container = document.getElementById('unit-list');
        container.innerHTML = '';
        
        for (let i = 0; i < jumlah; i++) {
            const div = document.createElement('div');
            div.className = 'bg-white p-3 rounded-lg border border-slate-200 hover:border-cyan-300 transition-colors';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-sm text-slate-900">Unit ${i+1}</span>
                    <span class="text-[10px] font-semibold text-slate-500">Pilih kondisi</span>
                </div>
                <div class="flex gap-1 flex-wrap">
                    <label class="cursor-pointer group">
                        <input type="radio" name="kondisi_${i}" value="baik" class="hidden peer" checked onchange="updateKondisiUnit(${i}, 'baik')">
                        <span class="text-[10px] px-2 py-1.5 rounded-md bg-slate-100 text-slate-700 peer-checked:bg-teal-500 peer-checked:text-white peer-checked:shadow-md font-semibold transition-all cursor-pointer">
                            <i class="fas fa-check-circle mr-1"></i> Baik
                        </span>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="kondisi_${i}" value="lecet" class="hidden peer" onchange="updateKondisiUnit(${i}, 'lecet')">
                        <span class="text-[10px] px-2 py-1.5 rounded-md bg-slate-100 text-slate-700 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-md font-semibold transition-all cursor-pointer">
                            <i class="fas fa-exclamation-circle mr-1"></i> Lecet
                        </span>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="kondisi_${i}" value="rusak" class="hidden peer" onchange="updateKondisiUnit(${i}, 'rusak')">
                        <span class="text-[10px] px-2 py-1.5 rounded-md bg-slate-100 text-slate-700 peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow-md font-semibold transition-all cursor-pointer">
                            <i class="fas fa-times-circle mr-1"></i> Rusak
                        </span>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="kondisi_${i}" value="hilang" class="hidden peer" onchange="updateKondisiUnit(${i}, 'hilang')">
                        <span class="text-[10px] px-2 py-1.5 rounded-md bg-slate-100 text-slate-700 peer-checked:bg-slate-900 peer-checked:text-white peer-checked:shadow-md font-semibold transition-all cursor-pointer">
                            <i class="fas fa-ban mr-1"></i> Hilang
                        </span>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="kondisi_${i}" value="lainnya" class="hidden peer" onchange="updateKondisiUnit(${i}, 'lainnya')">
                        <span class="text-[10px] px-2 py-1.5 rounded-md bg-slate-100 text-slate-700 peer-checked:bg-slate-500 peer-checked:text-white peer-checked:shadow-md font-semibold transition-all cursor-pointer">
                            <i class="fas fa-question-circle mr-1"></i> Lainnya
                        </span>
                    </label>
                </div>
                <div id="custom-block-${i}" class="hidden mt-4">
                    <label for="custom-beban-${i}" class="text-[10px] font-semibold text-slate-600">Biaya Kerusakan Khusus (Rp)</label>
                    <input type="number" min="0" step="1000" name="custom_biaya[]" id="custom-beban-${i}" value="0" placeholder="500000" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400" onchange="updateCustomBiaya(${i}, this.value)" oninput="updateCustomBiaya(${i}, this.value)">
                    <p class="text-[9px] text-slate-500 mt-1">Sesuaikan dengan nilai kerusakan yang sesungguhnya</p>
                </div>
            `;
            container.appendChild(div);
        }
    }
    
    function updateKondisiUnit(index, kondisi) {
        kondisiUnit[index] = kondisi;
        const customBlock = document.getElementById('custom-block-' + index);
        if (customBlock) {
            customBlock.classList.toggle('hidden', kondisi !== 'lainnya');
        }
        updateSummary();
    }

    function updateCustomBiaya(index, value) {
        const amount = parseInt(value, 10);
        if (!customBiaya) {
            return;
        }
        customBiaya[index] = isNaN(amount) ? 0 : amount;
        updateSummary();
    }
    
    function updateSummary() {
        let dendaKondisi = 0;
        for (let i = 0; i < kondisiUnit.length; i++) {
            const k = kondisiUnit[i];
            if (k === 'lecet') dendaKondisi += 50000;
            else if (k === 'rusak') dendaKondisi += 200000;
            else if (k === 'hilang') dendaKondisi += 500000;
            else if (k === 'lainnya') {
                const customAmount = customBiaya[i] ? Math.max(0, parseInt(customBiaya[i], 10)) : 0;
                dendaKondisi += isNaN(customAmount) ? 0 : customAmount;
            }
        }
        
        // Pastikan angka bulat
        dendaKondisi = Math.floor(dendaKondisi);
        const total = dendaTerlambatValue + dendaKondisi;
        
        document.getElementById('denda-kondisi').innerHTML = 'Rp ' + dendaKondisi.toLocaleString('id-ID');
        document.getElementById('total-denda').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
        
        addHiddenInputs();
    }
    
    function addHiddenInputs() {
        const form = document.getElementById('formKondisi');
        const oldInputs = form.querySelectorAll('.hidden-input');
        oldInputs.forEach(input => input.remove());
        
        for (let i = 0; i < kondisiUnit.length; i++) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'kondisi_unit[]';
            input.value = kondisiUnit[i];
            input.className = 'hidden-input';
            form.appendChild(input);
            
            // Add custom biaya if lainnya is selected
            if (kondisiUnit[i] === 'lainnya') {
                const customInput = document.createElement('input');
                customInput.type = 'hidden';
                customInput.name = 'custom_biaya[]';
                customInput.value = customBiaya[i] ? Math.max(0, parseInt(customBiaya[i], 10)) : 0;
                customInput.className = 'hidden-input';
                form.appendChild(customInput);
            } else {
                const customInput = document.createElement('input');
                customInput.type = 'hidden';
                customInput.name = 'custom_biaya[]';
                customInput.value = '0';
                customInput.className = 'hidden-input';
                form.appendChild(customInput);
            }
        }
    }
    
    function closeKondisiModal() {
        document.getElementById('modal-kondisi').classList.add('hidden');
        document.getElementById('modal-kondisi').classList.remove('flex');
    }
    
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeKondisiModal();
    });
</script>
@endsection