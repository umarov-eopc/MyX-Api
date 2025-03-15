<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resource\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Image;

class AuthController extends Controller
{
    final function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            $image = Image::read(storage_path('app/public/' . $path));
            $image->cover(300, 300);
            $image->save();

            $data['profile_photo'] = $path;

        }
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'User registrated successfully'
        ], 201);
    }

    final function user(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
