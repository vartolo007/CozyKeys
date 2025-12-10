<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller

{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:10|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date',
            'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'id_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'user_type' => 'required|in:admin,tenant,owner',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'profile_image' => $request->file('profile_image')->store('profiles', 'public'),
            'id_image' => $request->file('id_image')->store('ids', 'public'),
            'user_type' => $request->user_type,
            'status' => 'pending',
        ]);
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:10',
            'password' => 'required|string|min:8',
        ]);

        if (!Auth::attempt($request->only('phone', 'password'))) {
            return response()->json(['message' => 'Invalid phone or password'], 401);
        }

        $user = User::where('phone', $request->phone)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
