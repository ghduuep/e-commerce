<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductService
{
    public function getAll()
    {
        return Product::with('category')->latest()->get();
    }

    public function findBySlug($slug)
    {
        return Product::where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        $data['slug'] = $this->generateSlug($data['name']);

        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $product->update($data);

        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }

    private function generateSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while(Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}

?>