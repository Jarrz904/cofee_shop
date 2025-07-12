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
    Schema::table('products', function (Blueprint $table) {
        // Kolom foreign key bisa null jika produk tidak memiliki kategori
        $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->after('id');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
