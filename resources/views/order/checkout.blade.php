@extends('layouts.pos')

@section('title', 'Checkout - POS')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6 pb-32" x-data="checkoutLogic()" x-init="init()" x-cloak>

    {{-- HEADER --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('order.index') }}" class="p-2 bg-amber-700 text-amber-50 rounded-lg">
            ←
        </a>
        <div>
            <h1 class="text-xl font-black text-amber-900">Konfirmasi Checkout 🧾</h1>
            <p class="text-sm text-amber-700">Review pesanan sebelum OK</p>
        </div>
    </div>

    {{-- ORDER SUMMARY --}}
    <div class="bg-white rounded-lg border-4 border-amber-700 shadow-lg p-6 space-y-4 mb-6">
        <h3 class="font-bold text-lg text-amber-900 mb-4">Daftar Pesanan:</h3>
        
        <template x-for="(item, index) in cart" :key="item.id">
            <div class="flex justify-between items-center bg-amber-50 p-3 rounded-lg">
                <div>
                    <p class="font-bold text-amber-900" x-text="item.name"></p>
                    <p class="text-sm text-amber-700">
                        <span x-text="item.qty"></span> × 
                        Rp <span x-text="Number(item.price).toLocaleString('id-ID')"></span>
                    </p>
                </div>
                {{-- Pastikan item.price dan item.qty dipanggil sebagai Number --}}
                <p class="font-bold text-amber-900">Rp <span x-text="(Number(item.qty) * Number(item.price)).toLocaleString('id-ID')"></span></p>
            </div>
        </template>
        
        <div x-show="isCartEmpty" class="text-center py-6 text-amber-700 font-semibold">
            Keranjang pesanan kosong.
        </div>

        <div class="border-t-2 border-amber-300 pt-4 flex justify-between items-center bg-amber-100 p-4 rounded-lg">
            <span class="font-bold text-lg text-amber-900">TOTAL</span>
            <span class="font-black text-2xl text-amber-900">Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></span>
        </div>
    </div>

    {{-- BUTTON AREA --}}
    <div class="fixed bottom-0 left-0 w-full p-4 bg-amber-50 border-t-4 border-amber-700">
        <div class="max-w-2xl mx-auto space-y-3">
            <button @click="submitOrder()"
                    :disabled="loading || isCartEmpty"
                    class="w-full py-4 rounded-lg bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-black text-lg transition">
                <span x-show="!loading && !isCartEmpty">✓ OK BAYAR CASH</span>
                <span x-show="!loading && isCartEmpty">KERANJANG KOSONG</span>
                <span x-show="loading" x-cloak>Memproses...</span>
            </button>
            <a href="{{ route('order.index') }}" class="block text-center py-2 bg-white border-2 border-amber-700 text-amber-900 rounded-lg font-bold hover:bg-amber-50 transition">
                ← BATAL
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
function checkoutLogic() {
    return {
        loading: false,
        cart: [],

        init() {
            // Ambil data keranjang dari Local Storage saat inisialisasi
            const storedCart = localStorage.getItem('shopping_cart');
            if (storedCart) {
                try {
                    // Penanganan error parsing data
                    this.cart = JSON.parse(storedCart);
                } catch (e) {
                    console.error("Error parsing shopping cart from localStorage, resetting:", e);
                    this.cart = []; // Reset jika ada error parsing
                }
            }
            this.$nextTick(() => {
                console.log("Cart initialized. Items:", this.cart.length, "Empty:", this.isCartEmpty);
            });
        },

        get totalPrice() {
            // Pastikan item.price dan item.qty adalah angka
            return this.cart.reduce((t, i) => t + (Number(i.price) * Number(i.qty)), 0);
        },

        get isCartEmpty() {
            // Keranjang kosong jika array kosong ATAU semua item memiliki qty <= 0
            return this.cart.length === 0 || this.cart.every(item => Number(item.qty) <= 0);
        },

        async submitOrder() {
            if (this.isCartEmpty) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Tambahkan item ke keranjang sebelum checkout',
                    confirmButtonColor: '#92400e'
                });
                return;
            }

            this.loading = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const res = await fetch("{{ route('order.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify({
                        cart: this.cart.map(item => ({
                            ...item,
                            qty: Number(item.qty), // Pastikan QTY adalah number saat dikirim
                            price: Number(item.price)
                        })),
                        payment_method: 'cash'
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || 'Checkout gagal. Silakan coba lagi.');
                }

                this.cart = [];
                localStorage.removeItem('shopping_cart');
                
                await Swal.fire({
                    icon: 'success',
                    title: 'Checkout Berhasil!',
                    text: 'Pesanan Anda sedang diproses',
                    confirmButtonColor: '#92400e',
                    allowOutsideClick: false
                });

                window.location.href = data.redirect_url;

            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Checkout Gagal',
                    text: e.message || String(e),
                    confirmButtonColor: '#92400e'
                });
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection