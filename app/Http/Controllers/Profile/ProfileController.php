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
        $school = $user->school;

        if ($user->major) {
            $major = [
                'title' => $user->major->major->title,
                'major_user_count' => UserMajor::where(['school_id' => $user->school_id, 'major_id' => $user->major->major_id])->count()
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
            'lng' => $user->lng
        ];

        return response(['user' => $profile]);
    }
}
