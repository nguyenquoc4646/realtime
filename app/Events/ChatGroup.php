<?php

namespace App\Events;

use App\Models\GroupchatModel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatGroup implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $group;
    public $userSend;
    public $message;
    public function __construct(GroupchatModel $group, User $userSend,$message)
    {
        $this->group = $group;
        $this->userSend = $userSend;
        $this->message = $message;
    
    }

  
    public function broadcastOn()
    {
        return new PrivateChannel('chat.group.'.$this->group->id);
    }
}
