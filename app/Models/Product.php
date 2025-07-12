<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Trait ini memungkinkan kita untuk menggunakan model factories.
     * Sangat berguna untuk testing dan seeder.
     */
    use HasFactory;

    /**
     * Mengizinkan semua atribut/kolom untuk diisi secara massal (mass assignment).
     * Ini aman digunakan karena kita sudah melakukan validasi ketat di controller.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Mendefinisikan relasi "belongsTo".
     * Setiap produk "milik" satu kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}