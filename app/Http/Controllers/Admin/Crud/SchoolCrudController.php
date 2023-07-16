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
        //
    }

    public function createStore(Request $request)
    {
        //
    }

    public function update(School $school)
    {
        //
    }

    public function updateStore(Request $request, School $school)
    {
        //
    }

    public function delete(School $school)
    {
        //
    }
}
