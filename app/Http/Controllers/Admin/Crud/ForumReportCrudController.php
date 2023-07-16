<?php

namespace App\Http\Controllers\Admin\Crud;

use App\Http\Controllers\Controller;
use App\Models\PostReport;
use App\Models\PostTitle;
use Illuminate\Http\Request;

class ForumReportCrudController extends Controller
{

    public function index()
    {
        $forumReports = PostReport::paginate(10);

        return view('admin.pages.forums.forum-reports', compact('forumReports'));
    }

    public function delete(PostReport $postReport)
    {
        $postReport->delete();
        return redirect()->route('admin.forums.reports')->with('success', 'Forum başlığı başarıyla silindi!');
    }
}
