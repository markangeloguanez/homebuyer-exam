<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register($request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
        ], 201);
    }

    public function login($request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = User::firstWhere('email', $credentials['email']);

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'data' => [
                'token' => $token,
                'information' => [
                    'name' => $user->name,
                ]
            ]
        ], 200);
    }
}
