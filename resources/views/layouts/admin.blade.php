<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cafe System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Gaya tambahan untuk menghilangkan scrollbar di sidebar, menjaga kebersihan */
        .sidebar-menu::-webkit-scrollbar {
            display: none;
        }
        .sidebar-menu {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- ======================== --}}
        {{-- SIDEBAR ELEGANT (TEMA INDIGO/VIOLET BARU) --}}
        {{-- ======================== --}}
        <aside
            class="w-64 bg-white text-gray-700 flex flex-col shadow-lg border-r border-gray-100 flex-shrink-0 transition-all duration-300">
            {{-- Brand --}}
            <div class="h-20 flex items-center gap-3 px-6 border-b border-gray-100 bg-white">
                <div
                    {{-- PERUBAHAN WARNA: Logo diubah menjadi Indigo-600 --}}
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xl">
                    A
                </div>
                <div>
                    <h1 class="text-gray-900 font-extrabold text-xl tracking-wide">ADMIN</h1>
                    <p class="text-xs text-gray-400">Management</p>
                </div>
            </div>

            {{-- Menu --}}
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2 sidebar-menu">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Navigasi</p>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    {{-- PERUBAHAN WARNA: Active state Indigo Muda --}}
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all 
                    {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'hover:bg-gray-50 hover:text-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="mt-4 mb-2 border-t border-gray-100"></div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Master Data</p>

                {{-- Daftar Menu --}}
                <a href="{{ route('admin.products.index') }}"
                    {{-- PERUBAHAN WARNA: Active state Indigo Muda --}}
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all 
                    {{ request()->routeIs('admin.products.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'hover:bg-gray-50 hover:text-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span>Daftar Menu</span>
                </a>

                {{-- Submenu Racik Menu Baru (Dibuat lebih flat/minimal) --}}
                <a href="{{ route('admin.products.create') }}"
                    {{-- PERUBAHAN WARNA: Hover text Indigo --}}
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 text-sm ml-4 text-gray-500 hover:text-indigo-600">
                    <span class="w-2 h-2 rounded-full bg-gray-300 ml-1"></span>
                    <span>Racik Menu Baru</span>
                </a>

                {{-- Kategori --}}
                <a href="{{ route('admin.categories.index') }}"
                    {{-- PERUBAHAN WARNA: Active state Indigo Muda --}}
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all 
                    {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'hover:bg-gray-50 hover:text-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <span>Kategori</span>
                </a>

                <div class="mt-4 mb-2 border-t border-gray-100"></div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"></p>

                {{-- Pengeluaran --}}
                @if (config('features.pengeluaran_admin'))
                    <a href="{{ route('admin.expenses.index') }}"
                        {{-- PERUBAHAN WARNA: Active state Indigo Muda --}}
                        class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all 
                        {{ request()->routeIs('admin.expenses.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'hover:bg-gray-50 hover:text-gray-800' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>Pengeluaran</span>
                    </a>
                @endif

                {{-- Laporan Keuangan --}}
                @if (config('features.laporan_keuangan'))
                    <a href="{{ route('admin.finance.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all 
                        {{ request()->routeIs('admin.finance.*') ? 'bg-amber-700 text-amber-50 font-bold shadow-lg shadow-amber-900/30' : 'hover:bg-amber-700/50 hover:text-amber-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span>Laporan Keuangan</span>
                    </a>
                @endif
            </div>

            {{-- User --}}
            <div class="p-4 border-t border-gray-200 bg-gradient-to-b from-gray-50 to-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        ☕
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-medium text-cyan-600">Kasir</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-400 p-2 rounded-lg hover:bg-cyan-100 hover:text-cyan-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ======================== --}}
        {{-- KONTEN UTAMA (KANAN) --}}
        {{-- ======================== --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>