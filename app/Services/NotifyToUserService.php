<?php

namespace App\Services;

use App\Events\MemoFixRequestUserNotificationEvent;
use App\Models\Memo;
use App\Models\User;

class NotifyToUserService
{
    /**
     * 管理者がメモの修正必要と判断して、記事の掲載が一時停止になったことをユーザーに通知する
     *
     * @param Memo $memo
     * @param User $user
     * @return void
     */
    public function notifyUserMemoFixRequestEmail(Memo $memo, User $user): void
    {
        $tags = $memo->tags->pluck('name')->toArray();
        $displayTags = !empty($tags) ? implode(",", $tags) : '';

        $content = "<p>タイトル: {$memo->title}</p>
<p>本文: {$memo->body}</p>
<p>タグ: " . $displayTags . "</p>";

        // 管理者がメモの修正必要と判断して、記事の掲載が一時停止になったことをユーザーに通知する
        event(new MemoFixRequestUserNotificationEvent($content, $user, $memo));
    }
}
