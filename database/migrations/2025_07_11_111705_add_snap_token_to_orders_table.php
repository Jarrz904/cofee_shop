<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // TAMBAHKAN KODE INI
            // Menambahkan kolom untuk menyimpan snap_token dari Midtrans
            // Dibuat nullable() karena token hanya ada setelah checkout
            $table->string('snap_token')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // TAMBAHKAN KODE INI
            // Logika untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('snap_token');
        });
    }
};