<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category; // Asumsikan Anda memiliki model Category
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Database
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        
        // Asumsi: Jika Anda memiliki tabel categories, reset juga.
        // Category::truncate(); 
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // DUMMY CATEGORIES (Jika Anda punya tabel Category)
        // ==========================================
        
        // $coffee = Category::create(['name' => 'Coffee & Espresso', 'icon' => '☕']);
        // $nonCoffee = Category::create(['name' => 'Non-Coffee & Tea', 'icon' => '🍹']);
        // $mainCourse = Category::create(['name' => 'Main Course', 'icon' => '🍽️']);
        // $snack = Category::create(['name' => 'Snacks & Appetizers', 'icon' => '🥨']);
        
        // Asumsi ID Category untuk seeding: 
        // 1 = Coffee, 2 = Non-Coffee, 3 = Main Course, 4 = Snack

        // ==========================================
        // INPUT PRODUK COFFEE SHOP DENGAN STOK
        // ==========================================
        
        // --- COFFEE & ESPRESSO (Category ID 1) ---
        Product::create(['name' => 'Signature Blend Espresso', 'price' => 18000, 'stock' => 50, 'image' => null, 'is_available' => true, 'category_id' => 1]);
        Product::create(['name' => 'Hot Caffe Latte', 'price' => 30000, 'stock' => 45, 'image' => null, 'is_available' => true, 'category_id' => 1]);
        Product::create(['name' => 'Iced Kopi Gula Aren', 'price' => 27000, 'stock' => 60, 'image' => null, 'is_available' => true, 'category_id' => 1]);
        Product::create(['name' => 'Caramel Macchiato', 'price' => 35000, 'stock' => 35, 'image' => null, 'is_available' => true, 'category_id' => 1]);
        Product::create(['name' => 'Cold Brew Black', 'price' => 32000, 'stock' => 40, 'image' => null, 'is_available' => true, 'category_id' => 1]);

        // --- NON-COFFEE & TEA (Category ID 2) ---
        Product::create(['name' => 'Matcha Latte Premium', 'price' => 38000, 'stock' => 30, 'image' => null, 'is_available' => true, 'category_id' => 2]);
        Product::create(['name' => 'Red Velvet Iced', 'price' => 35000, 'stock' => 30, 'image' => null, 'is_available' => true, 'category_id' => 2]);
        Product::create(['name' => 'Classic Lemon Tea', 'price' => 20000, 'stock' => 50, 'image' => null, 'is_available' => true, 'category_id' => 2]);
        Product::create(['name' => 'Sparkling Strawberry Soda', 'price' => 28000, 'stock' => 25, 'image' => null, 'is_available' => true, 'category_id' => 2]);

        // --- MAIN COURSE (Category ID 3) ---
        Product::create(['name' => 'Nasi Goreng Kampung', 'price' => 45000, 'stock' => 20, 'image' => null, 'is_available' => true, 'category_id' => 3]);
        Product::create(['name' => 'Chicken Katsu Curry Rice', 'price' => 55000, 'stock' => 15, 'image' => null, 'is_available' => true, 'category_id' => 3]);
        
        // --- SNACKS & APPETIZERS (Category ID 4) ---
        Product::create(['name' => 'Truffle Fries', 'price' => 38000, 'stock' => 40, 'image' => null, 'is_available' => true, 'category_id' => 4]);
        Product::create(['name' => 'Cinnamon Churros', 'price' => 28000, 'stock' => 30, 'image' => null, 'is_available' => true, 'category_id' => 4]);
        Product::create(['name' => 'Slice Chocolate Cake', 'price' => 40000, 'stock' => 25, 'image' => null, 'is_available' => true, 'category_id' => 4]);

        // --- Contoh Produk Habis (Stok 0) ---
        Product::create(['name' => 'Speciality Single Origin', 'price' => 45000, 'stock' => 0, 'image' => null, 'is_available' => false, 'category_id' => 1]);
    }
}