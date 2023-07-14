<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\TeacherVote;
use App\Models\User;
use Illuminate\Http\Request;

class UserReviewCrudController extends Controller
{

    public function index()
    {
        $teacherVotes = TeacherVote::paginate(10);
        foreach ($teacherVotes as $teacherVote) {
            if (!User::where(['id' => $teacherVote->id])->exists()) {
                $teacherVote->delete();
            }
        }
        dd($teacherVotes);

        return view("admin.pages.users.user-reviews", compact("teacherVotes"));
    }

    public function delete(TeacherVote $review)
    {
        $review->delete();
        return redirect()->route('admin.users.reviews')->with('success', 'Kullanıcı değerlendirmesi başarıyla silindi!');
    }
}
