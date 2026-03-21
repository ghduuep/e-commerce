<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Product\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index()
    {
        return $this->service->getAll();
    }

    public function show($slug)
    {
        return $this->service->findBySlug($slug);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        return $this->service->create($data);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        return $this->service->update($product, $data);
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);

        return response()->json(['message' => 'Deleted']);
    }
}
