<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartService
{
    public function getCart($user)
    {
        return Cart::with('items.product')
            ->firstOrCreate(['user_id' => $user->id]);
    }

    public function addProduct($user, $productId, $quantity = 1)
    {
        $cart = $this->getCart($user);

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $this->getCart($user);
    }

    public function removeProduct($user, $productId)
    {
        $cart = $this->getCart($user);

        $cart->items()->where('product_id', $productId)->delete();

        return $this->getCart($user);
    }

    public function updateQuantity($user, $productId, $quantity)
    {
        $cart = $this->getCart($user);

        $product = $cart->items()->where('product_id', $productId)->firstOrFail();

        $item->update(['quantity' => $quantity]);

        return $this->getCart($user);
    }

    public function getTotal($user)
    {
        $cart = $this->getCart($user);

        return $cart->items()->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }
}