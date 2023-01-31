<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Models\Courses;
use App\Models\School;
use App\Models\TeacherCourses;
use App\Models\Teachers;
use Illuminate\Http\Request;

class CoursesController extends Controller
{

    public function list(Request $request)
    {
        $user = $request->user();
        $schoolId = School::first()->id;
        $majorId = $user->major->major_id;

        $data = [];
        $courses = Courses::where(['school_id' => $schoolId, 'major_id' => $majorId])->get();
        foreach ($courses as $course) {
            $teachers = TeacherCourses::where(['course_id' => $course->id])->get();
            $data[] = [
                'id' => $course->id,
                'name' => $course->name,
                'teachers' => TeacherResource::collection($teachers),
            ];
        }

        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
