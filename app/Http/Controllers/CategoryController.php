<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->with('plats')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['user_id'] = auth()->id();
        $category = Category::create($validated);
        
        return response()->json($category->load('plats'), 201);
    }

    public function show(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json($category->load('plats'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $category->update($validated);
        
        return response()->json($category->load('plats'),201);
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();
        
        return response()->json(null, 204);
    }

    public function addPlats(Request $request, Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'plat_ids' => 'required|array',
            'plat_ids.*' => 'exists:plats,id'
        ]);
        
        $plats = Plat::whereIn('id', $validated['plat_ids'])
                    ->where('user_id', auth()->id())
                    ->get();

        if ($plats->count() !== count($validated['plat_ids'])) {
            return response()->json(['message' => 'Some plats do not belong to you'], 403);
        }

        $category->plats()->attach($validated['plat_ids']);

        return response()->json($category->load('plats'));
    }
}
