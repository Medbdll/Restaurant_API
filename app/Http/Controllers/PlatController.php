<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlatController extends Controller
{
    public function index()
    {
        $plats = Plat::where('user_id', auth()->id())->with('categories')->get();
        return response()->json($plats);
    }

    

    public function store(Request $request)
    {
        $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

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
        $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        if ($plat->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            if ($plat->image) {
                Storage::disk('public')->delete($plat->image);
            }
            
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

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
        $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        if ($plat->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $plat->delete();
        return response()->json(null, 204);
    }
    public function addIngrediants(Request $request, Plat $plat)
    {
        $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        if ($plat->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'ingredient_ids' => 'required|array',
            'ingredient_ids.*' => 'exists:ingredient,id'
        ]);

        $plat->ingredients()->attach($validated['ingredient_ids']);
        
        return response()->json($plat->load('ingredients'), 200);
    }
}
