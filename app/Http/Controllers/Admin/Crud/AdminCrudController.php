<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminCrudController extends Controller
{

    public function index()
    {
        $admins = Admin::paginate(10);
        return view('admin.pages.admins.admins', compact('admins'));
    }

    public function create()
    {
        return view('admin.pages.admins.admin-create');
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins|max:255',
            'password' => 'required|string|max:255',
            're_password' => 'required|string|max:255|same:password',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        if ($admin) {
            return redirect()->route('admin.admins')->with('success', 'Admin başarıyla oluşturuldu!');
        } else {
            return redirect()->route('admin.admins')->with('error', 'Admin oluşturulurken hata!');
        }
    }

    public function update(Admin $admin)
    {
        return view('admin.pages.admins.admin-update', compact('admin'));
    }

    public function updateStore(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'. $admin->id .'|max:255',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if (isset($request->password)) {
            $admin->password = \Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('admin.admins')->with('success', 'Admin başarıyla güncellendi!');
    }

    public function delete(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.admins')->with('success', 'Admin başarıyla silindi!');
    }
}
