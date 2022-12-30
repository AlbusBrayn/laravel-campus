<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\UserMajor;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        unset($user['password']);
        unset($user['remember_token']);
        unset($user['school_id']);
        unset($user['is_banned']);
        unset($user['hide_location']);
        unset($user['is_muted']);
        unset($user['otp_code']);
        unset($user['is_admin']);
        unset($user['otp_reset_time']);
        unset($user['forget_code']);
        unset($user['forget_expire']);
        $data = $user;
        $data['school'] = $user->school;
        $data['avatar'] = $user->avatar;
        $data['major'] = $user->major;
        $count = UserMajor::where(['school_id' => $data['major']['school_id'], 'major_id' => $data['major']['major_id']])->count();
        $data['major']['major_user_count'] = $count;

        return response(['user' => $data]);
    }
}
