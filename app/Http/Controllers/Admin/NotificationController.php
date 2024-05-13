<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class NotificationController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.pages.send-notification', compact('users'));
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required',
            'users' => 'required|array',
            'users.*' => 'required|integer|exists:users,id'
        ]);

        $users = $request->users;

        foreach ($users as $user) {
            $user = User::findOrFail($user);
            if ($user->device_id) {
                FirebaseService::sendNotification($user->device_id, $request->title, $request->message);
            }
        }

        return redirect()->back()->with('success', 'Notification sent successfully.');
    }
}
