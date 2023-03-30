<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'like',
        'dislike',
        'published'
    ];

    protected $hidden = [
        'user_id',
        'published'
    ];

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function postTitle(): HasOne
    {
        return $this->hasOne(PostTitle::class, 'title', 'id');
    }
}
