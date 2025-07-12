<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-stone-200 leading-tight">
                {{ __('Daftar Semua Pesanan') }}
            </h2>
            
            @if($orders->isNotEmpty())
                <form action="{{ route('admin.orders.destroyAll') }}" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN ingin menghapus SEMUA data pesanan? Aksi ini tidak bisa dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-25 transition ease-in-out duration-150">
                        Hapus Semua Pesanan
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div x-data="{ 
        showModal: false, 
        orderId: null, 
        actionUrl: '' 
    }"
    @keydown.escape.window="showModal = false">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-stone-900/60 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-lg border border-white/10">
                    <div class="p-6 text-stone-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-white/10">
                                <thead class="bg-black/20">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Pemesan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Detail Pesanan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-black/10 divide-y divide-white/10">
                                    @forelse ($orders as $order)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="font-medium text-white">#{{ $order->id }}</div>
                                            <div class="text-stone-400">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-300">
                                            {{ $order->user->name ?? 'User Dihapus' }}
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-stone-300">
                                            <ul class="space-y-1">
                                                @foreach($order->details as $detail)
                                                    <li>- {{ $detail->quantity }}x {{ $detail->product->name ?? 'Produk Dihapus' }}</li>
                                                @endforeach
                                            </ul>
                                            <p class="font-semibold mt-2 text-amber-400">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </td>
                                        
                                        {{-- == PERUBAHAN 1: Menambahkan Status Pembayaran == --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @switch($order->status)
                                                    @case('pending')    bg-yellow-500/20 text-yellow-300 @break
                                                    @case('paid')       bg-blue-500/20 text-blue-300    @break {{-- Lunas --}}
                                                    @case('processing') bg-orange-500/20 text-orange-300 @break {{-- Diproses --}}
                                                    @case('completed')  bg-green-500/20 text-green-300   @break
                                                    @case('rejected')   bg-red-500/20 text-red-300       @break
                                                    @case('failed')     bg-red-500/20 text-red-300       @break
                                                    @default            bg-gray-500/20 text-gray-300
                                                @endswitch
                                            ">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            @if($order->rejection_reason)
                                                <p class="text-xs text-stone-400 mt-1" title="Alasan Penolakan">Alasan: {{ Str::limit($order->rejection_reason, 30) }}</p>
                                            @endif
                                        </td>
                                        {{-- Akhir Perubahan 1 --}}

                                        {{-- == PERUBAHAN 2: Aksi Dinamis Berdasarkan Status Pembayaran == --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col space-y-2">
                                                @if($order->status == 'pending')
                                                    {{-- Tombol Terima/Tolak untuk pesanan yang belum dibayar --}}
                                                    <div class="flex space-x-4">
                                                        <form action="{{ route('admin.orders.accept', $order) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-400 hover:text-green-300">Terima (COD)</button>
                                                        </form>
                                                        <button @click="showModal = true; orderId = {{ $order->id }}; actionUrl = '{{ route('admin.orders.reject', $order) }}'" class="text-yellow-400 hover:text-yellow-300">
                                                            Tolak
                                                        </button>
                                                    </div>
                                                @elseif($order->status == 'paid')
                                                    {{-- Tombol untuk memproses pesanan yang sudah lunas --}}
                                                    <form action="{{ route('admin.orders.process', $order) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-indigo-400 hover:text-indigo-300">Proses Pesanan</button>
                                                    </form>
                                                @elseif($order->status == 'processing')
                                                    {{-- Tombol untuk menyelesaikan pesanan yang sedang diproses --}}
                                                    <form action="{{ route('admin.orders.complete', $order) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-green-400 hover:text-green-300">Selesaikan</button>
                                                    </form>
                                                @endif
                                                
                                                {{-- Tombol Hapus selalu ada --}}
                                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan #{{ $order->id }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                        {{-- Akhir Perubahan 2 --}}
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-stone-400">Belum ada pesanan yang masuk.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($orders->hasPages())
                           <div class="mt-4 p-4 border-t border-white/10">{{ $orders->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL tidak perlu diubah, sudah fungsional --}}
        <div x-show="showModal" x-cloak ...>
           ...
        </div>
    </div>
</x-app-layout>  