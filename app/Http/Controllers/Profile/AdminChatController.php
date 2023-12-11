<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\MessageFormRequest;
use App\Models\User;
use App\Models\AdminChat;
use App\Events\MessageSentToAdminEvent;
use App\Events\NewChatToAdminEvent;

class AdminChatController extends Controller
{
    // Загрузка чата
    public function loadChat() {
        $data = AdminChat::where('user_id', '=', auth()->user()->id)->first();
        if (!empty($data)) {
            $this->updateChat();
            return [
                'chat' => $data->chat,
                'newMsgs' => $data->new_msgs,
            ];
        }
        return 'error';
        //return empty($data) ? 'error' : json_encode($data->chat);
    }

    // Обновление чата
    public function updateChat() {
        $data = AdminChat::where('user_id', '=', auth()->user()->id)->first();

        // Проверка, существует ли чат вообще
        if (!empty($data)) {
            $chat = json_decode($data->chat);
            $newMsgs = json_decode($data->new_msgs);

            // Существует ли чат с новыми сообщениями, если да
            if (!empty($newMsgs)) {
                // Последнее сообщение не от нас, если да, обновляем чаты
                if (end($newMsgs)->id != auth()->user()->id) {
                    $chat = json_encode(array_merge($chat, $newMsgs));
                    $newMsgs = json_encode(array());
                    
                    $data->update([
                        'chat' => $chat,
                        'new_msgs' => $newMsgs,
                    ]);
                }
            }
        }
    }

    // Отправка сообщения от юзера 
    public function sendMsg(MessageFormRequest $request) {
        $data = $request->validated();
        // Формируем новое сообщение
        $messageData = [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'msg' => $data['message'],
            'time' => date('H:i')
        ];
        
        //Получаем данные с новыми сообщениями
        $data = AdminChat::where('user_id', '=', $request->user()->id)->first();

        // Если данных (чата) нету, создаем его ИНАЧЕ обновляем
        if (empty($data)) {
            AdminChat::create([
                'user_id' => $request->user()->id,
                'username' => $request->user()->name,
                'chat' => json_encode(array()),
                'new_msgs' => json_encode(array($messageData)),
            ]);

            // Вызов события создания нового чата по WebSocket
            broadcast(new NewChatToAdminEvent($request->user(), $messageData))->toOthers();

        } else {
            $newMsgs = json_decode($data->new_msgs);
            array_push($newMsgs, $messageData);
            // Обновляем новые сообщения
            $data->update([
                'new_msgs' => json_encode($newMsgs),
            ]);
            
            // Вызов события отправки сообщения по WebSocket
            broadcast(new MessageSentToAdminEvent($request->user(), $messageData))->toOthers();
        }

        // Возвращаем отправленное сообщение самому себе
        return $messageData;
    }
}
