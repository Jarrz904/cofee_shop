<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        
        // ======================================================================
        // == TAMBAHKAN URL WEBHOOK ANDA DI SINI ==
        // ======================================================================
        
        /**
         * Pengecualian untuk notifikasi dari Midtrans.
         * Midtrans akan mengirim request POST ke URL ini tanpa CSRF token.
         * Pastikan URL ini cocok persis dengan yang Anda daftarkan di 
         * routes/web.php atau routes/api.php.
         */
        'payment/callback' 
    ];
}