<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tomorrow Coffe - Cafe Laravel')</title>
    {{-- Menggunakan cdn.tailwindcss.com untuk kemudahan --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        {{-- Mempertahankan font Inter --}}
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    {{-- Menambahkan warna custom untuk Teal/Cyan agar lebih mudah diakses --}}
                    colors: {
                        'brand-primary': {
                            500: '#06b6d4', // cyan-500
                            600: '#0891b2', // cyan-600
                        },
                    }
                }
            }
        }
    </script>
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Alpine.js + Plugin Persist --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #f7f9fb; /* Warna latar belakang yang sangat terang/krem muda */
        }

        {{-- Gradient text yang lama dihapus. Jika perlu gradien, gunakan gradien cyan. --}}
        .brand-gradient {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); /* Cyan gradient */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        {{-- Glass effect yang lama dihapus, ganti dengan shadow dan bg solid white --}}
    </style>
</head>

{{-- Background diganti ke warna netral yang sangat terang --}}
<body class="bg-gray-50 min-h-screen" x-data="globalState">

    {{-- NAVBAR MODERN (Menggunakan warna Putih bersih dan shadow elegan) --}}
    <nav class="bg-white sticky top-0 z-50 border-b border-gray-100 shadow-lg">
        <div class="max-w-3xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('order.index') }}" class="flex items-center gap-2 group">
                <div
                    {{-- Logo menggunakan brand color (Cyan) --}}
                    class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-200 group-hover:scale-105 transition-transform">
                    <span class="text-white text-lg font-black">☕</span>
                </div>
                <div class="hidden sm:block">
                    {{-- Menggunakan brand gradient baru --}}
                    <span class="font-black text-xl brand-gradient">Tomorrow Coffe</span>
                    <span class="block text-[10px] text-gray-500 -mt-1 font-medium">Kopi Enak</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                @auth
                    {{-- Profil Pengguna --}}
                    <div class="hidden sm:flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200">
                        <div
                            class="w-6 h-6 bg-cyan-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                    </div>

                    {{-- Riwayat --}}
                    <a href="{{ route('order.history') }}"
                        class="p-2 bg-white hover:bg-cyan-50 rounded-xl border border-gray-200 shadow-md transition-all hover:shadow-lg hover:scale-[1.02]"
                        title="Riwayat">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="p-2 bg-white hover:bg-red-50 rounded-xl border border-gray-200 shadow-md transition-all hover:shadow-lg hover:scale-[1.02] group"
                            title="Logout">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-red-500 transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                @else
                    {{-- Login Button (Warna Cyan) --}}
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-cyan-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-cyan-300/50 hover:bg-cyan-700 hover:shadow-cyan-400/50 hover:scale-105 transition-all">
                        Login ✨
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ISI HALAMAN --}}
    <main>
        @yield('content')
    </main>
    

    {{-- Script Global --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalState', () => ({
                // Menggunakan persist untuk menyimpan keranjang di local storage
                cart: Alpine.$persist([]).as('shopping_cart'),

                get totalPrice() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },
                get totalQty() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                }
            }))
        })
    </script>
    @stack('scripts')
</body>

</html>