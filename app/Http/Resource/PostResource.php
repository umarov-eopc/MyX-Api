<?php

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    final function toArray($request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'photo' => $this->photo ? url('storage/' . $this->photo) : null,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
