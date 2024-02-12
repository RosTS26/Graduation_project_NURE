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

class EnemyReadyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    private $friend_id;
    private $moveId;

    public function __construct(User $user, $friend_id, $moveId)
    {
        $this->user = $user;
        $this->friend_id = $friend_id;
        $this->moveId = $moveId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Sea-battle-'.min($this->friend_id, $this->user->id).'-'.max($this->friend_id, $this->user->id));
    }

    public function broadcastAs() 
    {
        return 'EnemyReady';
    }

    public function broadcastWith()
    {
        return [
            'moveId' => $this->moveId,
        ];
    }
}
