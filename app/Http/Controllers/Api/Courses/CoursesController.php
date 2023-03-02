<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\UserTeacherResource;
use App\Models\Courses;
use App\Models\School;
use App\Models\TeacherCourses;
use App\Models\Teachers;
use App\Models\TeacherVote;
use App\Models\UserTeacher;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

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
        $sort = $request->sort;
        if (!in_array($sort, ['highest_points', 'lowest_points', 'name', 'department'])) {
            $sort = 'all';
        }

        $departmentId = $request->department_id ?? null;

        switch ($sort) {
            case 'highest_points':
                $teachers = collect();
                $teachersQuery = Teachers::all();
                foreach ($teachersQuery as $teacher) {
                    if (TeacherVote::where(['teacher_id' => $teacher->id])->exists()) {
                        $votes = TeacherVote::where(['teacher_id' => $teacher->id])->get();
                        $quality = 0;
                        $attitude = 0;
                        $performance = 0;
                        foreach ($votes as $vote) {
                            $quality += $vote->quality;
                            $attitude += $vote->attitude;
                            $performance += $vote->performance;
                        }
                        $qualityRate = $quality / count($votes);
                        $attitudeRate = $attitude / count($votes);
                        $performanceRate = $performance / count($votes);
                        $point = ($qualityRate + $attitudeRate + $performanceRate) / 3;
                    } else {
                        $point = 10;
                    }

                    $teachers->add([
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'is_admin' => (bool)$teacher->is_admin,
                        'point' => $point
                    ]);
                }
                $teachers = $teachers->sortByDesc('point');
                $newTeachers = collect();
                foreach ($teachers as $teacher) {
                    $newTeachers->add(Teachers::find($teacher['id']));
                }
                $teachers = paginate($newTeachers, 10);
                break;
            case 'lowest_points':
                $teachers = collect();
                $teachersQuery = Teachers::all();
                foreach ($teachersQuery as $teacher) {
                    if (TeacherVote::where(['teacher_id' => $teacher->id])->exists()) {
                        $votes = TeacherVote::where(['teacher_id' => $teacher->id])->get();
                        $quality = 0;
                        $attitude = 0;
                        $performance = 0;
                        foreach ($votes as $vote) {
                            $quality += $vote->quality;
                            $attitude += $vote->attitude;
                            $performance += $vote->performance;
                        }
                        $qualityRate = $quality / count($votes);
                        $attitudeRate = $attitude / count($votes);
                        $performanceRate = $performance / count($votes);
                        $point = ($qualityRate + $attitudeRate + $performanceRate) / 3;
                    } else {
                        $point = 10;
                    }

                    $teachers->add([
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'is_admin' => (bool)$teacher->is_admin,
                        'point' => $point
                    ]);
                }
                $teachers = $teachers->sortBy('point');
                $newTeachers = collect();
                foreach ($teachers as $teacher) {
                    $newTeachers->add(Teachers::find($teacher['id']));
                }
                $teachers = paginate($newTeachers, 10);
                break;
            case 'name':
                $teachers = Teachers::orderBy('name')->paginate(10);
                break;
            case 'department':
                $teachers = collect();
                $teachersQuery = Teachers::all();
                foreach ($teachersQuery as $teacher) {
                    $t = TeacherCourses::where(['teacher_id' => $teacher->id, 'course_id' => $departmentId])->first();
                    if ($t) {
                        $teachers->add($teacher);
                    }
                }

                $teachers = paginate($teachers, 10);
                break;
            default:
                $teachers = Teachers::paginate(10);
                break;
        }

        return UserTeacherResource::collection($teachers);
    }

    public function departments(Request $request)
    {
        $schoolId = School::first()->id;

        $data = [];
        $courses = Courses::where(['school_id' => $schoolId])->get();
        foreach ($courses as $course) {
            $data[] = [
                'id' => $course->id,
                'name' => $course->name
            ];
        }

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function reviewUpsert(Request $request)
    {
        $user = $request->user();

        $validator = \Validator::make($request->all(), [
            'teacher_id' => 'required|integer',
            'quality' => 'required|integer',
            'attitude' => 'required|integer',
            'performance' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'teacher_id' => 'Öğretmen',
            'quality' => 'Kalite',
            'attitude' => 'Davranış',
            'performance' => 'Performans',
            'comment' => 'Yorum',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Tüm alanları doldurmalısınız.', 'data' => $validator->errors()], 400);
        }

        $teacher = Teachers::find($request->teacher_id);

        if (!$teacher) {
            return response(['status' => 'error', 'message' => 'Öğretmen bulunamadı!'], 400);
        }

        $teacherVote = TeacherVote::where(['teacher_id' => $teacher->id])->first();
        if (!$teacherVote) {
            $teacherVote = TeacherVote::create([
                'teacher_id' => $teacher->id,
                'quality' => $request->quality,
                'attitude' => $request->attitude,
                'performance' => $request->performance,
                'comment' => $request->comment,
            ]);
        } else {
            $teacherVote->quality = $request->quality;
            $teacherVote->attitude = $request->attitude;
            $teacherVote->performance = $request->performance;
            $teacherVote->comment = $request->comment;
            $teacherVote->save();
        }

        return response(['status' => 'success', 'message' => 'İşleminiz başarıyla gerçekleştirildi!']);
    }

    public function teacherDetail(Request $request, $id)
    {
        $user = $request->user();
        $teacher = Teachers::find($id);
        if (!$teacher) {
            return response(['status' => 'error', 'message' => 'Öğretmen bulunamadı!'], 400);
        }

        if (TeacherVote::where(['teacher_id' => $id])->exists()) {
            $votes = TeacherVote::where(['teacher_id' => $id])->get();
            $quality = 0;
            $attitude = 0;
            $performance = 0;
            foreach ($votes as $vote) {
                $quality += $vote->quality;
                $attitude += $vote->attitude;
                $performance += $vote->performance;
            }
            $qualityRate = $quality / count($votes);
            $attitudeRate = $attitude / count($votes);
            $performanceRate = $performance / count($votes);
            $point = ($qualityRate + $attitudeRate + $performanceRate) / 3;
        } else {
            $point = 10;
        }

        return response(['status' => 'success', 'data' => [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'is_admin' => (bool)$teacher->is_admin,
            'points' => $point,
            'color' => getColor($point),
            'qualityRate' => $qualityRate ?? 10,
            'attitudeRate' => $attitudeRate ?? 10,
            'performanceRate' => $performanceRate ?? 10
        ]]);
    }

    public function teacherReviews(Request $request, $id)
    {
        $user = $request->user();
        $teacher = Teachers::find($id);

        if (!$teacher) {
            return response(['status' => 'error', 'message' => 'Öğretmen bulunamadı!'], 400);
        }

        return TeacherVote::where(['teacher_id' => $teacher->id])->paginate(10);
    }
}
