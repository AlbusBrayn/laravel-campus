<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\Major;
use App\Models\School;
use Illuminate\Http\Request;

class CourseCrudController extends Controller
{

    public function index()
    {
        $courses = Courses::paginate(15);

        return view('admin.pages.courses.courses', compact('courses'));
    }

    public function create()
    {
        $schools = School::all();
        $majors = Major::all();

        return view('admin.pages.courses.course-create', compact('schools', 'majors'));
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'school_id' => 'required|integer',
            'major_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $course = Courses::create([
            'school_id' => $request->school_id,
            'major_id' => $request->major_id,
            'name' => $request->name,
            'is_active' => isset($request->is_active),
        ]);

        if ($course) {
            return redirect()->back()->with('success', 'Ders başarıyla oluşturuldu!');
        } else {
            return redirect()->back()->with('error', 'Ders oluşturulurken bir hata oluştu!');
        }
    }

    public function update(Courses $course)
    {
        $schools = School::all();
        $majors = Major::all();

        return view('admin.pages.courses.course-update', compact('course', 'schools', 'majors'));
    }

    public function updateStore(Request $request, Courses $course)
    {
        $request->validate([
            'school_id' => 'required|integer',
            'major_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $course->school_id = $request->school_id;
        $course->major_id = $request->major_id;
        $course->name = $request->name;
        $course->is_active = isset($request->is_active);

        if ($course->save()) {
            return redirect()->back()->with('success', 'Ders başarıyla güncellendi!');
        } else {
            return redirect()->back()->with('error', 'Ders güncellenirken bir hata oluştu!');
        }
    }

    public function delete(Courses $course)
    {
        $course->is_active = false;
        $course->save();
        return redirect()->back()->with('success', 'Ders başarıyla silindi!');
    }
}
