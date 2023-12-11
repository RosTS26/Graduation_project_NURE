<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Ивент отправки сообщения от админа к пользователю
class MessageSentToUserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $userId;
    private $message;

    public function __construct($userId, $message)
    {
        $this->userId = $userId;
        $this->message = $message;
    }

    // Название канала
    public function broadcastOn()
    {
        return new PrivateChannel('User-chat-'.$this->userId);
    }

    // Название события для данного канала
    public function broadcastAs() 
    {
        return 'MessageSent';
    }

    // Данные для отправки по WS
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}
