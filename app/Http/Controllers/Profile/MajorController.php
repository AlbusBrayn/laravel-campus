<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\School;
use App\Models\SchoolMajor;
use App\Models\User;
use App\Models\UserMajor;
use Illuminate\Http\Request;

class MajorController extends Controller
{

    public function index(Request $request)
    {
        //@TODO: Multiple school control
        $school = School::first();
        if (!$school) {
            return response(['status' => 'error', 'message' => 'Sisteme kayıtlı bir okul bulunamadı!'], 400);
        }

        $data = [];
        $majors = $school->schoolMajors;
        foreach ($majors as $major) {
            $data[] = $major->major;
        }

        return response(['majors' => $data]);
    }

    public function students(Request $request)
    {
        $user = $request->user();
        $data = [];
        $majorId = $user->major->major_id;

        $blockedIds = $request->user()->getBlockedFriendships()->pluck('recipient_id')->toArray();
        $relations = UserMajor::where('major_id', $majorId)->whereNotIn('user_id', $blockedIds)->get();

        foreach ($relations as $relation) {
            $user = User::find($relation->user_id);
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'created_at' => $relation->created_at
            ];
        }

        return response(['students' => $data]);
    }
}
