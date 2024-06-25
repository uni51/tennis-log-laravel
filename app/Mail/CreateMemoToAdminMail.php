<?php

namespace App\Mail;

use App\Enums\CategoryType;
use App\Enums\MemoStatusType;
use App\Lib\DomainHelper;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateMemoToAdminMail extends Mailable
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

    public function build()
    {
        $domain = DomainHelper::getDomain();
        $statusLabel = MemoStatusType::getDescription($this->memo->status);
        $categoryDescription = CategoryType::getDescription($this->memo->category_id);

        return $this->subject('メモの新規投稿通知' . '(' . $statusLabel . ')')
            ->view('emails.to_admin.create_memo_notification')
            ->with([
                'content' => $this->content,
                'user'    => $this->user,
                'memo'    => $this->memo,
                'statusLabel' => $statusLabel,
                'categoryDescription' => $categoryDescription,
                'domain'  => $domain,
            ]);
    }
}
