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
            return response(['status' => 'error', 'message' => 'Kullanıcının avatar datası oluşturulmamış!'], 400);
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
        $validator->setAttributeNames([
            'skin' => 'Ten rengi',
            'clothes' => 'Kıyafet',
            'mouth' => 'Ağız',
            'eyes' => 'Gözler',
            'eye_brow' => 'Kaşlar',
            'top' => 'Saç',
            'hair_color' => 'Saç Rengi',
            'accessories' => 'Aksesuar',
            'beard' => 'Sakal',
            'beard_color' => 'Sakal Rengi'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();

        if ($user->avatar) {
            return response(['status' => 'error', 'message' => 'Kullanıcının avatarı zaten oluşturulmuş!'], 400);
        }

        $data = $validator->validated();
        $data['user_id'] = $user->id;

        $create = Avatar::create($data);
        if ($create) {
            return response(['status' => 'success', 'message' => 'Başarılı']);
        } else {
            return response(['status' => 'error', 'message' => 'Hata'], 400);
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
        $validator->setAttributeNames([
            'skin' => 'Ten rengi',
            'clothes' => 'Kıyafet',
            'mouth' => 'Ağız',
            'eyes' => 'Gözler',
            'eye_brow' => 'Kaşlar',
            'top' => 'Saç',
            'hair_color' => 'Saç Rengi',
            'accessories' => 'Aksesuar',
            'beard' => 'Sakal',
            'beard_color' => 'Sakal Rengi'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();

        if (!$user->avatar) {
            return response(['status' => 'error', 'message' => 'Kullanıcının avatar datası oluşturulmamış!'], 400);
        }

        $data = $validator->validated();
        $avatar = Avatar::where('user_id', '=', $user->id)->first();
        $avatar->update($data);
        $update = $avatar->save();

        if ($update) {
            return response(['status' => 'success', 'message' => 'Başarılı']);
        } else {
            return response(['status' => 'error', 'message' => 'Hata'], 400);
        }
    }
}
