<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman riwayat pesanan dengan memisahkan
     * pesanan yang belum dibayar dan yang sudah selesai.
     */
    public function index()
    {
        // ==========================================================
        // == PERBAIKAN LOGIKA UTAMA ADA DI SINI ==
        // ==========================================================

        // 1. Ambil semua pesanan yang statusnya 'unpaid' atau 'pending'
        // Ini adalah pesanan yang menunggu aksi dari user (pembayaran) atau admin (konfirmasi).
        $pendingOrders = Order::where('user_id', Auth::id())
                            ->whereIn('status', ['unpaid', 'pending'])
                            ->with('details.product')
                            ->latest()
                            ->get(); // Kita pakai get() karena daftar ini biasanya pendek dan butuh perhatian segera.

        // 2. Ambil semua pesanan yang statusnya BUKAN 'unpaid' atau 'pending'
        // Ini adalah riwayat transaksi yang sesungguhnya (lunas, diproses, selesai, ditolak, dll).
        $historyOrders = Order::where('user_id', Auth::id())
                          ->whereNotIn('status', ['unpaid', 'pending'])
                          ->with('details.product')
                          ->latest()
                          ->paginate(10); // Kita pakai paginate() karena daftar riwayat bisa sangat panjang.

        // 3. Kirim kedua variabel tersebut ke view
        return view('user.orders.index', [
            'pendingOrders' => $pendingOrders,
            'historyOrders'   => $historyOrders
        ]);
    }

    /**
     * Menyimpan pesanan baru ke database.
     * (Tidak ada perubahan, kode Anda di sini sudah sangat baik)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:100',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                
                $product = Product::find($validated['product_id']);
                $quantity = (int) $validated['quantity'];
                $totalPrice = $product->price * $quantity;
                
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $totalPrice,
                    'status' => 'pending', // atau 'unpaid'
                ]);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            });

        } catch (\Exception $e) {
            // Catat error untuk debugging
            \Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan.');
        }

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi dari admin.');
    }
}