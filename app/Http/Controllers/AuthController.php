<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Image;

class AuthController extends Controller
{
    final function register(RegisterRequest $request)
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

        $token = $user->createToken
    }
}
