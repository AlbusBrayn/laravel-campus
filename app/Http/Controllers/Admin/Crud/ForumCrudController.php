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
        //
    }

    public function createStore(Request $request)
    {
        //
    }

    public function update(Admin $admin)
    {
        //
    }

    public function updateStore(Request $request, Admin $admin)
    {
        //
    }

    public function delete(Post $post)
    {
        $post->is_active = false;
        $post->save();
        return redirect()->route('admin.admins')->with('success', 'İçerik başarıyla silindi!');
    }
}
