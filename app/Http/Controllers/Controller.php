<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $view
     * @param array $data
     * @param string $email
     * @param string $subject
     * @return void
     */
    public function sendMail(string $view, array $data, string $email, string $subject): void
    {
        Mail::send($view, $data, function ($message) use ($email, $subject) {
            $message->to($email);
            $message->subject($subject);
        });
    }
}
