<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create(['name' => 'Espresso', 'description' => 'Kopi hitam pekat klasik.', 'price' => 15000]);
        Product::create(['name' => 'Latte', 'description' => 'Espresso dengan susu steamed.', 'price' => 25000]);
        Product::create(['name' => 'Cappuccino', 'description' => 'Kombinasi seimbang espresso, susu, dan busa.', 'price' => 25000]);
        Product::create(['name' => 'Americano', 'description' => 'Espresso yang diencerkan dengan air panas.', 'price' => 20000]);
    }
}