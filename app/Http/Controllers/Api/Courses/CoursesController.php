<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class CoursesController extends Controller
{

    public function list(Request $request)
    {
        $user = $request->user();
        $schoolId = School::first();
        $majorId = $user->major->major_id;

        dd([$schoolId, $majorId]);
    }
}
