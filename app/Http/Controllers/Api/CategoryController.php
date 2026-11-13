<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return ['data' => Category::withCount('books')->orderBy('name')->get()];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        return response()->json(['data' => Category::create($data)], 201);
    }

    public function show(Category $category)
    {
        return ['data' => $category->load('books')];
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $category->update($data);

        return ['data' => $category];
    }

    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            return response()->json(['message' => 'Kategori masih dipakai oleh buku'], 409);
        }

        $category->delete();

        return response()->noContent();
    }
}