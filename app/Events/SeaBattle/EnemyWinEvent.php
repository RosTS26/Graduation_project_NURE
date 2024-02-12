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

class EnemyWinEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $winnerID;
    private $loserID;

    public function __construct($winnerID, $loserID)
    {
        $this->winnerID = $winnerID;
        $this->loserID = $loserID;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Sea-battle-'.min($this->winnerID, $this->loserID).'-'.max($this->winnerID, $this->loserID));
    }

    public function broadcastAs() 
    {
        return 'EnemyWin';
    }

    public function broadcastWith()
    {
        return [
            'userID' => $this->winnerID,
            'data' => 1,
        ];
    }
}
