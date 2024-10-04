<?php

namespace App\Jobs;

use App\Lib\SendMailHelper;
use App\Mail\CreateMemoToAdminMail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCreateMemoAdminMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $content;
    protected $user;
    protected $memo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($content, $user, $memo)
    {
        $this->content = $content;
        $this->user = $user;
        $this->memo = $memo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $adminEmail = SendMailHelper::getAdminEmail();

        try {
            Mail::to($adminEmail)->send(new CreateMemoToAdminMail($this->content, $this->user, $this->memo));
        } catch (Exception $e) {
            logger()->error($e->getMessage());
            throw new Exception('メールの送信に失敗しました。');
        }
    }
}
