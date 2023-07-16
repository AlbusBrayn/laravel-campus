<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Post;
use Illuminate\Http\Request;

class ForumCrudController extends Controller
{

    public function index()
    {
        $forums = Post::paginate(10);

        return view('admin.pages.forums.forums', compact('forums'));
    }

    public function create()
    {
        return back();
    }

    public function createStore(Request $request)
    {
        return back();
    }

    public function update(Post $post)
    {
        return view('admin.pages.forums.forum-update', compact('post'));
    }

    public function updateStore(Request $request, Post $post)
    {
        //
    }

    public function delete(Post $post)
    {
        $post->is_active = false;
        $post->save();
        return redirect()->route('admin.forums')->with('success', 'İçerik başarıyla silindi!');
    }
}
