<?php

namespace App\Mail;

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

        return $this->subject('【'. $serviceName .'】メモの修正リクエストが届いています。')
            ->view('emails.memo_fix_request')
            ->with([
                'content' => $this->content,
                'user'    => $this->user,
                'memo'    => $this->memo,
                'domain'  => $this->domain,
                'serviceName' => $serviceName,
            ]);
    }
}
