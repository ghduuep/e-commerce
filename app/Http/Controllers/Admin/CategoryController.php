<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $service) {}

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
        ]);

        return $this->service->create($data);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        return $this->service->update($category, $data);
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);

        return response()->json(['message'] => 'Deleted');
    }
}
