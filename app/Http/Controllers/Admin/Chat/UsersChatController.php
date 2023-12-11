<?php

namespace App\Http\Controllers\Admin\Chat;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageFormRequest;
use App\Http\Requests\Admin\UserIdRequest;
use App\Models\User;
use App\Models\AdminChat;
use App\Events\MessageSentToUserEvent;

class UsersChatController extends Controller
{
     // Загрузка чата
     public function loadChat(UserIdRequest $request) {
        $user_id = $request->validated();
        $data = AdminChat::where('user_id', '=', $user_id)->first();
        if (!empty($data)) {
            $this->updateChat($request);
            return [
                'chat' => $data->chat,
                'newMsgs' => $data->new_msgs,
            ];
        }
        return 'error';
    }

    // Обновление чата
    public function updateChat(UserIdRequest $request) {
        $user_id = $request->validated();
        $data = AdminChat::where('user_id', '=', $user_id)->first();

        // Проверка, существует ли чат вообще
        if (!empty($data)) {
            $chat = json_decode($data->chat);
            $newMsgs = json_decode($data->new_msgs);

            // Существует ли чат с новыми сообщениями, если да
            if (!empty($newMsgs)) {
                // Если cообщение не от нас, обновляем чаты
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

    // Отправка сообщения юзеру 
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
        $res = AdminChat::where('user_id', '=', $request->user_id)->first();

        // Обновляем чат, если он существует, инчае ошибка
        if (!empty($res)) {
            $newMsgs = json_decode($res->new_msgs);
            array_push($newMsgs, $messageData);
            // Обновляем новые сообщения
            $res->update([
                'new_msgs' => json_encode($newMsgs),
            ]);
        } else {
            return 'error';
        }

        // Вызов события отправки сообщения по WebSocket
        broadcast(new MessageSentToUserEvent($data['user_id'], $messageData))->toOthers();

        // Возвращаем отправленное сообщение самому себе
        return $messageData;
    }
}
