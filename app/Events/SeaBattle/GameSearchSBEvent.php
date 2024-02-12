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

// Ивент для создания нового чата и отправки сообщения
class GameSearchSBEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    private $enemyID;

    public function __construct(User $user, $enemyID)
    {
        $this->user = $user;
        $this->enemyID = $enemyID;
    }

    public function broadcastOn()
    {
        return new Channel('Game-search');
    }

    public function broadcastAs() 
    {
        return 'ConfirmConnection';
    }

    public function broadcastWith()
    {
        return [
            'userID' => $this->user->id,
            'enemyID' => $this->enemyID,
        ];
    }
}
