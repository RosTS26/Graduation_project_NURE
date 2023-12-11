<?php

namespace App\Services\Messenger;

use App\Models\User;
use App\Models\Friend;
use App\Models\FriendlyChat;
use App\Models\BlockUserChat;
use Illuminate\Support\Facades\DB; // Подключение транзакций
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSentToFriendEvent;
use App\Events\deleteChatEvent;

class Service
{   
    // Возвращаем инфу про друзей
    public function getFriends() {
        $id = auth()->user()->id;
        $friendsList = json_decode(auth()->user()->friend->friends);
        $friends = [];

        foreach ($friendsList as $item) {
            $friendDB = User::find($item->id);
            // Получаем кол-во новых сообщений и последнее сообщение от пользователя
            $friendlyChat = FriendlyChat::where('user_id', $id)->where('friend_id', $item->id)->first();
            $numNewMsgs = count(json_decode($friendlyChat->new_msgs));
            $numChat = count(json_decode($friendlyChat->chat));

            if ($numNewMsgs > 0) $msgData = json_decode($friendlyChat->new_msgs);
            else if ($numChat > 0) $msgData = json_decode($friendlyChat->chat);
            else $msgData = [(object)['msg' => '']];

            $lastMsg = end($msgData)->msg;

            if ($friendDB) {
                $itemData = [
                    'id' => $friendDB->id,
                    'name' => $friendDB->name,
                    'avatar' => $friendDB->avatar,
                    'online' => $friendDB->online,
                    'numNewMsgs' => $numNewMsgs,
                    'lastMsg' => $lastMsg,
                ];

                array_push($friends, $itemData);
            }
        }

        return $friends;
    }

    // Проверка на блокировку чата
    public function checkBlockChat() {

        $currentDateTime = new \DateTime();
        $userBlockInfo = BlockUserChat::where('user_id', '=', auth()->user()->id)->get();

        if (count($userBlockInfo) > 0) {
            foreach ($userBlockInfo as $row) {
                $end_date = \DateTime::createFromFormat('Y-m-d H:i:s', $row['end_date']);

                // Код ошибки, если есть актуальная блокировка
                if ($currentDateTime < $end_date) return $row; // Чат заблокирован
            }
        } else {
            return 0; // Блокировок нету
        }
    }

    // Обновление чата
    public function updateChat($friend_id) {
        try {
            $id = auth()->user()->id;
            $data = FriendlyChat::where('user_id', $id)->where('friend_id', $friend_id)->first();
    
            if (!empty($data)) {
                // Добавляем к основному чату новые сообщения
                $chat = array_merge(json_decode($data->chat), json_decode($data->new_msgs));
                
                DB::beginTransaction();
                $data->update([
                    'chat' => json_encode($chat),
                    'new_msgs' => json_encode(array()),
                ]);
                Db::commit();
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            Db::rollBack();
            return 0;
        }

        return 1;
    }

    // Возвращаем основной чат
    public function getChat($friend_id) {
        $id = auth()->user()->id; 
        $data = FriendlyChat::where('user_id', $id)->where('friend_id', $friend_id)->first();
        
        if (!empty($data)) {
            // Обновляем чат с новыми сообщениями
            $this->updateChat($friend_id);
            // Возвращаем чат
            return [
                'chat' => $data->chat,
                'newMsgs' => $data->new_msgs,
            ];
        } else {
            return 0; // Чата не существует (ошибка)
        }
    }

    // Удаление чата
    public function deleteChat($friend_id) {
        try {
            $myId = auth()->user()->id;
            $myDB = FriendlyChat::where('user_id', $myId)->where('friend_id', $friend_id)->first();
            $friendDB = FriendlyChat::where('user_id', $friend_id)->where('friend_id', $myId)->first();
            
            // Удаляем чаты, если они существуют
            if ($myDB && $friendDB) {
                DB::beginTransaction();
                $myDB->update([
                    'chat' => json_encode([]),
                    'new_msgs' => json_encode([]),
                ]);

                $friendDB->update([
                    'chat' => json_encode([]),
                    'new_msgs' => json_encode([]), 
                ]);
                Db::commit();

                // Оправляем websocket про удаление чата
                broadcast(new DeleteChatEvent(auth()->user(), $friend_id))->toOthers();

            } else return 1; // Ошибка

        } catch(\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
            // return 1;
        }

        return 0; // Операция успешна
    }

    // Отправка сообщения
    public function sendMsg($friend_id, $message) { 
        try {
            // Проверка блокировку чата
            if ($this->checkBlockChat()) return 2;
            
            $id = auth()->user()->id;
            $myDB = FriendlyChat::where('user_id', $id)->where('friend_id', $friend_id)->first();
            $friendDB = FriendlyChat::where('user_id', $friend_id)->where('friend_id', $id)->first();
            
            // Если чаты существуют, проводим запись нового сообщения
            if (!empty($myDB) && !empty($friendDB)) {
                $msgData = [
                    'id' => $id,
                    'name' => auth()->user()->name,
                    'msg' => $message,
                    'time' => date('H:i'),
                ];

                $myChat = json_decode($myDB->chat);
                $friendNewMgs = json_decode($friendDB->new_msgs);
                array_push($myChat, $msgData);
                array_push($friendNewMgs, $msgData);

                // Обновляем чаты пользователей
                DB::beginTransaction();
                $myDB->update([
                    'chat' => json_encode($myChat),
                ]);

                $friendDB ->update([
                    'new_msgs' => json_encode($friendNewMgs),
                ]);
                Db::commit();

                // Вызов события отправки сообщения по Web-Socket (toOthers - кроме отправителя)
                broadcast(new MessageSentToFriendEvent(auth()->user(), $friend_id, $msgData))->toOthers();
    
                return $msgData;

            } else {
                return 0; // Чат не существует
            }
        } catch(\Exception $e) {
            Db::rollBack();
            // return $e->getMessage();
            return 1; // Ошибка
        }
    }
}