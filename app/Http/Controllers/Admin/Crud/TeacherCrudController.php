<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\Teachers;
use Illuminate\Http\Request;

class TeacherCrudController extends Controller
{

    public function index()
    {
        $teachers = Teachers::paginate(10);

        return view('admin.pages.teachers.teachers', compact('teachers'));
    }

    public function create()
    {
        return view('admin.pages.teachers.teacher-create');
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $teacher = Teachers::create([
            'name' => $request->name
        ]);

        if ($teacher) {
            return redirect()->back()->with('success', 'Öğretmen başarıyla oluşturuldu!!');
        } else {
            return redirect()->back()->with('error', 'Öğretmen oluşturulurken bir hata oluştu!');
        }
    }

    public function update(Teachers $teacher)
    {
        return view('admin.pages.teachers.teacher-update', compact('teacher'));
    }

    public function updateStore(Request $request, Teachers $teacher)
    {
        $request->validate([
            'name ' => 'required|string|max:255',
        ]);

        $teacher->name = $request->name;
        $teacher->is_active = isset($request->is_active);

        if ($teacher->save()) {
            return redirect()->back()->with('success', 'Öğretmen başarıyla güncellendi!');
        } else {
            return redirect()->back()->with('error', 'Öğretmen güncellenirken bir hata oluştu!');
        }
    }

    public function delete(Teachers $teacher)
    {
        $teacher->is_active = false;
        $teacher->save();
        return redirect()->back()->with('success', 'Öğretmen başarıyla silindi!');
    }
}
