<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        $cart = $user->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Empty cart'
            ], 400);
        }

        return DB::transaction(function () use ($cart, $user) {
            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $cart->items()->delete();

            return response()->json([
                'message' => 'Order created successfuly',
                'order' => $order->load('items.product')
            ]);
        });
    }

    public function index(Request $request)
    {
        return $request->user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->get();
    }
}
