<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
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

        return response(['user' => $user]);
    }
}
