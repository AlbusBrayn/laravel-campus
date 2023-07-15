<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\School;
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
        $schools = School::all();

        return view('admin.pages.users.user-create', compact('schools'));
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'school_id' => 'required|int',
            'email' => 'required|email|unique:admins|max:255',
            'password' => 'required|string|max:255',
            're_password' => 'required|string|max:255|same:password',
        ]);

        $user = User::create([
            'school_id' => $request->school_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user) {
            return redirect()->route('admin.users')->with('success', 'Kullanıcı başarıyla oluşturuldu!');
        } else {
            return redirect()->route('admin.users')->with('error', 'Kullanıcı oluşturulurken bir hata oluştu!');
        }
    }

    public function update(User $user)
    {
        $schools = School::all();

        return view('admin.pages.users.user-update', compact('user', 'schools'));
    }

    public function updateStore(Request $request, User $user)
    {
        //
    }

    public function delete(User $user)
    {
        $user->is_active = false;
        $user->save();
        return redirect()->route('admin.users')->with('success', 'Kullanıcı başarıyla silindi!');
    }
}
