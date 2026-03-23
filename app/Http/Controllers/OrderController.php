<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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

        $lineItems = [];

        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => $item->product->price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://localhost:3000/success',
            'cancel_url' => 'http://localhost:3000/cancel',

            'metadata' => [
                'order_id' => $order->id,
            ]
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
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
