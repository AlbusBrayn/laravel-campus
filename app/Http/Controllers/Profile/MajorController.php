<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolMajor;
use Illuminate\Http\Request;

class MajorController extends Controller
{

    public function index(Request $request)
    {
        //@TODO: Multiple school control
        $school = School::first();
        if (!$school) {
            return response(['status' => 'error', 'message' => 'Sisteme kayıtlı bir okul bulunamadı!']);
        }

        $data = [];
        $majors = $school->schoolMajors;
        foreach ($majors as $major) {
            $data[] = $major->major;
        }

        return response(['majors' => $data]);
    }
}
