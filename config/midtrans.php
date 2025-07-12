<?php

// Lokasi: config/midtrans.php

return [
    /**
     * Kunci API Midtrans Anda dari dashboard.
     * Diambil dari file .env untuk keamanan.
     */

    // MEMBACA variabel bernama 'MIDTRANS_MERCHANT_ID' dari file .env
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'), 
    
    // MEMBACA variabel bernama 'MIDTRANS_CLIENT_KEY' dari file .env
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    
    // MEMBACA variabel bernama 'MIDTRANS_SERVER_KEY' dari file .env
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    /**
     * Menentukan apakah akan menggunakan environment Produksi atau Sandbox.
     * Diambil dari file .env. Default-nya adalah 'false' (Sandbox).
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /**
     * Aktifkan sanitasi untuk membersihkan input dari potensi serangan XSS.
     * Direkomendasikan untuk selalu 'true'.
     */
    'is_sanitized' => true,

    /**
     * Aktifkan 3D Secure untuk transaksi kartu kredit.
     * Direkomendasikan untuk selalu 'true' untuk keamanan tambahan.
     */
    'is_3ds' => true,
];