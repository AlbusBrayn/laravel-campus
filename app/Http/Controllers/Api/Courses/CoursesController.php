<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\UserTeacherResource;
use App\Models\Courses;
use App\Models\School;
use App\Models\TeacherCourses;
use App\Models\Teachers;
use App\Models\UserTeacher;
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

    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'required|integer',
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'required|integer',
        ]);

        $validator->setAttributeNames([
            'course_ids' => 'Dersler',
            'course_ids.*' => 'Ders',
            'teacher_ids' => 'Öğretmenler',
            'teacher_ids.*' => 'Öğretmen',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Bir seçim işlemi yapmadınız!', 'data' => $validator->errors()], 400);
        }

        $course_ids = $request->course_ids;
        $teacher_ids = $request->teacher_ids;

        if (count($course_ids) !== count($teacher_ids)) {
            return response(['status' => 'error', 'message' => 'Aynı ders üzerinde sadece bir öğretmen seçebilirsin!'], 400);
        }

        $user = $request->user();
        $userTeachers = UserTeacher::where(['user_id' => $user->id])->get();
        foreach ($userTeachers as $userTeacher) {
            $userTeacher->delete();
        }

        foreach ($course_ids as $key => $course_id) {
            $teacher_id = $teacher_ids[$key];
            $teacherCourses = TeacherCourses::where(['course_id' => $course_id, 'teacher_id' => $teacher_id])->first();
            if ($teacherCourses) {
                UserTeacher::create([
                    'user_id' => $user->id,
                    'teacher_course_id' => $teacherCourses->id,
                ]);
            }
        }

        return response(['status' => 'success', 'message' => 'Dersler kaydedildi!']);
    }

    public function teachers(Request $request)
    {
        //$user =  $request->user();
        $teachers = Teachers::orderBy('id', 'desc')->paginate(10);

        return response()->json(['status' => 'success', 'data' => UserTeacherResource::collection($teachers)]);
    }

    public function searchTeacher(Request $request)
    {
        $user =  $request->user();
        $search = $request->search;

        $teachers = Teachers::where('name', 'like', '%' . $search . '%');
        $data = [];

        foreach ($teachers as $teacher) {
            $data[] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'is_admin' => (bool)$teacher->is_admin,
                'points' => 10,
                'color' => 'green'
            ];
        }
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
