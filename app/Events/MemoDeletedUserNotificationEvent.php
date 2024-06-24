<?php
namespace App\Events;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemoDeletedUserNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $content;
    public User $user;
    public Memo $memo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $content, User $user, ?Memo $memo)
    {
        $this->content = $content;
        $this->user = $user;
        $this->memo = $memo;
    }

    public function broadcastOn(): Channel|PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
