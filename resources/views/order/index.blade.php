@extends('layouts.pos')

@section('title', 'Tomorrow Coffee')

@section('content')
    <div class="flex h-screen bg-cyan-50" x-data="posSystem()" @init="init()">
        
        {{-- MAIN CONTENT: Product Catalog --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{-- Header --}}
            <div class="mb-8 border-b border-cyan-200 pb-4">
                <h1 class="text-3xl font-black text-cyan-900 flex items-center gap-2">
                    <span class="text-4xl">☕</span> Tomorrow Coffee
                </h1>
                <p class="text-cyan-700 text-sm"></p>
            </div>

            {{-- Search & Category --}}
            <div class="mb-8 space-y-4">
                {{-- Search --}}
                <div class="relative shadow-md rounded-lg">
                    <input type="text" x-model="searchQuery" placeholder="Cari menu..."
                        class="w-full pl-12 pr-4 py-3 bg-white border border-cyan-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-4 focus:ring-cyan-200 focus:border-cyan-600 transition duration-150">
                    <svg class="w-5 h-5 text-cyan-500 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                {{-- Category Tabs --}}
                <div class="flex gap-3 overflow-x-auto pb-2">
                    <button @click="activeCategory = 'all'"
                        :class="activeCategory === 'all' ? 'bg-cyan-700 text-white shadow-lg' : 'bg-white text-cyan-900 border border-cyan-300 hover:bg-cyan-100'"
                        class="px-5 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition duration-200 ease-in-out">
                        🍵 Semua
                    </button>
                    @foreach($categories as $category)
                        <button @click="activeCategory = '{{ $category->id }}'"
                            :class="activeCategory === '{{ $category->id }}' ? 'bg-cyan-700 text-white shadow-lg' : 'bg-white text-cyan-900 border border-cyan-300 hover:bg-cyan-100'"
                            class="px-5 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition duration-200 ease-in-out">
                            {{ $category->icon ?? '📁' }} {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($products as $product)
                    @php
                        $isAvailable = $product->is_available && $product->stock > 0;
                    @endphp
                    <div x-show="isVisible('{{ addslashes($product->name) }}', {{ $product->category_id ?? 'null' }})"
                        {{-- Card product: Menambah shadow dan hover effect --}}
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl border border-cyan-100 overflow-hidden transition duration-300 {{ !$isAvailable ? 'opacity-60 grayscale' : 'cursor-pointer' }}"
                        @click="{{ $isAvailable ? "addToCart($product->id, '".addslashes($product->name)."', $product->price, $product->stock)" : "Swal.fire({icon: 'error', title: 'Stok Habis', text: 'Produk ini sedang tidak tersedia', confirmButtonColor: '#0891b2'})" }}">

                        {{-- Image --}}
                        <div class="h-28 bg-cyan-100 relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl">🖼️</div>
                            @endif
                            @if(!$isAvailable)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">HABIS</span>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-3">
                            <h3 class="font-extrabold text-cyan-900 text-sm line-clamp-2 mb-1">{{ $product->name }}</h3>
                            <p class="text-cyan-600 font-bold text-base">
                                Rp <span class="text-cyan-800">{{ number_format($product->price, 0, ',', '.') }}</span>
                            </p>
                            
                            @if($isAvailable)
                                <button
                                    class="w-full mt-2 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-bold text-xs transition duration-200 shadow-md">
                                    + Tambah Cepat
                                </button>
                            @else
                                <button disabled class="w-full mt-2 py-1.5 bg-gray-300 text-gray-500 rounded-lg font-bold text-xs cursor-not-allowed">
                                    HABIS
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SIDEBAR: Cart/Keranjang --}}
        <div class="w-[340px] bg-white border-l-4 border-cyan-700 shadow-2xl flex flex-col overflow-hidden">
            {{-- Header Sidebar --}}
            <div class="bg-gradient-to-r from-cyan-800 to-cyan-700 text-white p-5 shadow-lg">
                <h2 class="font-extrabold text-2xl flex items-center">
                    🛒 Keranjang <span class="ml-2 px-3 py-1 bg-cyan-900/50 rounded-full text-sm" x-text="totalQty"></span>
                </h2>
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-for="(item, idx) in cart" :key="idx">
                    <div class="bg-white p-3 rounded-xl shadow border border-cyan-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-cyan-900 text-base" x-text="item.name"></p>
                                <p class="text-xs text-cyan-600">Rp <span x-text="Number(item.price).toLocaleString('id-ID')"></span></p>
                            </div>
                            <button @click="removeFromCart(idx)" class="text-red-500 hover:text-red-700 text-sm font-bold p-1 rounded-full hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        {{-- Quantity Control --}}
                        <div class="flex items-center gap-2 mt-2">
                            <button @click="item.qty = Math.max(1, item.qty - 1); updateLocalStorage()" 
                                class="w-8 h-8 bg-cyan-100 text-cyan-700 border border-cyan-300 rounded-full text-sm font-extrabold hover:bg-cyan-200 transition duration-150">
                                −
                            </button>
                            <input type="number" x-model.number="item.qty" 
                                @input="item.qty = Math.min(item.maxStock, item.qty); item.qty = Math.max(1, item.qty); updateLocalStorage()" 
                                :max="item.maxStock" 
                                class="flex-1 text-center text-sm font-extrabold border-2 border-cyan-300 rounded-lg px-1 py-1 focus:border-cyan-600 focus:ring-1 focus:ring-cyan-600 transition">
                            <button @click="item.qty = Math.min(item.maxStock, item.qty + 1); updateLocalStorage()" 
                                class="w-8 h-8 bg-cyan-100 text-cyan-700 border border-cyan-300 rounded-full text-sm font-extrabold hover:bg-cyan-200 transition duration-150">
                                +
                            </button>
                        </div>

                        {{-- Subtotal --}}
                        <p class="text-right font-black text-cyan-900 mt-2 text-md">
                            Rp <span x-text="(Number(item.qty) * Number(item.price)).toLocaleString('id-ID')"></span>
                        </p>
                    </div>
                </template>

                {{-- Empty Cart Message --}}
                <div x-show="cart.length === 0" class="text-center py-12 text-cyan-700">
                    <p class="text-5xl mb-3">☕</p>
                    <p class="font-extrabold text-lg">Keranjang Kosong</p>
                    <p class="text-sm text-cyan-600">Klik produk untuk mulai bertransaksi.</p>
                </div>
            </div>

            {{-- Summary & Checkout --}}
            <div class="border-t-4 border-cyan-600 bg-cyan-50 p-5 space-y-4 shadow-[0_-5px_15px_rgba(0,0,0,0.1)]">
                <div class="space-y-2 text-base">
                    <div class="flex justify-between text-cyan-800 font-semibold">
                        <span>Total Items:</span>
                        <span x-text="totalQty"></span>
                    </div>
                    <div class="flex justify-between font-black text-2xl text-cyan-900 border-t-2 border-cyan-300 pt-3">
                        <span>GRAND TOTAL:</span>
                        <span>Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></span>
                    </div>
                </div>

                {{-- Checkout Button --}}
                <button @click="checkout()" :disabled="cart.length === 0"
                    class="w-full py-4 bg-cyan-700 hover:bg-cyan-800 text-white rounded-xl font-black text-lg shadow-xl shadow-cyan-500/30 disabled:opacity-50 disabled:bg-gray-400 disabled:shadow-none transition duration-300">
                    💳 LANJUT KE PEMBAYARAN
                </button>

                {{-- History Button --}}
                <a href="{{ route('order.history') }}" class="block w-full py-2.5 text-center bg-white border border-cyan-600 text-cyan-900 rounded-xl font-bold hover:bg-cyan-100 transition text-sm">
                    📜 Riwayat Transaksi
                </a>
            </div>
        </div>

        {{-- Login Required Modal --}}
        <div x-show="showLoginModal" x-transition.opacity.duration.300 class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 border-4 border-amber-500 shadow-2xl text-center">
                <div class="text-5xl mb-4">🔐</div>
                <h3 class="text-2xl font-black text-cyan-900 mb-3">Harus Login Dulu!</h3>
                <p class="text-gray-600 mb-6">Untuk melanjutkan pembayaran, silakan login terlebih dahulu.</p>
                
                <div class="space-y-3">
                    <a href="{{ route('login') }}" class="block w-full py-3 bg-cyan-700 hover:bg-cyan-800 text-white rounded-lg font-bold transition">
                        🔓 Login
                    </a>
                    <a href="{{ route('register') }}" class="block w-full py-3 bg-white border-2 border-cyan-700 text-cyan-900 rounded-lg font-bold hover:bg-cyan-50 transition">
                        📝 Daftar
                    </a>
                    <button @click="showLoginModal = false" class="w-full py-2 text-gray-600 font-bold hover:text-gray-800 transition">
                        Lanjut Belanja
                    </button>
                </div>
            </div>
        </div>

        {{-- Checkout Confirmation Modal --}}
        <div x-show="showCheckoutModal" x-transition.opacity.duration.300 class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
            <div @click.outside="showCheckoutModal = false" class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 border-4 border-cyan-700 shadow-2xl">
                <h3 class="text-2xl font-black text-cyan-900 mb-5 text-center">Konfirmasi Pembayaran</h3>
                
                <div class="bg-cyan-50 p-5 rounded-lg mb-6 border-2 border-cyan-300">
                    <p class="text-lg text-cyan-700 mb-2 font-medium">Total Pesanan (<span x-text="totalQty"></span> Items):</p>
                    <p class="text-3xl font-black text-cyan-900">Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></p>
                </div>

                {{-- Cash Payment Input --}}
                <div class="bg-amber-50 p-4 rounded-lg mb-6 border-2 border-amber-300 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-amber-900 mb-2">💵 Uang Tunai Customer:</label>
                        <input type="number" x-model.number="cashAmount" @input="cashAmount = Math.max(0, cashAmount)" 
                            placeholder="Masukkan jumlah uang" 
                            class="w-full px-4 py-2 border-2 border-amber-300 rounded-lg text-lg font-bold text-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-600">
                    </div>
                    
                    {{-- Change Calculation --}}
                    <div>
                        <label class="block text-sm font-bold text-amber-900 mb-1">🎁 Kembalian:</label>
                        <p class="text-2xl font-black" :class="change >= 0 ? 'text-green-600' : 'text-red-600'">
                            Rp <span x-text="Math.abs(change).toLocaleString('id-ID')"></span>
                        </p>
                        <p x-show="change < 0" class="text-xs text-red-600 font-bold mt-1">
                            ⚠️ Uang kurang Rp <span x-text="Math.abs(change).toLocaleString('id-ID')"></span>
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button @click="showCheckoutModal = false" :disabled="isSubmitting" class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition disabled:opacity-60">
                        BATAL
                    </button>
                    <button @click="submitOrder()" :disabled="isSubmitting || change < 0 || cashAmount === 0" class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">✓ OK BAYAR</span>
                        <span x-show="isSubmitting" x-cloak>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function posSystem() {
            return {
                activeCategory: 'all',
                searchQuery: '',
                showCheckoutModal: false,
                showLoginModal: false,
                isSubmitting: false,
                cashAmount: 0,
                isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                cart: [],
                products: @json($products->map(fn($p) => ['id' => $p->id, 'name' => strtolower($p->name), 'category_id' => $p->category_id])),

                init() {
                    this.loadCart();
                    // Sync cart setiap kali page fokus/diubah dari checkout
                    window.addEventListener('focus', this.loadCart.bind(this));
                },
                
                loadCart() {
                    const saved = localStorage.getItem('shopping_cart');
                    if (saved) {
                        try {
                            this.cart = JSON.parse(saved);
                            // Cleanup: Filter item dengan qty < 1 saat memuat
                            this.cart = this.cart.filter(i => Number(i.qty) >= 1); 
                        } catch (e) {
                            console.error("Error loading/syncing cart from localStorage", e);
                            this.cart = [];
                        }
                    }
                },
                
                updateLocalStorage() {
                    // Hanya simpan item dengan Qty > 0
                    const cleanCart = this.cart.filter(i => Number(i.qty) >= 1);
                    localStorage.setItem('shopping_cart', JSON.stringify(cleanCart));
                    // Update state cart Alpine setelah local storage diubah
                    this.cart = cleanCart; 
                },

                get totalQty() {
                    return this.cart.reduce((t, i) => t + Number(i.qty), 0);
                },

                get totalPrice() {
                    return this.cart.reduce((t, i) => t + (Number(i.price) * Number(i.qty)), 0);
                },

                get change() {
                    return this.cashAmount - this.totalPrice;
                },

                isVisible(productName, categoryId) {
                    const catId = categoryId ? String(categoryId) : null;
                    const matchCategory = this.activeCategory === 'all' || 
                                        this.activeCategory === catId || 
                                        (this.activeCategory === 'uncategorized' && !catId);
                    const matchSearch = this.searchQuery === '' || productName.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchCategory && matchSearch;
                },

                addToCart(id, name, price, maxStock) {
                    let item = this.cart.find(i => i.id === id);
                    if (item) {
                        if (item.qty >= maxStock) {
                            Swal.fire({icon: 'warning', title: 'Stok Terbatas!', text: 'Maksimal ' + maxStock + ' untuk produk ini', confirmButtonColor: '#0891b2'});
                            return;
                        }
                        item.qty = Number(item.qty) + 1; // Pastikan QTY adalah Number
                    } else {
                        this.cart.push({id, name, price, qty: 1, maxStock});
                    }
                    this.updateLocalStorage();
                },

                removeFromCart(idx) {
                    this.cart.splice(idx, 1);
                    this.updateLocalStorage();
                },

                checkout() {
                    // Clean cart sebelum checkout
                    this.cart = this.cart.filter(i => Number(i.qty) >= 1);
                    this.updateLocalStorage();

                    if (this.cart.length === 0) {
                        Swal.fire({icon: 'warning', title: 'Keranjang Kosong!', text: 'Tambah produk terlebih dahulu', confirmButtonColor: '#0891b2'});
                        return;
                    }

                    // Cek apakah user sudah login
                    if (!this.isLoggedIn) {
                        this.showLoginModal = true;
                        return;
                    }

                    // Reset cash amount & open modal pembayaran
                    this.cashAmount = 0;
                    this.showCheckoutModal = true;
                },

                async submitOrder() {
                    this.isSubmitting = true;

                    try {
                        const res = await fetch("{{ route('order.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                cart: this.cart.map(item => ({
                                    id: item.id,
                                    qty: item.qty
                                })),
                                payment_method: 'cash',
                                cash_amount: this.cashAmount
                            })
                        });

                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Checkout gagal');

                        this.cart = [];
                        localStorage.removeItem('shopping_cart');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Checkout Berhasil!',
                            text: 'Pesanan Anda sedang diproses',
                            confirmButtonColor: '#0891b2'
                        }).then(() => {
                            window.location.href = data.redirect_url || "{{ route('order.index') }}";
                        });

                    } catch (e) {
                        console.error('Error:', e);
                        this.showCheckoutModal = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Gagal',
                            text: e.message || 'Terjadi kesalahan',
                            confirmButtonColor: '#0891b2'
                        });
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
@endpush