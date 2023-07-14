<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserCrudController extends Controller
{

    public function index()
    {
        $users = User::paginate(10);
        return view('admin.pages.users.users', compact('users'));
    }

    public function create()
    {
        dd('sss');
        //return view('admin.pages.admins.admin-create');
    }

    public function createStore(Request $request)
    {
        //
    }

    public function update(User $user)
    {
        //
    }

    public function updateStore(Request $request, User $user)
    {
        //
    }

    public function delete(User $user)
    {
        $user->status = false;
        $user->save();
        return redirect()->route('admin.users')->with('success', 'Kullanıcı başarıyla silindi!');
    }
}
