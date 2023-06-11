<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{

    public function index()
    {
        $totalPlayers = User::count();
        $totalServers = Server::count();
        $onlinePlayers = 0;
        foreach (Server::where(['is_active' => true, 'is_online' => true, 'is_whitelisted' => false])->get() as $server) {
            $onlinePlayers += $server->online_players;
        }
        return view('admin.dashboard', compact('totalPlayers', 'totalServers', 'onlinePlayers'));
    }
}
