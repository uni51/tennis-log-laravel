<?php

namespace App\Mail;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotTennisRelatedToAdminMail extends Mailable
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

    public function build()
    {
        return $this->subject('テニスに関連しないメモの投稿通知')
            ->view('emails.to_admin.not_tennis_related_notification')
            ->with([
                'content' => $this->content,
                'user'    => $this->user,
                'memo'    => $this->memo,
                'domain'  => $this->domain,
            ]);
    }
}
