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

class SeaBattleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    private $friend_id;
    private $data;

    public function __construct(User $user, $friend_id, $data)
    {
        $this->user = $user;
        $this->friend_id = $friend_id;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Sea-battle-'.min($this->friend_id, $this->user->id).'-'.max($this->friend_id, $this->user->id));
    }

    public function broadcastAs() 
    {
        // TEST
        return 'Test';
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->data,
        ];
    }
}
