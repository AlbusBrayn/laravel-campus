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
        return view('admin.pages.forums.forum-title-create');
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = ["is_active" => isset($request->is_active)];
        $postTitle = PostTitle::create($data);

        if ($postTitle) {
            $postTitle->translateOrNew('tr')->title = $request->title;
            $postTitle->save();

            return redirect()->route('admin.forums.titles')->with('success', 'Forum başlığı başarıyla oluşturuldu!');
        } else {
            return redirect()->route('admin.forums.titles')->with('error', 'Forum başlığı oluşturulurken bir hata oluştu!');
        }
    }

    public function update(PostTitle $postTitle)
    {
        return view('admin.pages.forums.forum-title-update', compact('postTitle'));
    }

    public function updateStore(Request $request, PostTitle $postTitle)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $postTitle->is_active = isset($request->is_active);
        $postTitle->translateOrNew('tr')->title = $request->title;
        $postTitle->save();

        return redirect()->route('admin.forums.titles')->with('success', 'Forum başlığı başarıyla güncellendi!');
    }

    public function delete(PostTitle $postTitle)
    {
        $postTitle->is_active = false;
        $postTitle->save();
        return redirect()->route('admin.forums.titles')->with('success', 'Forum başlığı başarıyla silindi!');
    }
}
