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
        'short_content',
        'content',
        'like',
        'dislike',
        'published',
        'is_active'
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
        return $this->hasOne(PostTitle::class, 'id', 'title');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
