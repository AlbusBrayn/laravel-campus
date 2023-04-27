<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\TeacherVoteResource;
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
            'course_ids' => 'array|min:1',
            'course_ids.*' => 'integer',
            'teacher_ids' => 'array|min:1',
            'teacher_ids.*' => 'integer',
        ]);

        $validator->setAttributeNames([
            'course_ids' => 'Dersler',
            'course_ids.*' => 'Ders',
            'teacher_ids' => 'Öğretmenler',
            'teacher_ids.*' => 'Öğretmen',
        ]);

        /*if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Bir seçim işlemi yapmadınız!', 'data' => $validator->errors()], 400);
        }*/

        $course_ids = $request->course_ids;
        $teacher_ids = $request->teacher_ids;

        $k = false;

        if (array_has_dupes($course_ids)) {
            return response(['status' => 'error', 'message' => 'Aynı ders üzerinde sadece bir öğretmen seçebilirsin!'], 400);
        }

        $user = $request->user();
        $userTeachers = UserTeacher::where(['user_id' => $user->id])->get();
        foreach ($userTeachers as $userTeacher) {
            $k = true;
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

        if ($k) {
            return response(['status' => 'success', 'message' => 'Seçtiğiniz dersler başarıyla güncellendi!']);
        } else {
            return response(['status' => 'success', 'message' => 'Dersler kaydedildi!']);
        }
    }

    public function teachers(Request $request)
    {
        $sort = $request->sort;
        if (!in_array($sort, ['highest_points', 'lowest_points', 'name', 'my_teachers', 'department'])) {
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
            case 'my_teachers':
                $teachers = collect();
                $userTeachers = UserTeacher::where(['user_id' => $request->user()->id])->get();
                foreach ($userTeachers as $userTeacher) {
                    $teacher = Teachers::find($userTeacher->teacherCourse->teacher_id);
                    $teachers->add($teacher);
                }

                $teachers = paginate($teachers, 10);
                break;
            case 'evaluated':
                $teachers = collect();
                $teacherVotes = TeacherVote::where(['user_id' => $request->user()->id])->get();
                foreach ($teacherVotes as $teacherVote) {
                    $teacher = Teachers::find($teacherVote->teacher_id);
                    $teachers->add($teacher);
                }
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
            'comment' => 'required|string|min:10|max:255',
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

        $teacherCourses = TeacherCourses::where(['teacher_id' => $teacher->id])->get();
        $courses = [];
        foreach ($teacherCourses as $teacherCourse) {
            $courses[] = $teacherCourse->id;
        }

        if (!UserTeacher::where('user_id', '=', $user->id)->whereIn('teacher_course_id', $courses)->exists()) {
            return response(['status' => 'error', 'message' => 'Bu öğretmene ait dersi almadığınız için yorum yapamazsınız!'], 400);
        }

        $teacherVote = TeacherVote::where(['teacher_id' => $teacher->id, 'user_id' => $user->id])->first();
        if (!$teacherVote) {
            $teacherVote = TeacherVote::create([
                'user_id' => $user->id,
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

        $courses = '';
        $teacherCourses = TeacherCourses::where(['teacher_id' => $teacher->id])->get();
        foreach ($teacherCourses as $teacherCours) {
            $courses .= $teacherCours->course->name . ', ';
        }
        $courses = rtrim($courses, ', ');

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

        if (TeacherVote::where(['teacher_id' => $id, 'user_id' => $user->id])->exists()) {
            $vote = TeacherVote::where(['teacher_id' => $id, 'user_id' => $user->id])->first();
        } else {
            $vote = null;
        }

        return response(['status' => 'success', 'data' => [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'title' => $courses,
            'is_admin' => (bool)$teacher->is_admin,
            'points' => $point,
            'color' => getColor($point),
            'qualityRate' => $qualityRate ?? 10,
            'qualityColor' => getColor($qualityRate ?? 10),
            'attitudeRate' => $attitudeRate ?? 10,
            'attitudeColor' => getColor($attitudeRate ?? 10),
            'performanceRate' => $performanceRate ?? 10,
            'performanceColor' => getColor($performanceRate ?? 10),
            'vote' => $vote
        ]]);
    }

    public function teacherReviews(Request $request, $id)
    {
        $user = $request->user();
        $teacher = Teachers::find($id);

        if (!$teacher) {
            return response(['status' => 'error', 'message' => 'Öğretmen bulunamadı!'], 400);
        }

        $votes = TeacherVote::where(['teacher_id' => $teacher->id])->with('user')->paginate(10);
        return TeacherVoteResource::collection($votes);
    }

    public function search(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'q' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Arama yapabilmek için en az 3 karakter girmelisiniz.', 'data' => $validator->errors()], 400);
        }

        $teachers = Teachers::where('name', 'like', '%' . $request->q . '%')->get();

        return UserTeacherResource::collection($teachers);
    }

    public function client(Request $request)
    {
        $user = $request->user();
        $myTeachersCount = UserTeacher::where('user_id', '=', $user->id)->count();
        $teachersCount = Teachers::count();
        $votesCount = TeacherVote::where(['user_id' => $user->id])->count();

        return response(['status' => 'success', 'data' => [
            'myTeachersCount' => $myTeachersCount,
            'teachersCount' => $teachersCount,
            'votesCount' => $votesCount
        ]]);
    }
}
