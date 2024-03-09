<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Memo;

class MemoEditRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $memo;

    /**
     * Create a new message instance.
     *
     * @param Memo  $memo
     * @return void
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.memoEditRequest')
            ->with([
                'memoTitle' => $this->memo->title,
                'userName' => $this->memo->user->name,
            ])
            ->subject('【Tennisノート】メモの修正リクエストが届いています。');
    }
}
