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
        unset($user['tokens']);
        $user['school'] = $user->school;
        $user['avatar'] = $user->avatar;
        $user['major'] = $user->major->major;
        dd($user->school_id, $user->major->major_id);
        $count = UserMajor::where(['school_id' => $user->school_id, 'major_id' => $user->major->major_id])->get();
        dd($count);
        $user['major']['major_user_count'] = $count;

        return response(['user' => $user]);
    }
}
