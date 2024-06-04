<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\CoursesImport;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }

    public function importExcel(Request $request)
    {
        Excel::import(new CoursesImport, $request->file('file'));

        return redirect('/')->with('success', 'Courses and associated teachers importedd successfully!');
    }
}
