<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ReigisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller

{
    public function register(ReigisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'profile_image' => 'http://127.0.0.1:8000/storage/' . $request->file('profile_image')->store('profiles', 'public'),
            'id_image' => 'http://127.0.0.1:8000/storage/' . $request->file('id_image')->store('ids', 'public'),
            'user_type' => $request->user_type,
            'status' => 'pending',
        ]);
        return ResponseHelper::jsonResponse($user, 'User registered successfully , awaitting admin approval', 201);
        // return response()->json(['message' => 'User registered successfully , awaitting admin approval', 'user' => $user], 201);
    }



    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('phone', 'password'))) {
            return ResponseHelper::jsonResponse(null, 'Invalid phone or password', 401);
        }

        $user = User::where('phone', $request->phone)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::jsonResponse(['token' => $token, 'user' => $user], 'Login successful', 200);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseHelper::jsonResponse(null, 'Logged out successfully', 200);
    }
}
