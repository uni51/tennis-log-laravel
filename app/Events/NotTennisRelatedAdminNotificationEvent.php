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

class NotTennisRelatedAdminNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $content;
    public User $user;
    public string $actionType;
    public Memo $memo;

    /**
     *  Create a new event instance.
     *
     * @param string $content
     * @param User $user
     * @param string $actionType
     * @param Memo|null $memo
     */
    public function __construct(string $content, User $user, string $actionType, ?Memo $memo)
    {
        $this->content = $content;
        $this->user = $user;
        $this->actionType = $actionType;
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
