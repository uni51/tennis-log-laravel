<?php

namespace App\Http\Controllers;

use App\Mail\SendTestMail;
use Illuminate\Support\Facades\Mail;

class MailSendTestController extends Controller
{
    public function send() {

        $to = [
            [
                'email' => 'XXXXX@XXXXX.jp',
                'name' => 'Test',
            ]
        ];

        Mail::to($to)->send(new SendTestMail());

    }
}
