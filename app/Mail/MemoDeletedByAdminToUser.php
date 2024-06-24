<?php

namespace App\Mail;

use App\Enums\CategoryType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Memo;

class MemoDeletedByAdminToUser extends Mailable
{
    use Queueable, SerializesModels;

    private string $content;
    private User $user;
    private Memo $memo;
    private string $domain;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $content, User $user, Memo $memo, string $domain)
    {
        $this->content = $content;
        $this->user = $user;
        $this->memo = $memo;
        $this->domain = $domain;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $serviceName = config('services.name');
        $categoryDescription = CategoryType::getDescription($this->memo->category_id);

        return $this->subject('【'. $serviceName .'】管理者によるメモの削除通知')
            ->view('emails.memo_deleted_by_admin')
            ->with([
                'content' => $this->content,
                'user'    => $this->user,
                'memo'    => $this->memo,
                'domain'  => $this->domain,
                'serviceName' => $serviceName,
                'categoryDescription' => $categoryDescription,
            ]);
    }
}
