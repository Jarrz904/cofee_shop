<?php

// Lokasi: app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;
use Throwable; // Import Throwable untuk penanganan error yang lebih baik

class PaymentController extends Controller
{
    // Method checkout Anda sudah baik, kita biarkan saja.
    public function checkout(Request $request)
    {
        // ... (kode checkout Anda)
    }

    /**
     * Menangani pembayaran ulang untuk pesanan yang sudah ada.
     */
    public function payAgain(Order $order)
    {
        // 1. Validasi Keamanan Awal
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            return response()->json(['error' => 'Pesanan ini tidak valid atau sudah diproses.'], 403);
        }

        // 2. PERBAIKAN: Validasi Konfigurasi Midtrans sebelum digunakan
        $serverKey = config('midtrans.server_key');
        if (!$serverKey) {
            return response()->json(['error' => 'Kunci Server Midtrans tidak dikonfigurasi. Silakan periksa file .env Anda.'], 500);
        }

        // 3. Dapatkan Snap Token
        try {
            $snapToken = $this->getSnapToken($order);
            $order->snap_token = $snapToken;
            $order->save();

            return response()->json(['snap_token' => $snapToken]);
            
        } catch (Throwable $e) { // Gunakan Throwable untuk menangkap semua jenis error
            // Kirim pesan error yang lebih spesifik ke frontend dan log
            \Log::error('Midtrans Snap Token Creation Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat token pembayaran. Silakan hubungi administrator.'], 500);
        }
    }

    /**
     * Helper function privat untuk membuat Snap Token.
     */
    private function getSnapToken(Order $order): string
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Siapkan parameter yang akan dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'COFFE-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->total_price, // Pastikan ini adalah integer dan nama kolom benar
            ],
            'customer_details' => [
                // PERBAIKAN: Pastikan relasi user ada sebelum diakses
                'first_name' => $order->user->name ?? 'Pelanggan',
                'email' => $order->user->email ?? 'email@pelanggan.com',
            ],
            'item_details' => $order->details->map(function ($detail) {
                return [
                    'id' => $detail->product_id,
                    'price' => (int) $detail->price,
                    'quantity' => (int) $detail->quantity,
                    // PERBAIKAN: Pastikan relasi product ada sebelum diakses
                    'name' => $detail->product->name ?? 'Produk Dihapus',
                ];
            })->toArray(),
        ];

        // Dapatkan token dari Midtrans
        return Snap::getSnapToken($params);
    }
}