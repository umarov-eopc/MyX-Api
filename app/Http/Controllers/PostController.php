<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resource\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Imagick;

class PostController extends Controller
{
    final function index(): AnonymousResourceCollection
    {
        $posts = Post::with(['user', 'comments', 'comments.user'])
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
        $post->load(['user', 'comments', 'comments.user']);

        return new PostResource($post);
    }

    final function update(StorePostRequest $request, Post $post): JsonResponse|PostResource
    {
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($post->photo) {
                Storage::disk('public')->delete($post->photo);
            }

            $path = $request->file('photo')->store('post-photos', 'public');

            $imagePath = storage_path('app/public/' . $path);
            $imagick = new Imagick($imagePath);

            $imagick->resizeImage(1200, 1200, Imagick::FILTER_LANCZOS, 1);

            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();

            $data['photo'] = $path;
        }

        $post->update($data);
        $post->load('user');

        return new PostResource($post);
    }

    final function destroy(Post $post): JsonResponse
    {
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($post->photo) {
            Storage::disk('public')->delete($post->photo);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function userPosts()
    {
        $posts = Post::with(['user', 'comments', 'comments.user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }
}
