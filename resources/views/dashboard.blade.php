<x-app-layout>
    {{-- SLOT HEADER --}}
    <x-slot name="header">
        <h2 class="font-serif text-3xl font-bold text-stone-200 leading-tight">
            Selamat Datang, {{ Auth::user()->name }}!
        </h2>
    </x-slot>

    {{-- KONTEN UTAMA --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
            
            {{-- =============================================== --}}
            {{-- == BAGIAN KERANJANG BELANJA == --}}
            {{-- =============================================== --}}
            <div>
                <h3 class="font-serif text-2xl font-bold mb-4 text-stone-200">Keranjang Anda</h3>
                <div class="bg-black/20 backdrop-blur-md overflow-hidden shadow-lg sm:rounded-lg border border-white/10">
                    @if (!empty($cartItems))
                        {{-- Tampilan jika keranjang ADA isinya --}}
                        <div class="p-0">
                            <table class="w-full text-sm text-left text-stone-300">
                                <thead class="text-xs text-stone-400 uppercase bg-black/30">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Produk</th>
                                        <th scope="col" class="px-6 py-3 text-center">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-right">Harga</th>
                                        <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                        <th scope="col" class="px-4 py-3 text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $id => $details)
                                        <tr class="border-b border-white/10">
                                            <td class="px-6 py-4 font-semibold text-white">{{ $details['name'] }}</td>
                                            <td class="px-6 py-4 text-center">{{ $details['quantity'] }}</td>
                                            <td class="px-6 py-4 text-right">Rp {{ number_format($details['price']) }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-amber-400">Rp {{ number_format($details['price'] * $details['quantity']) }}</td>
                                            <td class="px-4 py-4 text-right">
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-500 hover:text-red-400">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 bg-black/30 flex justify-between items-center">
                            {{-- Variabel $totalPrice ini dikirim dari HomeController --}}
                            <span class="text-xl font-bold text-white">Total: Rp {{ number_format($totalPrice) }}</span>
                            <button id="checkout-button" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                Checkout
                            </button>
                        </div>
                    @else
                        {{-- Tampilan jika keranjang KOSONG --}}
                        <div class="p-10 text-center text-stone-400">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-stone-300">Keranjang Anda Kosong</h3>
                            <p class="mt-1 text-sm text-stone-400">Pilih menu favorit Anda di bawah untuk memulai pesanan.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- =============================================== --}}
            {{-- == BAGIAN MENU KAMI (SELALU TAMPIL) == --}}
            {{-- =============================================== --}}
            <div x-data="{ 
                selectedCategory: 'all', 
                products: {{ $products->toJson() }},
                categories: {{ $categories->toJson() }},
                get filteredProducts() {
                    if (this.selectedCategory === 'all') { return this.products; }
                    return this.products.filter(p => p.category_id == this.selectedCategory);
                }
            }">
                <h2 class="font-serif text-4xl font-bold text-center mb-6 text-stone-200">Menu Kami</h2>
                
                {{-- Tombol Filter Kategori --}}
                <div class="mb-8 flex justify-center flex-wrap gap-2">
                    <button @click="selectedCategory = 'all'" :class="{ 'bg-amber-500 text-black font-bold shadow-lg': selectedCategory === 'all', 'bg-stone-700/50 text-stone-300 hover:bg-stone-600/50': selectedCategory !== 'all' }" class="px-4 py-2 text-sm rounded-full transition">Semua</button>
                    <template x-for="category in categories">
                        <button @click="selectedCategory = category.id" :class="{ 'bg-amber-500 text-black font-bold shadow-lg': selectedCategory == category.id, 'bg-stone-700/50 text-stone-300 hover:bg-stone-600/50': selectedCategory != category.id }" class="px-4 py-2 text-sm rounded-full transition" x-text="category.name"></button>
                    </template>
                </div>

                {{-- Grid Produk --}}
               <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <template x-for="product in filteredProducts" :key="product.id">
        <div class="bg-stone-900/60 backdrop-blur-sm overflow-hidden shadow-lg rounded-lg flex flex-col border border-white/10 transform hover:-translate-y-1.5 transition-transform duration-300">
            
            {{-- PERBAIKAN UTAMA ADA DI BARIS INI --}}
            <img :src="product.image ? '/storage/' + product.image : 'https://via.placeholder.com/400x300.png'" 
                 :alt="product.name" 
                 class="w-full h-48 object-cover">
            
            <form action="{{ route('cart.add') }}" method="POST" class="p-6 flex-grow flex flex-col">
                                @csrf
                                <input type="hidden" name="product_id" :value="product.id">
                                <div class="flex-grow">
                                    <h3 class="font-bold text-lg text-white" x-text="product.name"></h3>
                                    <p class="text-stone-400 text-sm mt-2 h-10 overflow-hidden" x-text="product.description"></p>
                                </div>
                                <p class="text-xl font-bold mt-4 text-amber-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.price)"></p>
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center">
                                        <label :for="'quantity-' + product.id" class="text-sm font-medium text-stone-300 mr-2">Jumlah:</label>
                                        <input type="number" :id="'quantity-' + product.id" name="quantity" class="w-16 rounded-md border-stone-600 bg-stone-800 text-white shadow-sm text-center" min="1" value="1">
                                    </div>
                                    <x-primary-button type="submit" class="bg-amber-600 hover:bg-amber-500 focus:bg-amber-700 active:bg-amber-800 focus:ring-amber-500">
                                        + Keranjang
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT AJAX UNTUK CHECKOUT DINAMIS --}}
    @push('scripts')
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
          const checkoutButton = document.getElementById('checkout-button');
          
          if(checkoutButton) {
              checkoutButton.addEventListener('click', async function(e) {
                  e.preventDefault();
                  
                  this.disabled = true;
                  this.innerHTML = 'Memproses...';

                  try {
                      const response = await fetch('{{ route("cart.checkout") }}', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': '{{ csrf_token() }}'
                          },
                      });

                      const data = await response.json();

                      if (response.ok) {
                          // Jika sukses, panggil popup Midtrans
                          window.snap.pay(data.snap_token, {
                              onSuccess: function(result){
                                  // Refresh halaman untuk mengosongkan keranjang
                                  window.location.href = '{{ route("dashboard") }}'; 
                              },
                              onPending: function(result){
                                  window.location.href = '{{ route("order.index") }}';
                              },
                              onError: function(result){
                                  alert("Pembayaran gagal!");
                                  checkoutButton.disabled = false;
                                  checkoutButton.innerHTML = 'Checkout';
                              },
                              onClose: function(){
                                  checkoutButton.disabled = false;
                                  checkoutButton.innerHTML = 'Checkout';
                              }
                          });
                      } else {
                          throw new Error(data.error || 'Terjadi kesalahan saat checkout.');
                      }

                  } catch (error) {
                      alert('Error: ' + error.message);
                      checkoutButton.disabled = false;
                      checkoutButton.innerHTML = 'Checkout';
                  }
              });
          }
      });
    </script>
    @endpush
</x-app-layout>