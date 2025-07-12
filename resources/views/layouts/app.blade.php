<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-serif { font-family: 'Playfair Display', serif; }
            .font-sans { font-family: 'Lato', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased">
        {{-- DIV PEMBUNGKUS min-h-screen TELAH DIHAPUS DARI SINI --}}

        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="pt-8 pb-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        
        {{-- DIV PENUTUP JUGA DIHAPUS DARI SINI --}}

        {{-- ========================================================== --}}
        {{-- == BAGIAN PENTING YANG HILANG DITAMBAHKAN DI SINI == --}}
        {{-- ========================================================== --}}
        
        <!-- 1. MEMUAT SCRIPT INTI DARI MIDTRANS (WAJIB) -->
        <!-- Script ini secara cerdas akan memilih antara Sandbox (development) dan Produksi. -->
        <script type="text/javascript" 
                src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
                data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        
        <!-- 2. WADAH UNTUK SCRIPT SPESIFIK DARI HALAMAN LAIN (WAJIB) -->
        <!-- Kode JavaScript dari riwayat-pesanan.blade.php akan dimasukkan ke sini. -->
        @stack('scripts')
        
    </body>
</html>