<?php

namespace App\Events\SeaBattle;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SetSessionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    private $friend_id;
    private $gameID;

    public function __construct(User $user, $friend_id, $gameID)
    {
        $this->user = $user;
        $this->friend_id = $friend_id;
        $this->gameID = $gameID;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Sea-battle-'.min($this->friend_id, $this->user->id).'-'.max($this->friend_id, $this->user->id));
    }

    public function broadcastAs() 
    {
        return 'SetSessionData';
    }

    public function broadcastWith()
    {
        return [
            'gameID' => $this->gameID,
        ];
    }
}
