<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->where('is_available', true)->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('order.index', compact('products', 'categories'));
    }

    public function checkout()
    {
        return view('order.checkout');
    }

    // 🔥 FIXED STORE - Support untuk Guest & Auth Users
    public function store(Request $request)
    {
        $request->validate([
            'cart'           => 'required|array|min:1',
            'cart.*.id'      => 'required|exists:products,id',
            'cart.*.qty'     => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            'cash_amount'    => 'nullable|integer|min:0',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {

                $user = Auth::user();
                $cartItems = $request->cart;
                $totalPrice = 0;

                // 1️⃣ Hitung total & cek stok
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    if (!$product || !$product->is_available) {
                        throw new \Exception("Produk " . ($product ? $product->name : 'tidak diketahui') . " tidak tersedia");
                    }

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} tidak cukup (tersedia: {$product->stock})");
                    }

                    $totalPrice += $product->price * $item['qty'];
                }

                // 2️⃣ Generate nomor antrean
                $today = Carbon::today();
                $lastOrder = Order::whereDate('created_at', $today)->latest()->first();
                $number = $lastOrder ? ((int) substr($lastOrder->queue_number, 2)) + 1 : 1;
                $queueCode = 'A-' . str_pad($number, 3, '0', STR_PAD_LEFT);

                // 3️⃣ Hitung kembalian untuk pembayaran cash
                $cashAmount = $request->cash_amount ?? 0;
                $change = $cashAmount - $totalPrice;

                // 3️⃣ SIMPAN ORDER (Support guest & auth user)
                $order = Order::create([
                    'user_id'        => $user?->id, // null untuk guest
                    'customer_name'  => $user?->name ?? 'Guest', // 'Guest' jika tidak login
                    'queue_number'   => $queueCode,
                    'total_price'    => $totalPrice,
                    'payment_method' => $request->payment_method ?? 'cash',
                    'cash_amount'    => $cashAmount,
                    'change'         => $change,
                    'status'         => 'paid',
                ]);

                // 4️⃣ Simpan item & kurangi stok
                foreach ($cartItems as $item) {
                    $product = Product::find($item['id']);

                    $order->items()->create([
                        'product_id' => $product->id,
                        'qty'        => $item['qty'],
                        'price'      => $product->price,
                    ]);

                    $product->decrement('stock', $item['qty']);
                }

                return $order;
            });

            return response()->json([
                'status' => 'success',
                'redirect_url' => route('order.show', $order->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function history() { $orders = Order::where('user_id', Auth::id()) ->orderBy('created_at', 'desc') ->get(); return view('order.history', compact('orders')); }
    
    public function show($id)
    {
        // Jika user login, ambil order milik user; jika guest, ambil order apapun
        if (Auth::check()) {
            $order = Order::with('items.product')
                ->where('user_id', Auth::id())
                ->findOrFail($id);
        } else {
            // Guest users - ambil berdasarkan queue_number dari URL
            $order = Order::with('items.product')
                ->findOrFail($id);
        }

        return view('order.success', compact('order'));
    }
}
