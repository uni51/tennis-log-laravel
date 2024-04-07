<?php

namespace App\Services;

use App\Events\MemoFixRequestUserNotificationEvent;
use App\Models\Memo;
use App\Models\User;

class NotifyToUserService
{
    /**
     * メモが投稿されたことを管理者にメール送信する
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

        // テニスに関連のない記事の場合は、管理者にメール送信
        event(new MemoFixRequestUserNotificationEvent($content, $user, $memo));
    }
}
