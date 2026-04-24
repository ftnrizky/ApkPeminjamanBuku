@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-8 rounded-2xl shadow text-center">

        <h1 class="text-xl font-bold mb-4">Bayar Denda</h1>

        <p class="mb-4 text-gray-500">
            Total: Rp {{ number_format($data->total_denda,0,',','.') }}
        </p>

        <button id="pay-button"
            class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700">
            Bayar Sekarang
        </button>

    </div>

</div>

<!-- MIDTRANS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            window.location.href = "/peminjam/riwayat";
        },
        onPending: function(result){
            alert("Menunggu pembayaran...");
        },
        onError: function(result){
            alert("Pembayaran gagal!");
        }
    });
};
</script>
@endsection