<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolCrudController extends Controller
{

    public function index()
    {
        $schools = School::paginate(10);

        return view('admin.pages.schools.schools', compact('schools'));
    }

    public function create()
    {
        return view('admin.pages.schools.school-create');
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email_pattern' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'latitude_delta' => 'required|string|max:255',
            'longitude_delta' => 'required|string|max:255',
        ]);

        $school = School::create([
            'name' => $request->name,
            'email_pattern' => $request->email_pattern,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'latitude_delta' => $request->latitude_delta,
            'longitude_delta' => $request->longitude_delta,
            'is_active' => isset($request->is_active),
        ]);

        if ($school) {
            return redirect()->back()->with('success', 'Okul başarıyla oluşturuldu!');
        } else {
            return redirect()->back()->with('error', 'Okul oluşturulurken bir sorun oluştu!');
        }
    }

    public function update(School $school)
    {
        return view('admin.pages.schools.school-update', compact('school'));
    }

    public function updateStore(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email_pattern' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'latitude_delta' => 'required|string|max:255',
            'longitude_delta' => 'required|string|max:255',
        ]);

        $school->name = $request->name;
        $school->email_pattern = $request->email_pattern;
        $school->latitude = $request->latitude;
        $school->longitude = $request->longitude;
        $school->latitude_delta = $request->latitude_delta;
        $school->longitude_delta = $request->longitude_delta;
        $school->is_active = isset($request->is_active);

        if ($school->save()) {
            return redirect()->back()->with('success', 'Okul başarıyla güncellendi!');
        } else {
            return redirect()->back()->with('error', 'Okul güncellenirken bir hata oluştu!');
        }
    }

    public function delete(School $school)
    {
        $school->is_active = false;
        $school->save();

        return redirect()->back()->with('success', 'Okul başarıyla silindi!');
    }
}
