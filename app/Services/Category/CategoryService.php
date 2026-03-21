<?php

namespace App\Services\Category;

use App\Model\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAll()
    {
        return Category::latest()->get();
    }

    public function findBySlug($slug)
    {
        return Category::where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        $data['slug'] = $this->generateSlug($data['name']);

        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = generateSlug($data['name']);
        }

        $category->update($data);

        return $category;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }

    private function generateSlug($name)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while(Category::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}