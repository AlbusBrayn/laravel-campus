<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Major;
use App\Models\Message;
use App\Models\User;
use App\Models\UserMajor;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();
        $unreads = [];
        $unread = Message::where(['receiver_id' => $user->id, 'is_read' => false])->get();
        foreach ($unread as $item) {
            $s = User::find($item->sender_id);
            $unreads[] = [
                'sender_id' => $item->sender_id,
                'message' => $item->message,
                'created_at' => $item->created_at,
                'name' => $s->name,
                'avatar' => $s->avatar
            ];
        }
        $messageUsers = [];
        $messages = Message::where(['receiver_id' => $user->id])->orWhere(['sender_id' => $user->id])->get();
        foreach ($messages as $message) {
            if ($message->sender_id == $user->id) {
                $m = User::find($message->receiver_id);
            } else {
                $m = User::find($message->sender_id);
            }
            $messageUsers[] = [
                'id' => $m->id,
                'name' => $m->name,
                'avatar' => $m->avatar,
            ];
        }
        $getMajor = UserMajor::where(['user_id' => $user->id])->first();

        $users = [];
        $friends = UserMajor::where(['major_id' => $getMajor->major_id, 'school_id' => $getMajor->school_id])->get()->except($user->id);
        $friends = $friends->random((count($friends) > 10) ? 10 : count($friends));
        foreach ($friends as $friend) {
            $user2 = User::find($friend->user_id);
            if ($user->isFriendWith($user2)) {
                $hasRequest = true;
            } else {
                if ($user->hasSentFriendRequestTo($user2)) {
                    $hasRequest = true;
                } else {
                    $hasRequest = false;
                }
            }
            $users[] = [
                'id' => $user2->id,
                'name' => $user2->name,
                'avatar' => $user2->avatar,
                'email' => $user2->email,
                'has_request' => $hasRequest,
            ];
        }
        $realFriends = [];
        foreach ($user->getFriends() as $rfriend) {
            $realFriends[] = [
                'id' => $rfriend->id,
                'name' => $rfriend->name,
                'avatar' => $rfriend->avatar,
                'email' => $rfriend->email,
            ];
        }

        return response(['messages' => $messageUsers, 'unread' => $unreads, 'users' => $users, 'friends' => $realFriends]);
    }

    public function startMessage(Request $request)
    {
        $user = $request->user();
        $users = [];
        $friends = $user->getFriends();

        foreach ($friends as $friend) {
            $users[] = [
                'id' => $friend->id,
                'name' => $friend->name,
                'avatar' => $friend->avatar,
                'email' => $friend->email,
            ];
        }

        return response(['friends' => $users]);
    }

    public function send(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'receiver_id' => 'required|integer',
            'message' => 'required|string'
        ]);

        $validator->setAttributeNames([
            'receiver_id' => 'Alıcı',
            'message' => 'Mesaj'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Eksik bilgi gönderemezsiniz!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $receiver = User::findOrFail($request->receiver_id);

        if ($user->id == $receiver->id) {
            return response(['status' => 'error', 'message' => 'Kendinize mesaj gönderemezsiniz!'], 400);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message
        ]);

        if ($message) {
            $event = new SendMessageEvent(json_encode(['message' => $message->message, 'sender' => $user->name]), $user->id, $receiver->id);

            return response(['status' => 'success', 'message' => 'Mesajınız başarıyla gönderildi!'], 200);
        } else {
            return response(['status' => 'error', 'message' => 'Mesajınız gönderilemedi!'], 400);
        }
    }

    public function get(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'receiver_id' => 'required|integer',
        ]);

        $validator->setAttributeNames([
            'receiver_id' => 'Alıcı',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Eksik bilgi gönderemezsiniz!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $receiver = User::findOrFail($request->receiver_id);

        $messages = Message::latest()->where(['receiver_id' => $receiver->id, 'sender_id' => $user->id])->orWhere(['receiver_id' => $user->id, 'sender_id' => $receiver->id])->orderBy('created_at')->paginate(50);

        return response(['status' => 'success', 'message' => 'Mesajlar başarıyla getirildi!', 'data' => $messages], 200);
    }
}
