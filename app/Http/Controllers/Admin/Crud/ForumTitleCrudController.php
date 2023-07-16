<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\PostTitle;
use Illuminate\Http\Request;

class ForumTitleCrudController extends Controller
{

    public function index()
    {
        $forumTitles = PostTitle::paginate(10);

        return view('admin.pages.forums.forum-titles', compact('forumTitles'));
    }

    public function create()
    {
        //
    }

    public function createStore(Request $request)
    {
        //
    }

    public function update(PostTitle $post)
    {
        //
    }

    public function updateStore(Request $request, PostTitle $post)
    {
        //
    }

    public function delete(PostTitle $postTitle)
    {
        $postTitle->is_active = false;
        $postTitle->save();
        return redirect()->route('admin.forums.titles')->with('success', 'Forum başlığı başarıyla silindi!');
    }
}
