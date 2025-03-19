<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    final function togglePostLike(Post $post): JsonResponse
    {
        $user = auth()->user();

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'message' => 'Post unliked successfully',
                'is_liked' => false,
                'likes_count' => $post->likes()->count()
            ]);
        } else {
            $post->likes()->create([
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'Post liked successfully',
                'is_liked' => true,
                'likes_count' => $post->likes()->count()
            ]);
        }
    }

    final function toggleCommentLike(Comment $comment): JsonResponse
    {
        $user = auth()->user();

        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'message' => 'Comment unliked successfully',
                'is_liked' => false,
                'likes_count' => $comment->likes()->count()
            ]);
        } else {
            $comment->likes()->create([
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'Comment liked successfully',
                'is_liked' => true,
                'likes_count' => $comment->likes()->count()
            ]);
        }
    }
}
