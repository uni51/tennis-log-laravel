<?php

namespace App\Listeners;

use App\Lib\DomainHelper;
use App\Lib\Environment;
use App\Lib\SendMailHelper;
use App\Mail\FixMemoToAdminMail;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendFixMemoAdminNotificationListener
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
        // 管理者メールアドレスを設定ファイルから取得
        $adminEmail = SendMailHelper::getAdminEmail();

        try {
            // 送信先アドレスにメールを送信
            Mail::to($adminEmail)->send(new FixMemoToAdminMail($event->content, $event->user, $event->memo));
        } catch (\Exception $e) {
            // メール送信に失敗した場合は、ログにエラーを出力
            logger()->error($e->getMessage());
            throw new Exception('メールの送信に失敗しました。');
        }
    }
}
