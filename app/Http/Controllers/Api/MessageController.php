<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
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
        $unread = Message::where(['receiver_id' => $user->id, 'is_read' => false])->get();
        $getMajor = UserMajor::where(['user_id' => $user->id])->first();

        $users = [];
        $friends = UserMajor::where(['major_id' => $getMajor->major_id, 'school_id' => $getMajor->school_id])->get()->random(10);
        foreach ($friends as $friend) {
            $users[] = $friend->user;
        }

        return response(['unread' => $unread, 'friends' => $users]);
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

        $messages = Message::where(['receiver_id' => $receiver->id, 'sender_id' => $user->id])->orWhere(['receiver_id' => $user->id, 'sender_id' => $receiver->id])->orderBy('created_at')->paginate(10);

        return response(['status' => 'success', 'message' => 'Mesajlar başarıyla getirildi!', 'data' => $messages], 200);
    }
}
