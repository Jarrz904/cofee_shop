<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambah kolom untuk alasan penolakan, bisa null
            $table->text('rejection_reason')->nullable()->after('status');
            
            // Mengubah kolom status untuk mengakomodasi status baru
            $table->string('status')->default('pending')->change(); // pending, accepted, completed, rejected, cancelled
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
            $table->string('status')->default('pending')->change(); // Kembalikan ke state awal jika perlu
        });
    }
};