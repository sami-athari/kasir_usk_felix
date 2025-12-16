@extends('layouts.pos')

@section('title', 'Pesanan Berhasil')

@section('content')
    {{-- Container Tengah, Fokus pada Simplicity --}}
    <div class="min-h-[calc(100vh-60px)] flex items-center justify-center p-4">
        <div class="w-full max-w-sm">
            
            {{-- Header Konfirmasi Sederhana --}}
            <div class="text-center mb-6">
                {{-- Ikon Success Sederhana (tanpa shadow besar) --}}
                <div class="inline-flex items-center justify-center w-20 h-20 bg-teal-500 rounded-full mb-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-black text-gray-900">Pesanan Berhasil Dibuat!</h1>
                <p class="text-gray-500 text-sm mt-1">Pesanan kamu siap disiapkan.</p>
            </div>

            {{-- Simplified Receipt Card --}}
            <div id="receipt" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                
                {{-- Nomor Antrean --}}
                <div class="bg-cyan-600 px-5 py-4 text-white text-center">
                    <p class="text-sm font-medium opacity-90">Nomor Antrean Kamu</p>
                    <h2 class="text-4xl font-extrabold mt-0.5 tracking-tight">{{ $order->queue_number }}</h2>
                </div>

                {{-- Details --}}
                <div class="p-5 space-y-3">
                    
                    {{-- Detail Item --}}
                    <div class="pb-3 border-b border-gray-100">
                        <p class="font-bold text-gray-700 mb-2">Item Pesanan:</p>
                        <ul class="space-y-1 text-sm text-gray-600">
                            @foreach($order->items as $item)
                                <li class="flex justify-between">
                                    <span class="truncate">{{ $item->qty }}x {{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                    <span>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    {{-- Total Pembayaran --}}
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-bold text-gray-700">Total Bayar</span>
                        <span class="text-2xl font-black text-cyan-600">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Informasi Pembayaran Cash --}}
                    @if($order->payment_method === 'cash' && $order->cash_amount)
                        <div class="space-y-2 pt-3 border-t border-gray-100">
                            <p class="flex justify-between text-sm">
                                <span class="text-gray-600">Uang Tunai:</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="flex justify-between text-sm">
                                <span class="text-gray-600">Kembalian:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($order->change, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    @endif

                    {{-- Informasi Tambahan --}}
                    <div class="text-xs pt-3 border-t border-gray-100 space-y-1">
                        <p class="flex justify-between text-gray-500">
                            <span>Nama:</span>
                            <span class="font-semibold text-gray-700">{{ $order->customer_name ?? 'Pelanggan' }}</span>
                        </p>
                        <p class="flex justify-between text-gray-500">
                            <span>Pembayaran:</span>
                            <span class="font-semibold text-green-600 uppercase">{{ $order->payment_method }}</span>
                        </p>
                    </div>
                </div>

                {{-- Status Bar Sederhana --}}
                <div class="bg-teal-50 px-5 py-3 text-center border-t border-teal-100">
                    <p class="text-teal-700 text-sm">Pembayaran Diterima. Mohon tunggu.</p>
                </div>
            </div>

            {{-- Action Buttons Sederhana --}}
            <div class="mt-6 space-y-3">
                <a href="{{ route('order.index') }}"
                    class="block w-full text-center bg-cyan-600 text-white py-3.5 rounded-xl font-bold hover:bg-cyan-700 transition">
                    Pesan Lagi ☕
                </a>

                <button id="printBtn"
                    class="block w-full text-center bg-white border border-gray-300 text-gray-800 py-3.5 rounded-xl font-bold hover:bg-gray-50 transition"
                    aria-label="Cetak struk">
                    Cetak Struk 🖨️
                </button>
            </div>
            
            <p class="text-center text-gray-400 text-sm mt-4">
                Terimakasih telah berbelanja    .
            </p>
        </div>
    </div>
    
    <style>
        /* CSS untuk mode cetak: Sembunyikan semua kecuali receipt (diperbaiki agar cetak lebih bersih) */
        @media print {
            body { 
                margin: 0; 
                padding: 0; 
            }
            body * { 
                visibility: hidden; 
            }
            #receipt, #receipt * { 
                visibility: visible; 
            }
            #receipt { 
                position: absolute; 
                left: 0; 
                top: 0; 
                width: 100%; 
                margin: 0;
                box-shadow: none !important; 
                border: none !important; 
                border-radius: 0 !important;
            }
            .min-h-\[calc\(100vh-60px\)\], .flex.items-center.justify-center {
                display: block !important;
                min-height: auto !important;
            }
            /* Sembunyikan tombol aksi dan footer saat dicetak */
            .mt-6, .text-center.text-gray-400 {
                display: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('printBtn');
            if (btn) {
                btn.addEventListener('click', function () {
                    window.print();
                });
            }
        });
    </script>
@endsection