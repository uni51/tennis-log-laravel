<?php

namespace App\Mail;

use App\Enums\MemoStatusType;
use App\Enums\CategoryType;
use App\Lib\DomainHelper;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Memo;

class MemoFixRequestToUser extends Mailable
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
    public function __construct(string $content, User $user, Memo $memo)
    {
        $this->content = $content;
        $this->user = $user;
        $this->memo = $memo;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $serviceName = config('services.name');
        $domain = DomainHelper::getDomain();
        $statusLabel = MemoStatusType::getDescription($this->memo->status);
        $categoryDescription = CategoryType::getDescription($this->memo->category_id);

        return $this->subject('【'. $serviceName .'】メモの修正リクエストが届いています。')
            ->view('emails.memo_fix_request')
            ->with([
                'content' => $this->content,
                'user'    => $this->user,
                'memo'    => $this->memo,
                'statusLabel' => $statusLabel,
                'categoryDescription' => $categoryDescription,
                'domain'  => $domain,
                'serviceName' => $serviceName,
            ]);
    }
}
