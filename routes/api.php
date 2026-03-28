<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\RecommendationsController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 
Route::post('/register', [AuthController::class ,'register']);
Route::post('/login', [AuthController::class ,'login']);

Route::middleware('auth:sanctum')->group(function () {
   
    Route::post('/logout', [AuthController::class ,'logout']);
    Route::put('/dietary-tags', [AuthController::class, 'updateDietaryTags']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('plats', PlatController::class);
    Route::apiResource('ingredients', IngredientController::class);
    Route::post('/categories/{category}/plats', [CategoryController::class, 'addPlats']);

    // Simple recommendation endpoints
    Route::post('recommendations/analyze/{plate_id}', [RecommendationController::class, 'analyze']);
    Route::get('recommendations', [RecommendationController::class, 'index']);
    Route::get('recommendations/{plate_id}', [RecommendationController::class, 'show']);

});