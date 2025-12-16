<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    /**
     * Relasi: Produk milik satu Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Cek apakah stok produk cukup
     * @param int $qty Jumlah produk yang diinginkan (default: 1)
     * @return bool
     */
    public function hasEnoughStock(int $qty = 1): bool
    {
        return $this->stock >= $qty;
    }
}
