@extends('layouts.pos')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="min-h-screen bg-amber-50 p-6" x-data="{ filter: 'all' }">
        <div class="max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-black text-amber-900">📜 Riwayat Transaksi</h1>
                        <p class="text-amber-700 text-sm">Semua transaksi yang sudah checkout</p>
                    </div>
                    <a href="{{ route('order.index') }}" class="px-5 py-2.5 bg-amber-700 hover:bg-amber-800 text-amber-50 rounded-lg font-bold transition">
                        ← Kembali
                    </a>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex gap-2 flex-wrap">
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-amber-700 text-amber-50' : 'bg-white text-amber-900 border-2 border-amber-700 hover:bg-amber-50'" class="px-4 py-2 rounded-lg font-bold text-sm transition">
                        Semua
                    </button>
                    <button @click="filter = 'paid'" :class="filter === 'paid' ? 'bg-green-600 text-white' : 'bg-white text-green-600 border-2 border-green-600 hover:bg-green-50'" class="px-4 py-2 rounded-lg font-bold text-sm transition">
                        ✓ Lunas
                    </button>
                    <button @click="filter = 'pending'" :class="filter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-white text-yellow-600 border-2 border-yellow-600 hover:bg-yellow-50'" class="px-4 py-2 rounded-lg font-bold text-sm transition">
                        ⏳ Pending
                    </button>
                </div>
            </div>

            {{-- Transactions List --}}
            <div class="space-y-4">
                @forelse($orders as $order)
                    <div x-show="filter === 'all' || filter === '{{ $order->status }}'" x-transition
                        class="bg-white rounded-lg border-2 border-amber-200 shadow-md hover:shadow-lg transition overflow-hidden">
                        
                        {{-- Main Content --}}
                        <div class="p-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                            {{-- 1. No Transaksi --}}
                            <div>
                                <p class="text-xs text-amber-700 font-bold uppercase">No. Antrean</p>
                                <p class="text-2xl font-black text-amber-900">{{ $order->queue_number }}</p>
                            </div>

                            {{-- 2. Waktu --}}
                            <div>
                                <p class="text-xs text-amber-700 font-bold uppercase">Waktu</p>
                                <p class="font-bold text-amber-900">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-amber-700">{{ $order->created_at->format('H:i') }}</p>
                            </div>

                            {{-- 3. Customer & Items --}}
                            <div>
                                <p class="text-xs text-amber-700 font-bold uppercase">Nama</p>
                                <p class="font-bold text-amber-900">{{ $order->customer_name ?? 'Guest' }}</p>
                                <p class="text-xs text-amber-700 mt-1">{{ $order->items->count() }} item</p>
                            </div>

                            {{-- 4. Total --}}
                            <div>
                                <p class="text-xs text-amber-700 font-bold uppercase">Total</p>
                                <p class="text-xl font-black text-amber-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>

                            {{-- 5. Status & Actions --}}
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2
                                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $order->status === 'paid' ? '✓ LUNAS' : '⏳ PENDING' }}
                                </span>
                                
                                <div class="mt-2 space-y-1">
                                    <a href="{{ route('order.show', $order->id) }}" class="block px-3 py-1.5 bg-amber-700 hover:bg-amber-800 text-amber-50 text-xs font-bold rounded-lg transition">
                                        Lihat Struk
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Items Detail (Collapsible) --}}
                        <div x-data="{ open: false }" class="bg-amber-50 border-t-2 border-amber-200">
                            <button @click="open = !open" class="w-full px-4 py-2 text-left font-bold text-amber-900 hover:bg-amber-100 transition flex justify-between items-center text-sm">
                                <span>📋 Daftar Item</span>
                                <span x-show="!open">▼</span>
                                <span x-show="open" x-cloak>▲</span>
                            </button>
                            <div x-show="open" x-cloak class="px-4 pb-3 space-y-2 text-sm">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between text-amber-900">
                                        <span class="font-semibold">{{ $item->qty }}x {{ $item->product->name ?? 'Produk' }}</span>
                                        <span>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="text-center py-16 bg-white rounded-lg border-2 border-dashed border-amber-300">
                        <p class="text-5xl mb-3">🧾</p>
                        <h3 class="text-xl font-bold text-amber-900">Tidak Ada Riwayat Transaksi</h3>
                        <p class="text-amber-700 mt-2">Mulai checkout untuk membuat transaksi</p>
                        <a href="{{ route('order.index') }}" class="inline-block mt-4 px-6 py-3 bg-amber-700 hover:bg-amber-800 text-amber-50 rounded-lg font-bold transition">
                            Mulai Transaksi ☕
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection