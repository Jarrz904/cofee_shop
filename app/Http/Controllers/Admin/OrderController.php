<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index()
    {
        $orders = Order::with(['user', 'details.product'])
                        ->latest()
                        ->paginate(10);
                        
        return view('admin.orders.index', compact('orders'));
    }

    // ==========================================================
    // == METHOD BARU YANG DITAMBAHKAN UNTUK KONFIRMASI PEMBAYARAN ==
    // ==========================================================
    /**
     * Mengkonfirmasi pembayaran secara manual dan mengubah status menjadi 'paid'.
     */
    public function confirmPayment(Order $order)
    {
        // Hanya izinkan konfirmasi jika statusnya 'unpaid' atau 'pending'
        if (!in_array($order->status, ['unpaid', 'pending'])) {
            return back()->with('error', 'Pesanan ini tidak perlu dikonfirmasi lagi.');
        }

        // Ubah status menjadi 'paid'
        $order->update(['status' => 'paid']);

        return back()->with('success', "Pembayaran untuk Pesanan #{$order->id} berhasil dikonfirmasi.");
    }

    /**
     * Mengubah status pesanan dari 'paid' menjadi 'processing'.
     */
    public function process(Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Hanya pesanan yang sudah lunas yang bisa diproses.');
        }

        $order->update(['status' => 'processing']);

        return back()->with('success', "Pesanan #{$order->id} sedang diproses.");
    }

    /**
     * Mengubah status pesanan dari 'processing' menjadi 'completed'.
     */
    public function complete(Order $order)
    {
        if ($order->status !== 'processing') {
            return back()->with('error', 'Hanya pesanan yang sedang diproses yang bisa diselesaikan.');
        }
        
        $order->update(['status' => 'completed']);

        return back()->with('success', "Pesanan #{$order->id} telah selesai.");
    }

    /**
     * Menolak pesanan yang belum dikonfirmasi.
     */
    public function reject(Request $request, Order $order)
    {
        if (!in_array($order->status, ['unpaid', 'pending'])) {
            return back()->with('error', 'Status pesanan ini tidak bisa ditolak.');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5|max:255'
        ]);

        $order->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', "Pesanan #{$order->id} telah ditolak.");
    }

    /**
     * Menghapus satu pesanan dari database.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', "Pesanan #{$order->id} berhasil dihapus.");
    }

    /**
     * Menghapus SEMUA pesanan dari database.
     */
    public function destroyAll()
    {
        Schema::disableForeignKeyConstraints();
        OrderDetail::truncate();
        Order::truncate();
        Schema::enableForeignKeyConstraints();

        return redirect()->route('admin.orders.index')->with('success', 'Semua riwayat pesanan berhasil dihapus.');
    }
}