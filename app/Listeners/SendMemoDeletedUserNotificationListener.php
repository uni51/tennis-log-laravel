<?php

namespace App\Listeners;

use App\Mail\MemoDeletedByAdminToUser;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendMemoDeletedUserNotificationListener
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
     * @param object $event
     * @return void
     * @throws Exception
     */
    public function handle(object $event): void
    {
        $sendAddress = $event->user->email;

        try {
            // 送信先アドレスにメールを送信
            Mail::to($sendAddress)->send(new MemoDeletedByAdminToUser($event->content, $event->user, $event->memo));
        } catch (\Exception $e) {
            // メール送信に失敗した場合は、ログにエラーを出力
            logger()->error($e->getMessage());
            throw new Exception('メールの送信に失敗しました。');
        }
    }
}
