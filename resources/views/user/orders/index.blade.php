<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Daftar Pesanan Anda</h3>
                    
                    @if($orders->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Anda belum memiliki riwayat pesanan.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    {{-- Detail Pesanan Anda yang sudah ada --}}
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold">Pesanan #{{ $order->id }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div>
                                            {{-- Ganti 'total_price' dengan kolom yang benar, misal 'total_amount' --}}
                                            <p class="text-lg font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="border-t my-3 dark:border-gray-600"></div>
                                    <div>
                                        <strong>Detail Pesanan:</strong>
                                        <ul class="list-disc list-inside mt-2 text-sm">
                                            @foreach($order->details as $detail)
                                                <li>{{ $detail->quantity }}x {{ $detail->product->name ?? 'Produk Dihapus' }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-sm">
                                            <strong>Status:</strong>
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($order->status == 'paid') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                    </div>

                                    {{-- Tombol Bayar Sekarang (sudah ada) --}}
                                    @if($order->status == 'pending')
                                        <div class="mt-4 text-right">
                                            <button 
                                                class="pay-button inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition ease-in-out duration-150"
                                                data-order-id="{{ $order->id }}">
                                                BAYAR SEKARANG
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                {{-- Paginasi Anda --}}
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- == SCRIPT UNTUK MEMBUAT TOMBOL BAYAR MENJADI FUNGSIONAL == --}}
    {{-- ========================================================== --}}
@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const payButtons = document.querySelectorAll('.pay-button');

        payButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const orderId = this.dataset.orderId;
                const payButtonElement = this;

                payButtonElement.disabled = true;
                payButtonElement.textContent = 'MEMPROSES...';

                // ===================================
                // == PERBAIKAN: URL dan CSRF Token ==
                // ===================================

                // 1. Buat URL secara dinamis dan andal
                const urlTemplate = "{{ route('order.pay_again', ['order' => ':orderId']) }}";
                const finalUrl = urlTemplate.replace(':orderId', orderId);

                fetch(finalUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // 2. Ambil CSRF token dari meta tag
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    // Tambahkan pengecekan ini untuk debug yang lebih baik
                    if (!response.ok) {
                        // Jika status bukan 200-299, lemparkan error untuk ditangkap .catch()
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            // ... semua callback Anda (onSuccess, onError, dll)
                            onSuccess: function(result){
                                alert("Pembayaran berhasil!");
                                window.location.reload();
                            },
                            onClose: function(){
                                payButtonElement.disabled = false;
                                payButtonElement.textContent = 'BAYAR SEKARANG';
                            }
                        });
                    } else {
                        alert(data.error || 'Gagal memproses pembayaran.');
                        payButtonElement.disabled = false;
                        payButtonElement.textContent = 'BAYAR SEKARANG';
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('Terjadi kesalahan. Tidak dapat terhubung ke server atau respons tidak valid.');
                    payButtonElement.disabled = false;
                    payButtonElement.textContent = 'BAYAR SEKARANG';
                });
            });
        });
    });
</script>
@endpush
</x-app-layout>