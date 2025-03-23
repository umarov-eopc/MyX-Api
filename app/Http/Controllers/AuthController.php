<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resource\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Imagick;

class AuthController extends Controller
{
    final function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $imagePath = storage_path('app/public/' . $path);

            $imagick = new Imagick($imagePath);

            $imagick->resizeImage(300, 300, Imagick::FILTER_LANCZOS, 1);

            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();

            $data['profile_photo'] = $path;
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'User registered successfully'
        ], 201);
    }

    final function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'User logged in successfully'
        ]);
    }

    final function user(Request $request): UserResource
    {
        $user = auth()->user();
        $user->load(['posts']);

        return new UserResource($user);
    }
}
