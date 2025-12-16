<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // Tampilkan Daftar Produk
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    // Tampilkan Form Tambah Produk
    public function create()
    {
        // Ambil semua kategori untuk pilihan
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    // Simpan Produk
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'category_id' => 'nullable|exists:categories,id',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 2. Upload Gambar (Jika ada)
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('products', 'public');
                }

                // 3. Simpan Data Produk
                Product::create([
                    'name'         => $request->name,
                    'price'        => $request->price,
                    'stock'        => $request->stock,
                    'image'        => $imagePath,
                    'is_available' => true,
                    'category_id'  => $request->category_id,
                ]);
            });

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambah!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // Tampilkan Form Edit Produk
    public function edit(Product $product)
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update Produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        try {
            DB::transaction(function () use ($request, $product) {

                // Upload Gambar Baru (Jika ada)
                if ($request->hasFile('image')) {
                    // Hapus gambar lama jika ada
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $imagePath = $request->file('image')->store('products', 'public');
                    $product->image = $imagePath;
                }

                // Update Data Produk
                $product->update([
                    'name'         => $request->name,
                    'price'        => $request->price,
                    'stock'        => $request->stock,
                    'image'        => $product->image ?? $product->image,
                    'category_id'  => $request->category_id,
                ]);
            });

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupdate: ' . $e->getMessage());
        }
    }

    // Hapus Produk
    public function destroy(Product $product)
    {
        try {
            // Hapus gambar jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Hapus produk
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Menu berhasil dihapus! 🗑️');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
