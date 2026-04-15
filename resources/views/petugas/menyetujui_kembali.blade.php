@extends('layouts.petugas')

@section('title', 'Menyetujui Kembali')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center gap-3 animate-pulse">
    <i class="fas fa-check-circle text-xl"></i>
    <span class="font-bold text-sm uppercase tracking-wider">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-rose-500 text-white rounded-2xl shadow-lg shadow-rose-500/20 flex items-center gap-3">
    <i class="fas fa-exclamation-circle text-xl"></i>
    <span class="font-bold text-sm uppercase tracking-wider">{{ session('error') }}</span>
</div>
@endif

<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic uppercase">Persetujuan <span class="text-emerald-600">Kembali</span></h1>
        <p class="text-gray-500 font-medium uppercase text-[10px] tracking-widest mt-1">Verifikasi kondisi fisik & hitung denda otomatis</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-blue-50 px-6 py-3 rounded-2xl border border-blue-100 shadow-sm flex items-center gap-3">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
            <span class="text-xs font-black text-blue-700 uppercase tracking-widest">{{ $pengembalians->count() }} Alat Perlu Dicek</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                    <th class="pb-5 px-4 w-32">Kode Pinjam</th>
                    <th class="pb-5 px-4">Alat Olahraga</th>
                    <th class="pb-5 px-4">Peminjam</th> 
                    <th class="pb-5 px-4 text-center">Jumlah</th>
                    <th class="pb-5 px-4 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
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
                    <tr class="group hover:bg-gray-50/50 transition-all text-sm">
                        <td class="py-6 px-4">
                            <span class="font-bold text-gray-900 text-sm bg-gray-100 px-2 py-1 rounded-lg">
                                PJM-{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="py-6 px-4">
                            <p class="font-bold text-gray-900 text-sm">{{ $data->alat->nama_alat }}</p>
                            <span class="text-[10px] text-gray-400">Harga: Rp {{ number_format($data->alat->harga_sewa, 0, ',', '.') }}/hari</span>
                        </td>
                        <td class="py-6 px-4">
                            <span class="font-black text-gray-900">{{ $data->user->name }}</span><br>
                            <span class="text-[9px] text-gray-400">Batas: {{ \Carbon\Carbon::parse($data->tgl_kembali)->format('d/m/Y') }}</span>
                        </td>
                        <td class="py-6 px-4 text-center">
                            <span class="text-xl font-black text-gray-900">{{ $data->jumlah }}</span>
                            <span class="text-[10px] text-gray-400 block">Unit</span>
                        </td>
                        <td class="py-6 px-4 text-center">
                            <button type="button" 
                                    onclick="openKondisiModal({{ $data->id }}, '{{ $data->alat->nama_alat }}', '{{ $data->user->name }}', {{ $data->jumlah }}, {{ $data->alat->harga_asli ?? 0 }}, {{ $estimasiTerlambat }})" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-[10px] font-black px-6 py-3 rounded-xl transition-all">
                                <i class="fas fa-clipboard-list mr-2"></i> Cek Kondisi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center px-4">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fas fa-check-double text-emerald-500 text-3xl"></i>
                                </div>
                                <p class="text-gray-400 font-black uppercase italic tracking-[0.3em] text-[10px]">Tidak ada antrian cek alat.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL KONDISI SEDERHANA -->
<div id="modal-kondisi" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl max-w-lg w-full">
        <div class="bg-emerald-600 rounded-t-2xl p-4 text-white">
            <div class="flex justify-between items-center">
                <h3 class="font-bold">Input Kondisi Alat</h3>
                <button onclick="closeKondisiModal()" class="text-white text-xl">&times;</button>
            </div>
        </div>
        
        <form id="formKondisi" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <div class="p-4 space-y-3">
                <!-- Info -->
                <div class="bg-gray-50 p-3 rounded-lg text-sm">
                    <p><strong>Kode:</strong> <span id="modal-kode"></span></p>
                    <p><strong>Peminjam:</strong> <span id="modal-peminjam"></span></p>
                    <p><strong>Alat:</strong> <span id="modal-alat"></span></p>
                    <p><strong>Jumlah:</strong> <span id="modal-jumlah"></span> unit</p>
                </div>
                
                <!-- Daftar Unit -->
                <div id="unit-list" class="space-y-2 max-h-96 overflow-y-auto">
                    <!-- JS akan generate -->
                </div>
                
                <!-- Ringkasan Denda -->
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <div class="flex justify-between text-sm">
                        <span>Denda Terlambat:</span>
                        <span id="denda-terlambat" class="font-bold text-rose-600">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span>Denda Kondisi:</span>
                        <span id="denda-kondisi" class="font-bold text-rose-600">Rp 0</span>
                    </div>
                    <div class="border-t mt-2 pt-2 flex justify-between font-bold">
                        <span>TOTAL DENDA:</span>
                        <span id="total-denda" class="text-rose-700">Rp 0</span>
                    </div>
                </div>
            </div>
            
            <div class="p-4 pt-0 flex gap-2">
                <button type="button" onclick="closeKondisiModal()" class="flex-1 bg-gray-200 py-2 rounded-lg text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-lg text-sm font-bold">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentId = null;
    let kondisiUnit = [];
    let currentHargaAsli = 0;
    let dendaTerlambatValue = 0;
    
    function openKondisiModal(id, namaAlat, peminjam, jumlah, hargaAsli, dendaTerlambat) {
        currentId = id;
        currentHargaAsli = hargaAsli;
        dendaTerlambatValue = dendaTerlambat;
        
        document.getElementById('modal-kode').innerText = 'PJM-' + String(id).padStart(4, '0');
        document.getElementById('modal-peminjam').innerText = peminjam;
        document.getElementById('modal-alat').innerText = namaAlat;
        document.getElementById('modal-jumlah').innerText = jumlah;
        document.getElementById('denda-terlambat').innerHTML = 'Rp ' + dendaTerlambatValue.toLocaleString('id-ID');
        
        document.getElementById('formKondisi').action = '/petugas/kembali/proses/' + id;
        
        // Reset kondisi
        kondisiUnit = [];
        for (let i = 0; i < jumlah; i++) {
            kondisiUnit.push('baik');
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
            div.className = 'bg-gray-50 p-2 rounded-lg border border-gray-200';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="font-bold text-sm w-12">Unit ${i+1}</span>
                    <div class="flex gap-1">
                        <label class="cursor-pointer">
                            <input type="radio" name="kondisi_${i}" value="baik" class="hidden peer" checked onchange="updateKondisiUnit(${i}, 'baik')">
                            <span class="text-[10px] px-2 py-1 rounded bg-gray-200 peer-checked:bg-emerald-500 peer-checked:text-white">Baik</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="kondisi_${i}" value="lecet" class="hidden peer" onchange="updateKondisiUnit(${i}, 'lecet')">
                            <span class="text-[10px] px-2 py-1 rounded bg-gray-200 peer-checked:bg-yellow-400 peer-checked:text-white">Lecet</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="kondisi_${i}" value="rusak" class="hidden peer" onchange="updateKondisiUnit(${i}, 'rusak')">
                            <span class="text-[10px] px-2 py-1 rounded bg-gray-200 peer-checked:bg-red-500 peer-checked:text-white">Rusak</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="kondisi_${i}" value="hilang" class="hidden peer" onchange="updateKondisiUnit(${i}, 'hilang')">
                            <span class="text-[10px] px-2 py-1 rounded bg-gray-200 peer-checked:bg-black peer-checked:text-white">Hilang</span>
                        </label>
                    </div>
                </div>
            `;
            container.appendChild(div);
        }
    }
    
    function updateKondisiUnit(index, kondisi) {
        kondisiUnit[index] = kondisi;
        updateSummary();
    }
    
    function updateSummary() {
        let dendaKondisi = 0;
        for (let i = 0; i < kondisiUnit.length; i++) {
            const k = kondisiUnit[i];
            if (k === 'lecet') dendaKondisi += 15000;
            else if (k === 'rusak') dendaKondisi += 50000;
            else if (k === 'hilang') dendaKondisi += currentHargaAsli;
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