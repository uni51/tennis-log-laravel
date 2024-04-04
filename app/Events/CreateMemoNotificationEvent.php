<?php
namespace App\Events;

use App\Models\Memo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CreateMemoNotificationEvent
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

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel
     */
    public function broadcastOn(): Channel|PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
