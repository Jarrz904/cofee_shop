<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (Welcome Page).
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->latest()->get();
        
        return view('welcome', compact('categories', 'products'));
    }

    /**
     * Menyiapkan dan menampilkan dashboard untuk pelanggan.
     */
    public function dashboard()
    {
        // Arahkan ke dashboard admin jika role-nya admin
        if (Auth::user()->isAdmin()) { // Menggunakan method isAdmin() lebih bersih
            return redirect()->route('admin.dashboard');
        } 
        
        // --- LOGIKA UNTUK DASHBOARD PELANGGAN ---

        // 1. Ambil data untuk bagian menu
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->latest()->get();
        
        // 2. Ambil data untuk bagian keranjang
        $cartItems = Session::get('cart', []);
        
        // ==========================================================
        // == PERBAIKAN UTAMA: Hitung Total Harga di Sini ==
        // ==========================================================
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // 3. Kirim semua data yang dibutuhkan ke view, termasuk $totalPrice
        return view('dashboard', [
            'categories' => $categories,
            'products' => $products,
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice, // <-- Variabel yang hilang kini ditambahkan
        ]);
    }
}