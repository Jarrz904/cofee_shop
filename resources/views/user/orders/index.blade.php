<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-300 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Tampilkan pesan sukses --}}
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- == BAGIAN 1: PESANAN YANG MENUNGGU KONFIRMASI / PEMBAYARAN == --}}
            {{-- ========================================================== --}}
            
            {{-- PERBAIKAN: Menggunakan $pendingOrders --}}
            @if($pendingOrders->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-4 text-gray-300 dark:text-gray-100">Pesanan Aktif</h3>
                    <div class="space-y-4">
                        {{-- PERBAIKAN: Menggunakan $pendingOrders --}}
                        @foreach($pendingOrders as $order)
                            <div class="bg-yellow-50 dark:bg-gray-800/50 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-lg p-4">
                                {{-- Tampilkan detail pesanan yang sedang menunggu --}}
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-gray-200">Pesanan #{{ $order->id }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($order->total_price) }}</p>
                                </div>
                                <div class="border-t my-3 dark:border-gray-600"></div>
                                <div>
                                    <strong class="text-gray-800 dark:text-gray-200">Detail Pesanan:</strong>
                                    <ul class="list-disc list-inside mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        @foreach($order->details as $detail)
                                            <li>{{ $detail->quantity }}x {{ $detail->product->name ?? 'Produk Dihapus' }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <p class="mt-3 text-sm text-gray-800 dark:text-gray-200">
                                    <strong>Status:</strong>
                                    <span class="font-semibold text-yellow-700 dark:text-yellow-400">{{ ucfirst($order->status) }}</span>
                                </p>
                                {{-- Anda bisa menambahkan tombol "Bayar Sekarang" di sini jika menggunakan alur Midtrans --}}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- == BAGIAN 2: RIWAYAT TRANSAKSI YANG SUDAH SELESAI == --}}
            {{-- ========================================================== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Riwayat Transaksi</h3>
                    
                    {{-- PERBAIKAN: Menggunakan $historyOrders --}}
                    @if($historyOrders->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Anda belum memiliki riwayat transaksi.</p>
                    @else
                        <div class="space-y-6">
                            {{-- PERBAIKAN: Menggunakan $historyOrders --}}
                            @foreach($historyOrders as $order)
                                <div class="border-b dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                    {{-- Tampilkan detail order yang sudah lunas/selesai/gagal --}}
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold">Pesanan #{{ $order->id }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <p class="text-lg font-semibold">Rp {{ number_format($order->total_price) }}</p>
                                    </div>
                                    <p class="mt-3 text-sm">
                                        <strong>Status:</strong>
                                        <span class="font-semibold 
                                            @if($order->status == 'paid' || $order->status == 'completed') text-green-600 dark:text-green-400 
                                            @elseif($order->status == 'rejected' || $order->status == 'failed') text-red-600 dark:text-red-400 
                                            @else text-gray-600 dark:text-gray-400 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- PERBAIKAN: Tampilkan link paginasi untuk $historyOrders --}}
            <div class="mt-4">
                {{ $historyOrders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>