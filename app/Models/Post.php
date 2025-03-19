<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'photo',
    ];

    final function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    final function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    final function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    final function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
