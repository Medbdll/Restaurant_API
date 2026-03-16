<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatController extends Controller
{
    public function index()
    {
        $plats = Plat::where('user_id', auth()->id())->with('categories')->get();
        return response()->json($plats);
    }

    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        $validated['user_id'] = auth()->id();
        $plat = Plat::create($validated);
        
        if ($request->has('category_ids')) {
            $userCategories = Category::whereIn('id', $request->category_ids)
                                   ->where('user_id', auth()->id())
                                   ->pluck('id')
                                   ->toArray();
            
            if (count($userCategories) !== count($request->category_ids)) {
                return response()->json(['message' => 'Some categories do not belong to you'], 403);
            }
            
            $plat->categories()->attach($userCategories);
        }
        return response()->json($plat->load('categories'), 201);
    }

    public function show(Plat $plat)
    {
        if ($plat->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($plat->load('categories'));
    }

   

    public function update(Request $request, Plat $plat)
    {
        if ($plat->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        $plat->update($validated);
        
        if ($request->has('category_ids')) {
            $userCategories = Category::whereIn('id', $request->category_ids)
                                   ->where('user_id', auth()->id())
                                   ->pluck('id')
                                   ->toArray();
            
            if (count($userCategories) !== count($request->category_ids)) {
                return response()->json(['message' => 'Some categories do not belong to you'], 403);
            }
            
            $plat->categories()->sync($userCategories);
        }

        return response()->json($plat->load('categories'));
    }

    public function destroy(Plat $plat)
    {
        if ($plat->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $plat->delete();
        return response()->json(null, 204);
    }
}
