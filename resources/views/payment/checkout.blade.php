<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-3xl font-bold text-stone-200 leading-tight">
            Konfirmasi & Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-stone-900/60 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-lg border border-white/10 p-8 text-stone-300">
                <h3 class="text-xl font-bold mb-2">Ringkasan Pesanan #{{ $order->id }}</h3>
                <p class="text-stone-400 mb-6">Silakan selesaikan pembayaran untuk pesanan Anda.</p>

                {{-- Detail Pesanan --}}
                <div class="border-y border-white/10 py-4">
                    @foreach($order->details as $detail)
                    <div class="flex justify-between items-center mb-2">
                        <span>{{ $detail->quantity }}x {{ $detail->product->name }}</span>
                        <span>Rp {{ number_format($detail->price * $detail->quantity) }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Total --}}
                <div class="flex justify-between items-center font-bold text-lg mt-4">
                    <span>TOTAL PEMBAYARAN</span>
                    <span class="text-amber-400">Rp {{ number_format($order->total_price) }}</span>
                </div>

                {{-- Tombol Bayar --}}
                <div class="mt-8 text-center">
                    <button id="pay-button" class="w-full sm:w-auto inline-flex items-center px-8 py-3 bg-amber-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. Load Script Midtrans Snap --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // 2. Ambil tombol bayar
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // 3. Panggil snap.pay dengan snapToken
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    /* Anda bisa menambahkan logika di sini, misalnya redirect ke halaman sukses */
                    // alert("Pembayaran sukses!"); 
                    window.location.href = '/my-orders'
                },
                onPending: function(result){
                    /* Anda bisa menambahkan logika di sini, misalnya redirect ke halaman pending */
                    // alert("Menunggu pembayaran!"); 
                    window.location.href = '/my-orders'
                },
                onError: function(result){
                    /* Anda bisa menambahkan logika di sini, misalnya menampilkan pesan error */
                    // alert("Pembayaran gagal!"); 
                },
                onClose: function(){
                    /* Dijalankan jika pelanggan menutup popup pembayaran tanpa menyelesaikan transaksi */
                    // alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            })
        });
    </script>
</x-app-layout>