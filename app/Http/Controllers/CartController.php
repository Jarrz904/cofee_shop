<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja (jika masih digunakan).
     */
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return view('cart.index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }

    /**
     * Menambahkan produk ke dalam keranjang (session).
     * (Tidak ada perubahan, kode Anda sudah benar)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        $cart = Session::get('cart', []);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menghapus item dari keranjang.
     * (Tidak ada perubahan, kode Anda sudah benar)
     */
    public function remove($product_id)
    {
        $cart = Session::get('cart');

        if(isset($cart[$product_id])) {
            unset($cart[$product_id]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Produk berhasil dihapus.');
    }
    
    /**
     * [ALUR UTAMA] Memproses checkout, membuat order, dan mengembalikan Snap Token via AJAX.
     */
    public function checkout()
    {
        $cartItems = Session::get('cart', []);

        if (empty($cartItems)) {
            return response()->json(['error' => 'Keranjang Anda kosong.'], 400);
        }

        $order = null;
        try {
            DB::transaction(function () use ($cartItems, &$order) {
                $totalPrice = 0;
                foreach ($cartItems as $item) { $totalPrice += $item['price'] * $item['quantity']; }

                $order = Order::create([
                    'user_id' => Auth::id(), 'total_price' => $totalPrice, 'status' => 'unpaid',
                ]);

                foreach ($cartItems as $product_id => $item) {
                    OrderDetail::create([
                        'order_id' => $order->id, 'product_id' => $product_id,
                        'quantity' => $item['quantity'], 'price' => $item['price'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            \Log::error('Checkout Database Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat pesanan.'], 500);
        }
        
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true; Config::$is3ds = true;

        // Siapkan parameter untuk Midtrans
        $midtrans_params = $this->buildMidtransParameters($order, $cartItems);

        try {
            $snapToken = Snap::getSnapToken($midtrans_params);
            
            // Simpan snapToken ke order untuk referensi
            $order->snap_token = $snapToken;
            $order->save();

        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal terhubung ke layanan pembayaran.'], 500);
        }

        // Kosongkan keranjang di session
        Session::forget('cart');

        // Kembalikan Snap Token sebagai JSON untuk ditangkap AJAX
        return response()->json(['snap_token' => $snapToken]);
    }

    /**
     * [ALUR TAMBAHAN] Menghasilkan Snap Token untuk pesanan yang sudah ada (Bayar Ulang).
     */
    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        if (!in_array($order->status, ['unpaid', 'pending'])) {
            return redirect()->route('order.index')->with('error', 'Pesanan ini tidak bisa dibayar.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true; Config::$is3ds = true;

        $order->load('details.product');
        $itemDetails = $order->details->map(function ($detail) {
            return ['id' => (string)$detail->product_id, 'price' => (int)$detail->price, 'quantity' => (int)$detail->quantity, 'name' => $detail->product->name];
        })->all();

        $midtrans_params = $this->buildMidtransParameters($order, $itemDetails, true);

        try {
            $snapToken = Snap::getSnapToken($midtrans_params);
            $order->snap_token = $snapToken;
            $order->save();
        } catch (\Exception $e) {
            \Log::error('Retry Payment Snap Token Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal terhubung ke layanan pembayaran.');
        }

        // Arahkan ke view pembayaran yang sama
        return view('payment.checkout', compact('snapToken', 'order'));
    }

    /**
     * Helper method untuk membangun parameter Midtrans agar tidak duplikat kode.
     */
    private function buildMidtransParameters(Order $order, $items, $isRetry = false)
    {
        $orderId = ($isRetry ? 'RETRY-' : 'COFFEE-') . $order->id . '-' . time();
        $itemDetails = [];

        if($isRetry) {
             $itemDetails = $items;
        } else {
            $itemDetails = collect($items)->map(function ($item, $product_id) {
                return [ 'id' => (string)$product_id, 'price' => (int)$item['price'], 'quantity' => (int)$item['quantity'], 'name' => $item['name'] ];
            })->values()->all();
        }
        
        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [ 'first_name' => Auth::user()->name, 'email' => Auth::user()->email ],
            'item_details' => $itemDetails,
        ];
    }
}