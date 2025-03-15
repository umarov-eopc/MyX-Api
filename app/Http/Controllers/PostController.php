<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resource\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Imagick;

class PostController extends Controller
{
    final function index(): AnonymousResourceCollection
    {
        $posts = Post::with(['user'])
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }

    final function store(StorePostRequest $request): PostResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('post-photos', 'public');

            $imagePath = storage_path('app/public/' . $path);

            $imagick = new Imagick($imagePath);

            $imagick->resizeImage(1200, 1200, Imagick::FILTER_LANCZOS, 1);

            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();

            $data['photo'] = $path;
        }

        $post = Post::create($data);
        $post->load('user');

        return new PostResource($post);

    }

    final function show(Post $post): PostResource
    {
        $post->load(['user']);
        return new PostResource($post);
    }
}
