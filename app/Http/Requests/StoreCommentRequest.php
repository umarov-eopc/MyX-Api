<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    final function authorize(): true
    {
        return true;
    }

    final function rules(): array
    {
        return [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ];
    }
}
