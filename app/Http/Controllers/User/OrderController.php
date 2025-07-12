<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- PENTING: Import DB Facade untuk transaction

class OrderController extends Controller
{
    /**
     * Menampilkan halaman riwayat pesanan milik user yang sedang login.
     */
    public function index()
    {
        // Kode ini sudah benar, tidak perlu diubah.
        // Mengambil semua pesanan dengan detail dan produknya.
        $orders = Order::where('user_id', Auth::id())
                        ->with('details.product')
                        ->latest()
                         ->paginate(5);
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Menyimpan pesanan baru ke database.
     * Logika ini sekarang menangani input kuantitas.
     */
    public function store(Request $request)
    {
        // 1. Validasi input, termasuk kuantitas dari form
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:100', // Pastikan kuantitas adalah angka > 0
        ]);

        // Menggunakan transaction untuk menjaga konsistensi data.
        // Jika salah satu query gagal, semua akan dibatalkan (rollback).
        try {
            DB::transaction(function () use ($validated) {
                
                $product = Product::find($validated['product_id']);
                $quantity = (int) $validated['quantity'];

                // 2. Hitung total harga berdasarkan harga produk dan kuantitas
                $totalPrice = $product->price * $quantity;
                
                // 3. Buat record di tabel 'orders'
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // 4. Buat record di tabel 'order_details'
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity, // <-- Menyimpan kuantitas yang benar
                    'price' => $product->price, // <-- Harga satuan saat pemesanan
                ]);

            }); // Akhir dari transaction

        } catch (\Exception $e) {
            // Jika terjadi error selama transaksi, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}