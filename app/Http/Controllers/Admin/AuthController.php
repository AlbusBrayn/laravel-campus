<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login()
    {
        return view("admin.auth.login");
    }

    public function loginStore(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (\Auth::guard("admin")->attempt(["email" => $request->email, "password" => $request->password])) {
            $user = auth()->guard('admin')->user();
            $user->last_login = Carbon::now();
            $user->ip_address = $request->ip();
            $user->save();

            return redirect()->route("admin.dashboard")->with("success", "You are loggedd in successfully!");
        } else {
            return back()->with("error", "Whoops! invalid email or password.");
        }
    }

    public function changePassword()
    {
        return view("admin.pages.change-password");
    }

    public function changePasswordStore(Request $request)
    {
        $request->validate([
            "old_password" => "required",
            "password" => "required|string|min:8",
        ]);

        $user = auth()->guard('admin')->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route("admin.dashboard")->with("success", "Password changed successfully!");
        } else {
            return back()->with("error", "Whoops! invalid old password.");
        }
    }

    public function adminLogout(Request $request)
    {
        auth()->guard('admin')->logout();
        \Session::flush();
        \Session::flash('success', 'You are logged out successfully!');
        return redirect()->route('admin.login');
    }
}
