<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('plats')->get();
        
        return response()->json($categories);
    }

    public function create()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $category = Category::create($validated);
        
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        $category->load('plats');
        
        return response()->json($category);
    }

    public function edit(Category $category)
    {
       
    }

    public function update(Request $request, Category $category)
    {
        
    }

    public function destroy(Category $category)
    {
        
    }
}
