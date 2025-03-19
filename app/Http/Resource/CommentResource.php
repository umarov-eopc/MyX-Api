<?php

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    final function toArray($request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'likes_count' => $this->likes()->count(),
            'is_liked' => $this->isLikedBy(auth()->user()),
            'user' => new UserResource($this->user),
            'post_id' => $this->post_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
