<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotTennisRelatedMail;

class SendNotTennisRelatedNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // 管理者メールアドレス TODO: 管理者のメールアドレスを要変更
        $adminEmail = 'admin@example.com';
        // 送信先アドレスにメールを送信
        Mail::to($adminEmail)->send(new NotTennisRelatedMail($event->content));
    }
}
