<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\UserMajor;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index(Request $request)
    {
        $data = [];
        $user = $request->user();
        unset($user['tokens']);
        $data = $user->getMutatedAttributes();
        $data['school'] = $user->school;
        $data['avatar'] = $user->avatar;
        $data['major'] = $user->major;
        dd($user->major, $data);
        $count = UserMajor::where(['school_id' => $user->school_id, 'major_id' => $user->major->major_id])->count();
        $data['major']['major_user_count'] = $count;

        return response(['user' => $data]);
    }
}
