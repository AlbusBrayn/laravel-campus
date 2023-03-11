<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $pusher = new \Pusher\Pusher(config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        config('broadcasting.connections.pusher.options'));

    $pusher->trigger('campus-message', 'test-event', ['message' => 'hello world']);
    //event(new \App\Events\SendMessageEvent('Hello World', 10, 2));
});
