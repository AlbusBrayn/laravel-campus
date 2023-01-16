<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMajor;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $school = $user->school;

        if ($user->major) {
            $major = [
                'title' => $user->major->major->title,
                'major_user_count' => UserMajor::where(['school_id' => $user->school_id, 'major_id' => $user->major->major_id])->count()
            ];
        } else {
            $major = [];
        }

        $profile = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'status' => $user->status,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'school' => $school,
            'avatar' => $user->avatar,
            'major' => $major,
            'hide_location' => (bool)$user->hide_location,
            'lat' => $user->lat,
            'lng' => $user->lng,
            'followers' => $user->followersCount(),
            'posts' => $user->posts->count(),
            'posts_list' => $user->posts,
            'followers_list' => $user->followers,
            'follow_requests' => $user->getFriendRequests()->count(),
        ];

        return response(['user' => $profile]);
    }

    public function visitor(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::findOrFail($id);

        $data = [
            'name' => $visitor->name,
            'avatar' => $visitor->avatar,
            'followers' => $visitor->followersCount(),
            'posts' => $visitor->posts->count(),
            'is_follow' => $user->isFollowing($visitor->id),
            'is_admin' => $user->id === $visitor->id,
            'posts_list' => $visitor->posts,
            'followers_list' => $visitor->followers,
        ];

        return response(['user' => $data]);
    }

    public function friendRequest(Request $request)
    {
        $user = $request->user();
        $friendRequests = $user->getFriendRequests();

        return response(['friend_requests' => $friendRequests]);
    }

    public function friendAccept(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);
        $user->acceptFriendRequest($friend);

        return response(['message' => 'Arkadaşlık isteği kabul edildi!']);
    }

    public function friendDecline(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);
        $user->denyFriendRequest($friend);

        return response(['message' => 'Arkadaşlık isteği reddedildi!']);
    }

    public function connect(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::find($id);

        if ($user->isFollowing($visitor->id)) {
            $user->unfollow($visitor->id);
            $d = "unfollow";
        } else {
            $user->follow($visitor->id);
            $d = "follow";
        }

        return response(['message' => 'success', 'data' => $d]);
    }

    public function block(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::find($id);

        if ($user->isBlocking($visitor->id)) {
            $user->unblock($visitor->id);
            $d = "unblock";
        } else {
            $user->block($visitor->id);
            $d = "block";
        }

        return response(['message' => 'success', 'data' => $d]);
    }
}
