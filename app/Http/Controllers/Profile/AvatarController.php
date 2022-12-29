<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\User;
use Illuminate\Http\Request;

class AvatarController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->avatar) {
            return response(['status' => 'error', 'message' => 'Kullanıcının avatar datası oluşturulmamış!']);
        }

        return response(['avatar' => $user->avatar]);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'skin' => 'nullable|string',
            'clothes' => 'nullable|string',
            'mouth' => 'nullable|string',
            'eyes' => 'nullable|string',
            'eye_brow' => 'nullable|string',
            'top' => 'nullable|string',
            'hair_color' => 'nullable|string',
            'accessories' => 'nullable|string',
            'beard' => 'nullable|string',
            'beard_color' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()]);
        }

        $user = $request->user();

        if ($user->avatar) {
            return response(['status' => 'error', 'message' => 'Kullanıcının avatarı zaten oluşturulmuş!']);
        }

        $data = $validator->validated();
        $data['user_id'] = $user->id;

        $create = Avatar::create($data);
        if ($create) {
            return response(['status' => 'success', 'message' => 'Başarılı']);
        } else {
            return response(['status' => 'error', 'message' => 'Hata']);
        }
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'skin' => 'nullable|string',
            'clothes' => 'nullable|string',
            'mouth' => 'nullable|string',
            'eyes' => 'nullable|string',
            'eye_brow' => 'nullable|string',
            'top' => 'nullable|string',
            'hair_color' => 'nullable|string',
            'accessories' => 'nullable|string',
            'beard' => 'nullable|string',
            'beard_color' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()]);
        }

        $user = $request->user();

        if (!$user->avatar) {
            return response(['status' => 'error', 'message' => 'Kullanıcının avatar datası oluşturulmamış!']);
        }

        $data = $validator->validated();
        $avatar = Avatar::where('user_id', '=', $user->id)->first();
        $avatar->update($data);
        $update = $avatar->save();

        if ($update) {
            return response(['status' => 'success', 'message' => 'Başarılı']);
        } else {
            return response(['status' => 'error', 'message' => 'Hata']);
        }
    }
}
