<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resource\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    final function index(Post $post): AnonymousResourceCollection
    {
        $comments = $post->comments()->with('user')->latest()->paginate(20);

        return CommentResource::collection($comments);
    }

    final function store(StoreCommentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $comment = Comment::create($data);
        $comment->load('user');

        return response()->json(new CommentResource($comment), 201);
    }

    final function destroy(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
