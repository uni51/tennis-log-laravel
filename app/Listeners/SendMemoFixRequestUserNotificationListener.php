<?php

namespace App\Listeners;

use App\Lib\DomainHelper;
use App\Mail\MemoFixRequestToUser;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendMemoFixRequestUserNotificationListener
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
     */
    public function handle(object $event): void
    {
        $sendAddress = $event->user->email;
        $domain = DomainHelper::getDomain();

        try {
            // 送信先アドレスにメールを送信
            Mail::to($sendAddress)->send(new MemoFixRequestToUser($event->content, $event->user, $event->memo, $domain));
        } catch (\Exception $e) {
            // メール送信に失敗した場合は、ログにエラーを出力
            logger()->error($e->getMessage());
            throw new Exception('メールの送信に失敗しました。');
        }
    }
}
