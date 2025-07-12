<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coffe Shop - Nikmati Setiap Tetesnya</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Menambahkan font kustom ke CSS */
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Lato', sans-serif; }
        .hero-bg {
            background-image: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.2)), url('https://images.unsplash.com/photo-1559925393-8be0ec4767c8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80');
        }
    </style>
</head>
<body class="bg-zinc-900 text-stone-300 font-sans antialiased">

    <!-- HEADER / NAVIGASI -->
    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-300" 
            x-data="{ top: true }" 
            @scroll.window="top = (window.pageYOffset > 10) ? false : true"
            :class="{ 'bg-zinc-900/90 backdrop-blur-sm shadow-lg': !top, 'bg-transparent': top }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center gap-2">
                    <img class="h-8 w-auto" src="{{ asset('../images/logo coffe.png') }}" alt="Coffee Shop Logo"> <!-- Ganti dengan path logo Anda -->
                    <span class="font-bold text-xl text-white">Coffe Shop</span>
                </a>
                <!-- Tombol Login & Register -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm text-white bg-amber-600 rounded-full hover:bg-amber-700 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-stone-300 hover:text-white transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm text-white bg-amber-600 rounded-full hover:bg-amber-700 transition">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- HERO SECTION -->
        <section class="h-screen w-full flex items-center justify-center hero-bg bg-cover bg-center">
            <div class="text-center text-white p-4">
                <h1 class="font-serif text-5xl md:text-7xl font-bold drop-shadow-lg">Secangkir Ketenangan.</h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-stone-200 drop-shadow-md">
                    Setiap biji kopi kami adalah sebuah cerita. Temukan favoritmu dan ciptakan momen tak terlupakan bersama kami.
                </p>
                <a href="#menu" class="mt-8 inline-block bg-amber-600 text-white font-bold px-8 py-3 rounded-full uppercase tracking-wider text-sm hover:bg-amber-700 transition-transform duration-300 hover:scale-105 shadow-lg">
                    Lihat Menu
                </a>
            </div>
        </section>

        {{-- Letakkan kode ini untuk menggantikan <section id="menu"> yang lama --}}

<section id="menu" class="py-20 sm:py-24 bg-zinc-950/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Inisialisasi Alpine.js untuk sistem tab --}}
        <div x-data="{
            selectedCategory: 'all',
            products: {{ $products->toJson() }},
            categories: {{ $categories->toJson() }},

            get filteredProducts() {
                if (this.selectedCategory === 'all') {
                    // Jika 'Unggulan', tampilkan 8 produk secara acak
                    return this.products.sort(() => 0.5 - Math.random()).slice(0, 8);
                }
                // Jika kategori lain dipilih, filter produk
                return this.products.filter(p => p.category_id == this.selectedCategory);
            }
        }">
            {{-- Judul Section --}}
            <div class="text-center">
                <h2 class="font-serif text-4xl font-bold text-white">Pilihan Favorit Kami</h2>
                <p class="mt-3 max-w-2xl mx-auto text-lg text-stone-400">
                    Disajikan dengan penuh cinta dari biji kopi terbaik, hanya untuk Anda.
                </p>
            </div>

            {{-- NAVIGASI TAB KATEGORI --}}
            <div class="mt-12 mb-8 flex justify-center flex-wrap gap-3">
                <button @click="selectedCategory = 'all'" 
                        :class="{ 'bg-amber-500 text-black font-bold': selectedCategory === 'all', 'bg-stone-800 text-stone-300 hover:bg-stone-700': selectedCategory !== 'all' }"
                        class="px-5 py-2 text-sm rounded-full transition duration-300">
                    Semua
                </button>
                {{-- Loop untuk membuat tombol dari data kategori --}}
                <template x-for="category in categories" :key="category.id">
                    <button @click="selectedCategory = category.id"
                            :class="{ 'bg-amber-500 text-black font-bold': selectedCategory == category.id, 'bg-stone-800 text-stone-300 hover:bg-stone-700': selectedCategory != category.id }"
                            class="px-5 py-2 text-sm rounded-full transition duration-300"
                            x-text="category.name">
                    </button>
                </template>
            </div>

            {{-- GRID PRODUK DINAMIS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Loop untuk menampilkan kartu produk berdasarkan kategori yang dipilih --}}
                <template x-for="product in filteredProducts" :key="product.id">
                    <div x-show="true" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="group bg-zinc-800/50 rounded-lg overflow-hidden shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                        <img :src="product.image ? '/storage/' + product.image : 'https://via.placeholder.com/400x300'" :alt="product.name" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-white group-hover:text-amber-500 transition-colors" x-text="product.name"></h3>
                            <p class="mt-2 text-sm text-stone-400 h-10 overflow-hidden" x-text="product.description"></p>
                           
                        </div>
                    </div>
                </template>
            </div>
            
            {{-- Pesan jika kategori yang dipilih tidak punya produk --}}
            <div x-show="filteredProducts.length === 0" class="text-center py-16 text-stone-500" style="display: none;">
                <p>Belum ada produk di kategori ini.</p>
            </div>

        </div>
    </div>
</section>
    </main>
    
    <!-- FOOTER -->
    <footer class="bg-zinc-950 border-t border-zinc-800">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center text-sm text-stone-500">
            <p>© {{ date('Y') }} Jarrz Coffe Shop. All Rights Reserved.</p>
            <p class="mt-1">Dibuat dengan <span class="text-amber-500">♥</span> Penuh Kesabaran</p>
        </div>
    </footer>

</body>
</html>