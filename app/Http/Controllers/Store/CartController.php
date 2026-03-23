<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cart\CartService;

class CartController extends Controller
{
    public function __construct(private CartService $service) {}

    public function index(Request $request)
    {
        return $this->service->getCart($request->user());
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        return $this->service->addProduct(
            $request->user(),
            $data['product_id'],
            $data['quantity'] ?? 1
        );
    }

    public function remove(Request $request, $productId)
    {
        return $this->service->removeProduct($request->user(), $productId);
    }

    public function update(Request $request, $productId)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        return $this->service->updateQuantity(
            $request->user(),
            $productId,
            $data['quantity']
        );
    }

    public function total(Request $request)
    {
        return [
            'total' => $this->service->getTotal($request->user())
        ];
    }
}
