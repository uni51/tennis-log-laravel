<?php

namespace App\Services;

use App\Events\CreateMemoAdminNotificationEvent;
use App\Events\NotTennisRelatedAdminNotificationEvent;
use App\Models\Memo;
use App\Models\User;

class NotifyToAdminService
{
    /**
     * メモが投稿されたことを管理者にメール送信する
     *
     * @param array $validated
     * @param Memo $memo
     * @param User $user
     * @return void
     */
    public function notifyAdminCreateMemoEmail(array $validated, Memo $memo, User $user): void
    {
        $content = "<p>タイトル: {$validated['title']}</p>
<p>本文: {$validated['body']}</p>
<p>タグ: " . implode(', ', $validated['tags']) . "</p>";

        // テニスに関連のない記事の場合は、管理者にメール送信
        event(new CreateMemoAdminNotificationEvent($content, $user, $memo));
    }

    /**
     * テニスに関連のない記事ということを管理者にメール送信する
     *
     * @param array $validated
     * @param Memo $memo
     * @param User $user
     * @return void
     */
    public function notifyAdminNotTennisRelatedEmail(array $validated, Memo $memo, User $user): void
    {
        $content = "<p>タイトル: {$validated['title']}</p>
<p>本文: {$validated['body']}</p>
<p>タグ: " . implode(', ', $validated['tags']) . "</p>";

        // テニスに関連のない記事の場合は、管理者にメール送信
        event(new NotTennisRelatedAdminNotificationEvent($content, $user, $memo));
    }
}
