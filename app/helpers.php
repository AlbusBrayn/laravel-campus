<?php

use App\Models\Like;
use App\Models\User;

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

function isFriend(int $user_id, int $friend_id): bool
{
    $user = User::find($user_id);
    $visitor = User::find($friend_id);

    if ($user->isFriendWith($visitor)) {
        return "friend";
    } else {
       if ($user->hasSentFriendRequestTo($visitor)) {
           return "pending";
       } else {
           return "none";
       }
    }
}
