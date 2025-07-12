<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan dengan paginasi.
     * Eager loading digunakan untuk performa optimal.
     */
    public function index()
    {
        // PERBAIKAN: Menggunakan with() dengan relasi bersarang (nested)
        // untuk mengambil data produk dari order detail.
        // PERBAIKAN: Menggunakan paginate() untuk menangani data yang besar.
        $orders = Order::with(['user', 'details.product'])
                        ->latest()
                        ->paginate(10); // Menampilkan 10 pesanan per halaman
                        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menerima pesanan yang statusnya 'pending'.
     */
    public function accept(Order $order)
    {
        // PERBAIKAN: Memastikan hanya pesanan 'pending' yang bisa diubah.
        // Ini mencegah pengguna mengubah status pesanan yang sudah selesai atau ditolak.
        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan ini sudah tidak bisa diubah.');
        }

        $order->update(['status' => 'completed']); // 'completed' lebih jelas daripada 'accepted'
        
        return back()->with('success', "Pesanan #{$order->id} berhasil diselesaikan.");
    }

    /**
     * Menolak pesanan 'pending' dengan alasan yang jelas.
     */
    public function reject(Request $request, Order $order)
    {
        // PERBAIKAN: Validasi status yang sama seperti di method accept().
        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan ini sudah tidak bisa diubah.');
        }
        
        // PERBAIKAN: Validasi yang lebih ketat dan pesan error yang lebih baik.
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5|max:255'
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
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
        // PERBAIKAN: Tidak ada perubahan, kode Anda sudah optimal.
        // `back()` sangat baik digunakan di sini karena akan mempertahankan
        // halaman paginasi dan filter yang mungkin ada.
        $order->delete();
        return back()->with('success', "Pesanan #{$order->id} berhasil dihapus.");
    }

    /**
     * Menghapus SEMUA pesanan dari database.
     * Ini adalah aksi yang berbahaya, bisa ditambahkan otorisasi tambahan jika perlu.
     */
    public function destroyAll()
    {
        // PERBAIKAN: Menggunakan truncate() lebih cepat dan me-reset auto-increment ID.
        // Ini adalah cara yang paling efisien untuk mengosongkan tabel.
        // Method ini tidak akan error jika tabel sudah kosong.
        Order::truncate();

        return redirect()->route('admin.orders.index')->with('success', 'Semua riwayat pesanan berhasil dihapus secara permanen.');
    }
}