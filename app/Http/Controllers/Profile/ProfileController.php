<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
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
            'followers' => $user->getFriendsCount(),
            'posts' => $user->posts->count(),
            'posts_list' => $user->posts,
            'followers_list' => $user->getFriends(),
            'follow_requests' => $user->getFriendRequests()->count(),
        ];

        return response(['user' => $profile]);
    }

    public function visitor(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::findOrFail($id);

        $friendRequests = [];
        foreach ($visitor->getFriendRequests() as $request) {
            $friendRequests[] = ['sender_id' => $request->sender_id, 'created_at' => $request->created_at];
        }

        $data = [
            'id' => $visitor->id,
            'name' => $visitor->name,
            'avatar' => $visitor->avatar,
            'followers' => $visitor->getFriendsCount(),
            'posts' => $visitor->posts->count(),
            'is_follow' => isFriend($user->id, $visitor->id),
            'is_admin' => $user->id === $visitor->id,
            'posts_list' => PostResource::collection($visitor->posts),
            'followers_list' => $visitor->getFriends(),
            'follow_requests_count' => $visitor->getFriendRequests()->count(),
            'follow_requests' => UserResource::collection($friendRequests),
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

    public function sendRequest(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::find($id);

        if ($user->id === $visitor->id) {
            return response(['status' => 'error', 'message' => 'Kendinize arkadaşlık isteği gönderemezsiniz!'], 400);
        }

        if ($user->isFriendWith($visitor)) {
            return response(['status' => 'error', 'message' => 'Zaten arkadaşsınız!'], 400);
        }

        if ($user->hasSentFriendRequestTo($visitor)) {
            return response(['status' => 'error', 'message' => 'Zaten arkadaşlık isteği göndermişsiniz!'], 400);
        }

        $user->befriend($visitor);

        return response(['status' => 'success', 'message' => 'Arkadaşlık isteği gönderildi!']);
    }

    public function block(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::find($id);

        if ($user->hasBlocked($visitor)) {
            $user->unblockFriend($visitor);
            $d = "unblock";
        } else {
            $user->blockFriend($visitor);
            $d = "block";
        }

        return response(['message' => 'success', 'data' => $d]);
    }
}
