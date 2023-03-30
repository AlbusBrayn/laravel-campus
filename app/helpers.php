<?php

use App\Models\Like;
use App\Models\User;

function timeParse($zaman): string
{
    $zamanismi= array("Saniye", "Dakika", "Saat", "Gün", "Ay", "Yıl");
    $sure= array("60","60","24","30","12","10");

    $simdikizaman = time();

    if($simdikizaman >= $zaman)
    {
        $fark     = time()- $zaman;
        for($i = 0; $fark >= $sure[$i] && $i < count($sure)-1; $i++)
        {
            $fark = $fark / $sure[$i];
        }

        $fark = round($fark);

        return $fark . " " . $zamanismi[$i] . " Önce";
    }

    return "";
}

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

function isFriend(int $user_id, int $friend_id): string
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

function array_has_dupes(array $array): bool
{
    return count($array) !== count(array_unique($array));
}

function paginate(\Illuminate\Support\Collection $results, $pageSize)
{
    $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
    $total = $results->count();

    return paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
        'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        'pageName' => 'page',
    ]);
}

function getColor(float|int $point)
{
    if ($point >= 8) {
        return 'green';
    } elseif ($point >= 5) {
        return 'yellow';
    } else {
        return 'red';
    }
}

function paginator($items, $total, $perPage, $currentPage, $options)
{
    return \Illuminate\Container\Container::getInstance()->makeWith(\Illuminate\Pagination\LengthAwarePaginator::class, compact(
        'items', 'total', 'perPage', 'currentPage', 'options'
    ));
}
