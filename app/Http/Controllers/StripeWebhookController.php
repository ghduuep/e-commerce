<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Order;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Mail;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $order = Order::where('stripe_session_id', $session->id)->first();

            if (!$order) {
                return response('Order Not Found', 400);
            }

            if ($order->status === 'paid') {
                return response('Already processed', 200);
            }

            $order->update([
                'status' => 'paid'
            ]);

            $order->load('items.product', 'user');

            Mail::to($order->user->email)
                ->queue(new OrderPaidMail($order));

            foreach ($order->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            $cart = $order->user->cart;

            if ($cart) {
                $cart->items()->delete();
            }
        }

        return response('OK', 200);
    }
}
