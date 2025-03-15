<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    final function authorize(): true
    {
        return true;
    }

    final function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'profile_photo' => 'nullable|image|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
