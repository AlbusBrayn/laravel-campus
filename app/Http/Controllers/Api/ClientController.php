<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function reportList(Request $request)
    {
        return response(['status' => 'success', 'reasons' => getReportReasons()]);
    }
}
