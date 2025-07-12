<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            \Log::error("Gagal membuat instance Notifikasi Midtrans: " . $e->getMessage());
            return response()->json(['error' => 'Invalid notification'], 400);
        }
        
        // Mengambil order_id yang sebenarnya dari format 'KODE-ID-TIMESTAMP'
        $orderIdParts = explode('-', $notification->order_id);
        $orderId = $orderIdParts[1] ?? null; // Ambil bagian tengah

        if (!$orderId) {
            \Log::warning("Notifikasi Midtrans dengan format order_id tidak valid: " . $notification->order_id);
            return response()->json(['error' => 'Invalid Order ID format'], 400);
        }

        $order = Order::find($orderId);
        
        if (!$order) {
            \Log::warning("Order tidak ditemukan untuk notifikasi Midtrans: " . $notification->order_id);
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        // Keamanan: Cek signature key
        $signature_key = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
        if ($notification->signature_key != $signature_key) {
             \Log::error("Signature key tidak valid untuk order_id: " . $notification->order_id);
             return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Handler untuk status transaksi
        if ($notification->transaction_status == 'capture' || $notification->transaction_status == 'settlement') {
            $order->status = 'paid';
        } else if ($notification->transaction_status == 'pending') {
            $order->status = 'pending';
        } else {
            $order->status = 'failed';
        }

        $order->save();
        
        \Log::info("Status order #{$order->id} berhasil diupdate menjadi {$order->status}");
        return response()->json(['message' => 'Success']);
    }
}