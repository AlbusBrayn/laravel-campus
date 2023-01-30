<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\School;
use Illuminate\Http\Request;

class CoursesController extends Controller
{

    public function list(Request $request)
    {
        $user = $request->user();
        $schoolId = School::first()->id;
        $majorId = $user->major->major_id;

        $courses = Courses::where(['school_id' => $schoolId, 'major_id' => $majorId])->get();
        dd($courses);
    }
}
