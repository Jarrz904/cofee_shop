<x-app-layout> {{-- Pastikan menggunakan layout admin yang benar --}}
    
    {{-- SLOT HEADER: Judul dan Tombol Aksi Global dengan Gaya Baru --}}
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-serif text-3xl font-bold text-stone-200 leading-tight">
                {{ __('Kelola Produk') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-500 active:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-stone-900 transition ease-in-out duration-150 shadow-md">
                + Tambah Produk Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- FORM PENCARIAN & FILTER dengan Gaya Baru -->
            <div class="mb-8 p-6 bg-black/20 backdrop-blur-md border border-white/10 shadow-lg rounded-lg">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                        {{-- Filter Kategori --}}
                        <div class="md:col-span-2">
                            <label for="filter_category" class="block text-sm font-medium text-stone-300">Filter Kategori</label>
                            <select name="filter_category" id="filter_category" class="mt-1 block w-full rounded-md border-stone-600 bg-stone-800/50 text-white shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('filter_category') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Cari Produk --}}
                        <div>
                            <label for="search" class="block text-sm font-medium text-stone-300">Cari Produk</label>
                            <input type="text" name="search" id="search" class="mt-1 block w-full rounded-md border-stone-600 bg-stone-800/50 text-white shadow-sm focus:ring-amber-500 focus:border-amber-500" placeholder="Nama produk..." value="{{ request('search') }}">
                        </div>

                        {{-- Urutkan --}}
                        <div>
                            <label for="sort" class="block text-sm font-medium text-stone-300">Urutkan</label>
                            <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-stone-600 bg-stone-800/50 text-white shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Default</option>
                                <option value="name_asc" @selected(request('sort') == 'name_asc')>Nama (A-Z)</option>
                                <option value="name_desc" @selected(request('sort') == 'name_desc')>Nama (Z-A)</option>
                                <option value="price_asc" @selected(request('sort') == 'price_asc')>Harga (Termurah)</option>
                                <option value="price_desc" @selected(request('sort') == 'price_desc')>Harga (Termahal)</option>
                            </select>
                        </div>

                        {{-- Tombol Aksi Filter --}}
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                Terapkan
                            </button>
                            <a href="{{ route('admin.products.index') }}" title="Reset Filter" class="inline-flex justify-center p-2 border border-stone-600 shadow-sm text-sm font-medium rounded-md text-stone-300 bg-stone-700 hover:bg-stone-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABEL PRODUK dengan Gaya Baru --}}
            <div class="bg-black/20 backdrop-blur-md overflow-hidden shadow-lg sm:rounded-lg border border-white/10">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-black/20">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Nama Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse ($products as $product)
                            <tr class="hover:bg-white/5 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-md object-cover" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $product->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-300">
                                    {{ $product->category->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-amber-400 hover:text-amber-300">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-stone-400">
                                    Tidak ada produk yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if ($products->hasPages())
                   <div class="p-4 border-t border-white/10">{{ $products->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>