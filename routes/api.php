<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 
Route::post('/register', [AuthController::class ,'register']);
Route::post('/login', [AuthController::class ,'login']);
Route::middleware('auth:sanctum')->group(function () {
   
    Route::post('/logout', [AuthController::class ,'logout']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('plats', PlatController::class);
    Route::post('/categories/{category}/plats', [CategoryController::class, 'addPlats']);
});
