<?php

use App\Models\Like;

function isLiked(int $user_id, int $post_id): string
{
    $like = Like::where(['user_id' => $user_id, 'post_id' => $post_id])->first();
    if ($like) {
        if ($like->is_liked) {
            return 'like';
        } else {
            return 'dislike';
        }
    } else {
        return 'none';
    }
}
