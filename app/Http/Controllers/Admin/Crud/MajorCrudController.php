<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\School;
use Illuminate\Http\Request;

class MajorCrudController extends Controller
{

    public function index()
    {
        $majors = Major::paginate(10);

        return view('admin.pages.major.majors', compact('majors'));
    }

    public function create()
    {
        return view('admin.pages.major.major-create');
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $major = Major::create([
            'title' => $request->title,
            'is_active' => isset($request->is_active),
        ]);

        if ($major) {
            return redirect()->back()->with('success', 'Bölüm başarıyla oluşturuldu!');
        } else {
            return redirect()->back()->with('error', 'Bölüm oluşturulurken bir hata oluştu!');
        }
    }

    public function update(Major $major)
    {
        return view('admin.pages.major.major-update', compact('major'));
    }

    public function updateStore(Request $request, Major $major)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $major->title = $request->title;
        $major->is_active = isset($request->is_active);

        if ($major->save()) {
            return redirect()->back()->with('success', 'Bölüm başarıyla güncellendi!');
        } else {
            return redirect()->back()->with('error', 'Bölüm güncellenirken bir hata oluştu!');
        }
    }

    public function delete(Major $major)
    {
        $major->is_active = false;
        $major->save();
        return redirect()->back()->with('success', 'Bölüm başarıyla silindi!');
    }
}
