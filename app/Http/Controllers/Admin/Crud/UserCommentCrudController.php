<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class UserCommentCrudController extends Controller
{

    public function index()
    {
        $comments = Comment::paginate(10);

        return view("admin.pages.users.user-comments", compact("comments"));
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.users.comments')->with('success', 'Kullanıcı yorumu başarıyla silindi!');
    }
}
