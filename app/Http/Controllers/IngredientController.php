<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    
    public function index()
    {
        $ingredient = Ingredient::all();
        return response()->json($ingredient);
    }

    
    public function store(Request $request)
    {
          $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tags' => 'array',
        ]);
        $ingredient = Ingredient::create($validated);
        return response()->json($ingredient, 201);
    }

    
    public function show(Ingredient $ingredient)
    {
        return response()->json($ingredient);
    }

    
    public function update(Request $request, Ingredient $ingredient)
    {
          $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        if ($ingredient->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tags' => 'array',
        ]);
        $ingredient->update($validated);
        return response()->json($ingredient, 200);
    }

    public function destroy(Ingredient $ingredient)
    {
        $user = User::find(auth()->id());
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        if ($ingredient->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $ingredient->delete();
        return response()->json(null, 204);
    }
}
