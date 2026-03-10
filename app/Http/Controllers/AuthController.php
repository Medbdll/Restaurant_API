<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

public function register(Request $request)
{
    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|min:6'
    ]);

    $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token'=>$token]);
}

public function login(Request $request)
{
    if(!Auth::attempt($request->only('email','password')))
        return response()->json(['message'=>'Unauthorized'],401);

    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token'=>$token]);
}

public function logout(Request $request)
{
    $request->user()->tokens()->delete();
    return response()->json(['message'=>'Logged out']);
}

public function user(Request $request)
{
    return $request->user();
}

}
