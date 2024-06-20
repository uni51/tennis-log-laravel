<?php

namespace App\Services;

use App\Events\CreateMemoAdminNotificationEvent;
use App\Events\FixMemoAdminNotificationEvent;
use App\Events\NotTennisRelatedAdminNotificationEvent;
use App\Models\Memo;
use App\Models\User;

class NotifyToAdminService
{
    /**
     * メモが投稿されたことを管理者にメール送信する
     *
     * @param Memo $memo
     * @param User $user
     * @return void
     */
    public function notifyAdminCreateMemoEmail(Memo $memo, User $user): void
    {
        $content = $this->createContent($memo);

        // テニスに関連のない記事の場合は、管理者にメール送信
        event(new CreateMemoAdminNotificationEvent($content, $user, $memo));
    }

    /**
     * テニスに関連のない記事ということを管理者にメール送信する
     *
     * @param Memo $memo
     * @param User $user
     * @return void
     */
    public function notifyAdminNotTennisRelatedEmail(Memo $memo, User $user): void
    {
        $content = $this->createContent($memo);

        // テニスに関連のない記事の場合は、管理者にメール送信
        event(new NotTennisRelatedAdminNotificationEvent($content, $user, $memo));
    }

    /**
     * 修正依頼していたメモが適切に更新されたことを管理者にメール送信する
     *
     * @param Memo $memo
     * @param User $user
     * @return void
     */
    public function notifyAdminFixMemoEmail(Memo $memo, User $user): void
    {
        $content = $this->createContent($memo);

        // テニスに関連のない記事の場合は、管理者にメール送信
        event(new FixMemoAdminNotificationEvent($content, $user, $memo));
    }

    private function createContent(Memo $memo): string
    {
        $tags = $memo->tags->pluck('name')->toArray();
        $displayTags = !empty($tags) ? implode(",", $tags) : '';

        $content = "<p>タイトル: {$memo->title}</p>
<p>本文: {$memo->body}</p>
<p>タグ: " . $displayTags . "</p>";

        return $content;
    }
}
