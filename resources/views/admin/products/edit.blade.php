@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col items-center justify-center text-center gap-2 mb-8">
        <h1 class="text-2xl font-black text-gray-800">✏️ Edit Produk</h1>
        {{-- Nama Produk yang diedit tetap dipertahankan untuk konteks --}}
        <p class="text-gray-500 text-sm">Ubah data produk <span class="font-bold text-cyan-600">{{ $product->name }}</span></p>
    </div>

    {{-- Error Message --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT --}}
    <div class="flex justify-center">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="w-full max-w-2xl">
            @csrf
            @method('PUT')

            {{-- Shadow lebih besar untuk kesan modern --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 space-y-6">

            {{-- Nama Produk --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                {{-- Focus Ring diubah ke Cyan --}}
                <input type="text" name="name"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition"
                    placeholder="Contoh: Kopi Susu Aren" value="{{ old('name', $product->name) }}" required>
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Harga Jual (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-400 font-medium">Rp</span>
                    {{-- Focus Ring diubah ke Cyan --}}
                    <input type="number" name="price"
                        class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition"
                        placeholder="25000" value="{{ old('price', $product->price) }}" required>
                </div>
            </div>

            {{-- Stok --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Stok Produk (pcs)</label>
                {{-- Focus Ring diubah ke Cyan --}}
                <input type="number" name="stock" min="0"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition"
                    placeholder="100" value="{{ old('stock', $product->stock) }}" required>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                {{-- Focus Ring diubah ke Cyan --}}
                <select name="category_id"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Foto (Input File diubah ke Cyan) --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                @if($product->image)
                    {{-- Style foto saat ini sedikit disempurnakan --}}
                    <div class="mb-3 relative group overflow-hidden rounded-xl border-2 border-gray-100">
                        <img src="{{ asset('storage/' . $product->image) }}"
                            class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                        <span
                            class="absolute top-0 right-0 bg-black/50 text-white text-xs px-3 py-1.5 font-semibold rounded-bl-xl">
                            Foto Saat Ini
                        </span>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    {{-- File Input: background diubah ke Cyan --}}
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 cursor-pointer border border-gray-200 rounded-xl">
                <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            {{-- Tombol Submit (Diubah ke Cyan) --}}
            <div class="flex gap-3 pt-4">
                <button type="submit"
                    {{-- Warna Tombol Submit diubah ke Cyan --}}
                    class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg shadow-cyan-300/50">
                    ✨ Update Produk
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition text-center">
                    Batal
                </a>
            </div>
        </div>
        </form>
    </div>

@endsection