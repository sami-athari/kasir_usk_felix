@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col items-center justify-center text-center gap-2 mb-8">
        <h1 class="text-2xl font-black text-gray-800">☕ Tambah Produk</h1>
        <p class="text-gray-500 text-sm">Tambahkan produk baru dengan stok.</p>
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="w-full max-w-2xl">
            @csrf

            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 space-y-6">

            {{-- Nama Produk --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                {{-- Focus Ring diubah ke Cyan --}}
                <input type="text" name="name"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition"
                    placeholder="Contoh: Kopi Susu Aren" value="{{ old('name') }}" required>
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Harga Jual (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-400 font-medium">Rp</span>
                    {{-- Focus Ring diubah ke Cyan --}}
                    <input type="number" name="price"
                        class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition"
                        placeholder="25000" value="{{ old('price') }}" required>
                </div>
            </div>

            {{-- Stok --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Stok Produk (pcs)</label>
                {{-- Focus Ring diubah ke Cyan --}}
                <input type="number" name="stock" min="0"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition"
                    placeholder="100" value="{{ old('stock', 0) }}" required>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                {{-- Focus Ring diubah ke Cyan --}}
                <select name="category_id"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Foto (Input File diubah ke Cyan) --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                <input type="file" name="image" accept="image/*"
                    {{-- File Input: background diubah ke Cyan --}}
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 cursor-pointer border border-gray-200 rounded-xl">
                <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Max: 2MB.</p>
            </div>

            {{-- Tombol Submit (Diubah ke Cyan) --}}
            <div class="flex gap-3 pt-4">
                <button type="submit"
                    {{-- Warna Tombol Submit diubah ke Cyan --}}
                    class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg shadow-cyan-300/50">
                    ✨ Simpan Produk
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