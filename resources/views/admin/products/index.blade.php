@extends('layouts.admin')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">☕ Daftar Produk</h1>
            <p class="text-gray-500 mt-1">Kelola produk dan stok.</p>
        </div>
        {{-- Tombol Tambah Produk diubah ke Cyan --}}
        <a href="{{ route('admin.products.create') }}"
            class="bg-cyan-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-cyan-600/30 hover:bg-cyan-700 hover:scale-105 transition transform flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-800 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- TAB KATEGORI (Ganti Warna Amber ke Cyan) --}}
    <div x-data="{ activeTab: 'all' }" class="space-y-6">
        {{-- Tab Navigation --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex flex-wrap gap-2">
            <button @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                <span>📋</span> Semua
                <span class="bg-white/20 px-2 py-0.5 rounded-lg text-xs">{{ $products->count() }}</span>
            </button>

            @foreach($categories as $category)
                <button @click="activeTab = '{{ $category->id }}'"
                    :class="activeTab === '{{ $category->id }}' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <span>{{ $category->icon ?? '📁' }}</span> {{ $category->name }}
                    <span
                        class="bg-white/20 px-2 py-0.5 rounded-lg text-xs">{{ $products->where('category_id', $category->id)->count() }}</span>
                </button>
            @endforeach

            <button @click="activeTab = 'uncategorized'"
                :class="activeTab === 'uncategorized' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                <span>📦</span> Tanpa Kategori
                <span
                    class="bg-white/20 px-2 py-0.5 rounded-lg text-xs">{{ $products->whereNull('category_id')->count() }}</span>
            </button>
        </div>

        {{-- GRID PRODUK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div x-show="activeTab === 'all' || activeTab === '{{ $product->category_id ?? 'uncategorized' }}'"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 group flex flex-col h-full">

                    {{-- GAMBAR & HARGA --}}
                    <div class="h-48 bg-gray-100 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                                <span class="text-4xl">☕</span>
                                <span class="text-xs font-bold mt-2 uppercase tracking-wide">No Image</span>
                            </div>
                        @endif

                        {{-- Badge Harga & Stok --}}
                        <div class="absolute top-3 right-3 flex gap-2">
                            {{-- Badge Harga diubah ke Cyan --}}
                            <div
                                class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-black text-cyan-600 shadow-sm border border-cyan-100">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            <div
                                class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-bold {{ $product->stock > 0 ? 'text-green-600 border border-green-100' : 'text-red-600 border border-red-100' }} shadow-sm">
                                {{ $product->stock }} pcs
                            </div>
                        </div>
                    </div>

                    {{-- INFO PRODUK --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">{{ $product->name }}</h3>
                        </div>
                        @if($product->category)
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500 mt-1">
                                <span>{{ $product->category->icon ?? '📁' }}</span>
                                {{ $product->category->name }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs text-gray-400 mt-1">
                                <span>📦</span> Tanpa Kategori
                            </span>
                        @endif

                        {{-- Ringkasan Detail Produk --}}
                        <div class="mt-4 bg-gray-50 rounded-lg p-3 border border-gray-100 flex-1">
                            <p class="text-sm font-bold text-gray-700 mb-2">
                                {{-- Harga diubah ke Cyan --}}
                                Harga: <span class="text-cyan-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Stok: <span
                                    class="font-bold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $product->stock }}
                                    pcs</span>
                            </p>
                        </div>
                    </div>

                    {{-- FOOTER CARD (HAPUS/EDIT) --}}
                    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                        {{-- Edit --}}
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                            class="p-2 text-gray-400 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg transition"
                            title="Edit Menu">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>

                        {{-- Delete Action --}}
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                            onsubmit="return confirm('Hapus menu {{ $product->name }}?');" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Hapus Menu">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                {{-- EMPTY STATE (Tombol diubah ke Cyan) --}}
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                        <span class="text-6xl">📋</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Belum Ada Menu</h3>
                    <p class="text-gray-500 mb-6">Mulai racik menu kafe pertamamu sekarang.</p>
                    <a href="{{ route('admin.products.create') }}"
                        class="bg-cyan-600 text-white px-6 py-3 rounded-xl font-bold shadow hover:bg-cyan-700 transition">
                        + Buat Menu Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>

@endsection