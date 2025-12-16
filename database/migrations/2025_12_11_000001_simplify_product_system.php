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
        // 1. Drop constraints dulu
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
        });

        // 2. Tambah kolom stock ke products
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('price'); // Stok produk langsung
        });

        // 3. Hapus tabel resep & bahan
        Schema::dropIfExists('product_ingredient');
        Schema::dropIfExists('ingredients');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });

        // Recreate jika rollback (opsional)
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->string('unit');
            $table->timestamps();
        });

        Schema::create('product_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->integer('amount_needed');
        });
    }
};
