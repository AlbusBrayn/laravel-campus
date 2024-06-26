<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Courses;
use App\Models\User;
use App\Models\UserMajor;
use App\Models\UserReport;
use App\Models\UserTeacher;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

//profile

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $school = $user->school;

        if ($user->major) {
            $blockedIds = $request->user()->getBlockedFriendships()->pluck('recipient_id')->toArray();
            $major = [
                'id' => $user->major->major->id,
                'title' => $user->major->major->title,
                'major_user_count' => UserMajor::where(['school_id' => $user->school_id, 'major_id' => $user->major->major_id])->whereNotIn('user_id', $blockedIds)->count()
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
        $school = $user->school;

        if ($user->major) {
            $major = [
                'id' => $user->major->major->id,
                'title' => $user->major->major->title,
                'major_user_count' => UserMajor::where(['school_id' => $user->school_id, 'major_id' => $user->major->major_id])->count()
            ];
        } else {
            $major = [];
        }

        $friendRequests = [];
        foreach ($visitor->getFriendRequests() as $request) {
            $friendRequests[] = [
                'id' => $request->sender->id,
                'name' => $request->sender->name,
                'avatar' => $request->sender->avatar,
                'created_at' => $request->created_at
            ];
        }

        $data = [
            'id' => $visitor->id,
            'name' => $visitor->name,
            'avatar' => $visitor->avatar,
            'school' => $school,
            'followers' => $visitor->getFriendsCount(),
            'posts' => $visitor->posts->count(),
            'is_follow' => isFriend($user->id, $visitor->id),
            'is_admin' => $user->id === $visitor->id,
            'posts_list' => PostResource::collection($visitor->posts),
            'followers_list' => UserResource::collection($visitor->getFriends()),
            'follow_requests_count' => $visitor->getFriendRequests()->count(),
            'follow_requests' => $friendRequests,
            'major' => $major,
        ];

        return response(['user' => $data]);
    }

    public function report(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'reason_id' => 'required|integer|in:1,2,3,4,5,6,7,8,9',
        ]);

        $validator->setAttributeNames([
            'reason_id' => 'Sebep',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Hata.', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $visitor = User::findOrFail($id);

        if ($user->id === $visitor->id) {
            return response(['status' => 'error', 'message' => 'Kendini şikayet edemezsin.'], 400);
        }

        if (UserReport::where(['sender_id' => $user->id, 'receiver_id' => $visitor->id])->exists()) {
            return response(['status' => 'error', 'message' => 'Bu kullanıcıyı daha önce şikayet ettin.'], 400);
        }

        UserReport::create([
            'sender_id' => $user->id,
            'receiver_id' => $visitor->id,
            'reason_id' => $request->reason_id
        ]);

        return response(['status' => 'success', 'message' => 'Şikayet başarıyla gönderildi.']);
    }

    public function deleteFriend(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::findOrFail($id);

        if ($user->isFriendWith($visitor)) {
            $user->unfriend($visitor);
            return response(['status' => 'success', 'message' => 'Kullanıcı başarıyla arkadaşlıktan çıkarıldı.']);
        } else {
            return response(['status' => 'error', 'message' => 'Arkadaşlık bulunamadı.'], 404);
        }
    }

    public function friendRequest(Request $request)
    {
        $user = $request->user();
        $friendRequests = $user->getFriendRequests();

        return response(['friend_requests' => $friendRequests]);
    }

    public function cancelRequest(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::findOrFail($id);

        if ($user->hasSentFriendRequestTo($visitor)) {
            $visitor->denyFriendRequest($user);
            return response(['status' => 'success', 'message' => 'Arkadaşlık isteği başarıyla iptal edildi.']);
        } else {
            return response(['status' => 'error', 'message' => 'Arkadaşlık isteği bulunamadı.'], 404);
        }
    }

    public function friendAccept(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        if ($friend->hasSentFriendRequestTo($user)) {
            $user->acceptFriendRequest($friend);
            return response(['status' => 'success', 'message' => 'Arkadaşlık isteği kabul edildi.']);
        } else {
            return response(['status' => 'error', 'message' => 'Arkadaşlık isteği bulunamadı.'], 404);
        }
    }

    public function friendDecline(Request $request, $id)
    {
        $user = $request->user();
        $friend = User::findOrFail($id);

        if ($friend->hasSentFriendRequestTo($user)) {
            $user->denyFriendRequest($friend);
            return response(['status' => 'success', 'message' => 'Arkadaşlık isteği reddedildi.']);
        } else {
            return response(['status' => 'error', 'message' => 'Arkadaşlık isteği bulunamadı.'], 404);
        }
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
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

        if ($visitor->hasSentFriendRequestTo($user)) {
            return response(['status' => 'error', 'message' => 'Bu kullanıcı size arkadaşlık isteği göndermiş!'], 400);
        }

        $user->befriend($visitor);

        if ($visitor->device_id) {
            FirebaseService::sendNotification($visitor->device_id, 'Yeni Arkadaşlık İsteği!', $user->name . ' size arkadaşlık isteği gönderdi.');
        }

        return response(['status' => 'success', 'message' => 'Arkadaşlık isteği gönderildi!']);
    }

    public function block(Request $request, $id)
    {
        $user = $request->user();
        $visitor = User::findOrFail($id);

        if ($user->hasBlocked($visitor)) {
            $user->unblockFriend($visitor);
            $d = "unblock";
        } else {
            if ($user->isFriendWith($visitor)) {
                $user->unfriend($visitor);
            }
            $user->blockFriend($visitor);
            $d = "block";
        }

        return response(['status' => 'success', 'message' => 'Başarılı', 'data' => $d]);
    }

    public function courses(Request $request)
    {
        $user = $request->user();
        $userTeachers = UserTeacher::where(['user_id' => $user->id])->get();
        $courses = [];

        $i = 0;
        foreach ($userTeachers as $teacher) {
            $courses[$i]['course'] = $teacher->teacherCourse->course->name;
            $courses[$i]['teacher'] = $teacher->teacherCourse->teacher->name;
            $courses[$i]['teacher_id'] = $teacher->teacherCourse->teacher->id;
            $i++;
        }

        return response(['status' => 'success', 'courses' => $courses]);
    }

    public function oneSignal(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'one_signal' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'one_signal' => 'One Signal ID',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Hata.', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $user->one_signal = $request->one_signal;
        $user->save();

        return response(['status' => 'success', 'message' => 'Başarılı.']);
    }

    public function deleteAccount()
    {
        $user = auth()->user();
        $user->delete();
        return response(['status' => 'success', 'message' => 'Hesabınız başarıyla silindi.']);
    }
}
