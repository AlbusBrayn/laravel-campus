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
            $unreads[] = $s->id;
        }

        $messages = Message::where(['receiver_id' => $user->id])->orWhere(['sender_id' => $user->id])->get();
        $chatList = [];
        foreach ($messages as $message) {
            if ($message->receiver_id === $user->id) {
                $chatList[$message->sender_id] = ['message' => $message->message, 'time' => $message->created_at];
            } else {
                $chatList[$message->receiver_id] = ['message' => $message->message, 'time' => $message->created_at];
            }
        }

        $array = [];
        foreach ($chatList as $userId => $arr) {
            $user2 = User::find($userId);
            $array[] = [
                'name' => $user2->name,
                'id' => $user2->id,
                'message' => \Str::substr($arr['message'], 0, 50) . '' . strlen($arr['message']) > 50 ? '...' : '',
                'is_unread' => in_array($user2->id, $unreads),
                'avatar' => $user2->avatar,
                'time' => timeParse(strtotime($arr['time'])),
            ];
        }

        return response(['messages' => $array]);
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
            $pusher = new \Pusher\Pusher(config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options'));

            $pusher->trigger('campus-message', 'message-' . $receiver->id . '-' . $user->id, ['status' => 'success']);

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

        $messages = Message::where(['receiver_id' => $receiver->id, 'sender_id' => $user->id])->orWhere(['receiver_id' => $user->id, 'sender_id' => $receiver->id])->orderBy('id', 'DESC')->paginate(20);
        $unreadMessages = Message::where(['receiver_id' => $user->id, 'sender_id' => $receiver->id, 'is_read' => false])->get();
        foreach ($unreadMessages as $message) {
            $message->is_read = true;
            $message->save();
        }

        return response(['status' => 'success', 'message' => 'Mesajlar başarıyla getirildi!', 'data' => $messages], 200);
    }

    public function searchMessage(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'search' => 'required|string|min:3',
        ]);

        $validator->setAttributeNames([
            'search' => 'Arama',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Eksik bilgi gönderemezsiniz!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $datas = [];
        $messages = Message::where([['receiver_id', '=', $user->id], ['message', 'like', "%{$request->search}%"]])->orWhere([['sender_id', '=', $user->id], ['message', 'like', "%{$request->search}%"]])->orderBy('id', 'DESC')->get();
        foreach ($messages as $message) {
            $datas[] = [
                $sender = User::find($message->sender_id),
                $receiver = User::find($message->receiver_id),
                'id' => ($sender->id === $user->id) ? $receiver->id : $sender->id,
                'name' => $sender->name,
                'email' => $sender->email,
                'avatar' => $sender->avatar,
                'message' => $message->message,
                'created_at' => $message->created_at,
            ];
        }

        return response(['status' => 'success', 'message' => 'Mesajlar başarıyla getirildi!', 'data' => $datas], 200);
    }
}
