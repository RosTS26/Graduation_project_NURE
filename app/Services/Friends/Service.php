<?php

namespace App\Services\Friends;

use App\Models\User;
use App\Models\Friend;
use App\Models\FriendlyChat;
use Illuminate\Support\Facades\DB; // Подключение транзакций
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Service
{   
    // Метод возвращающий информацию о пользователях в БД "friends"
    private function getFriendsInfo($users) {
        $friendsData = [];
        foreach ($users as $item) {
            $friendDB = User::find($item->id);

            if ($friendDB) {
                $itemData = [
                    'id' => $friendDB->id,
                    'name' => $friendDB->name,
                    'avatar' => $friendDB->avatar,
                    'online' => $friendDB->online,
                ];

                array_push($friendsData, $itemData);
            }
        }

        return $friendsData;
    }

    // Создание двух общих чатов для пользователей, которые в друзьях
    private function createChats() {

    }

    // ====================================================================

    // Кол-во входных заявок в друзья
    public function getNumIncomApp() {
        // Получаем массив с заявками
        $incomApp = json_decode(auth()->user()->friend->incoming_app);
        // Возвращаем кол-во заявок
        return count($incomApp);
    }

    // Метод для выбраной опции (мои друзья, отправленные заявки, входящие заявки)
    public function option($option) {
        // Получаем данные о друзьях
        $friendDB = auth()->user()->friend;

        // Отдаем клиенту информацию, которую он запросил
        switch ($option) {
            case 'friends':
                return $this->getFriendsInfo(json_decode($friendDB->friends));
            case 'sentApp':
                return $this->getFriendsInfo(json_decode($friendDB->sent_app));
            case 'incomApp':
                return $this->getFriendsInfo(json_decode($friendDB->incoming_app));
            default:
                return 'error';
        }
    }

    // Поиск новых друзей по имени
    public function findFriends($username) {
        // Ищем новых друзей
        $findFriends = User::where('name', 'LIKE', $username . '%')->get();

        $friendDB = auth()->user()->friend; // Получаем данные о друзьях

        $decodedFriends = json_decode($friendDB->friends, true);
        $decodedSent = json_decode($friendDB->sent_app, true);
        //$decodedIncom = json_decode($friendDB->incoming_app, true);

        $friendDB = [
            'friends' => [],
            'sent_app' => [],
            'incoming_app' => [],
        ];

        if ($decodedFriends) $friendDB['friends'] = array_column($decodedFriends, 'id');
        if ($decodedSent) $friendDB['sent_app'] = array_column($decodedSent, 'id');
        //if ($decodedIncom) $friendDB['incoming_app'] = array_column($decodedIncom, 'id');

        $usersInfo = [];
        foreach ($findFriends as $user) {
            // Не учитываем зарегестрированного пользователя
            if ($user->id === auth()->user()->id) continue;

            // Определяем статус пользователя
            if (in_array($user->id, $friendDB['friends'])) $status = 1; // Статус друг
            else if (in_array($user->id, $friendDB['sent_app'])) $status = 2; // Статус заявка отправлена
            //else if (in_array($user->id, $friendDB['incoming_app'])) $status = 3; // Заявка получена
            else $status = 0; // Статус по умолчанию (0 = не друг)

            $itemData = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'status' => $status,
            ];

            array_push($usersInfo, $itemData);
        }

        return $usersInfo;
    }

    // Добавление в друзья
    public function addFriend($user_id) {
        // Проверка, сущесвует ли пользователь
        $user = User::find($user_id);
        if (!$user || $user_id == auth()->user()->id) return 4; // Ошибка

        $friendDB = auth()->user()->friend; // БД клиента

        // БД текущего пользователя
        $friends = json_decode($friendDB->friends);
        $sentApp = json_decode($friendDB->sent_app);
        $incomApp = json_decode($friendDB->incoming_app);

        // БД друга (второго пользователя)
        $hisFriends = json_decode($user->friend->friends);
        $hisSentApp = json_decode($user->friend->sent_app);
        $hisIncomApp = json_decode($user->friend->incoming_app);

        try {
            // Проверяем, является ли пользователь другом
            foreach ($friends as $item) {
                if ($item->id == $user_id) return 2;
            }

            // Проверяем, была ли заявка уже отправлена
            foreach ($sentApp as $item) {
                if ($item->id == $user_id) return 3; // Ошибка
            }
            
            // Проверяем, была ли отправлена заявка от пользователя
            // Если да, то принимаем эту заявку в друзья
            foreach ($incomApp as $key => $item) {
                if ($item->id == $user_id) {
                    // БД текущего клиента
                    unset($incomApp[$key]); // Удаляем входящую заявку
                    array_push($friends, ['id' => $user_id]); // Добавляем пользователя в друзья
                    
                    Db::beginTransaction();
                    $friendDB->update([
                        'friends' => json_encode($friends),
                        'incoming_app' => json_encode(array_values($incomApp)),
                    ]);
                    
                    // БД отправителя заявки
                    foreach ($hisSentApp as $key2 => $item2) {
                        if ($item2->id == auth()->user()->id) {
                            unset($hisSentApp[$key2]);
                            array_push($hisFriends, ['id' => auth()->user()->id]);
                            $user->friend->update([
                                'friends' => json_encode($hisFriends),
                                'sent_app' => json_encode(array_values($hisSentApp)),
                            ]);
                        }
                    }

                    // Создание двух чатов, если они отсутсвуют
                    // Чат пользователя с другом
                    FriendlyChat::firstOrCreate(
                        ['user_id' => auth()->user()->id, 'friend_id' => $user_id],
                        ['chat' => json_encode([]), 'new_msgs' => json_encode([])]
                    );
                    // Копия чата для друга
                    FriendlyChat::firstOrCreate(
                        ['user_id' => $user_id, 'friend_id' => auth()->user()->id],
                        ['chat' => json_encode([]), 'new_msgs' => json_encode([])]
                    );

                    Db::commit();
                    return 1; // Принимаем заявку
                }
            }

            // Отправляем первыми заявку в друзья
            array_push($sentApp, ['id' => $user_id]);
            array_push($hisIncomApp, ['id' => auth()->user()->id]);

            Db::beginTransaction();
            $friendDB->update([
                'sent_app' => json_encode($sentApp),
            ]);

            $user->friend->update([
                'incoming_app' => json_encode($hisIncomApp),
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            // return $e->getMessage();
            return 4; // Ошибка
        }

        return 0; // Отправляем заявку
    }

    // Отмена заявки в друзья
    // Метод для отмены sent и incoming заявок одновременно
    public function cancelApp(User $forSentDB, User $forIncomDB) {

        $sentApp = json_decode($forSentDB->friend->sent_app);
        $incomApp = json_decode($forIncomDB->friend->incoming_app);

        try {
            foreach ($sentApp as $key => $item) {
                // Если заявка в друзья присутсвует, удаляем ее
                if ($item->id == $forIncomDB->id) {
                    unset($sentApp[$key]);

                    Db::beginTransaction();
                    $forSentDB->friend->update([
                        'sent_app' => json_encode(array_values($sentApp)),
                    ]);
    
                    foreach ($incomApp as $key2 => $item2) {
                        if ($item2->id == $forSentDB->id) {
                            unset($incomApp[$key2]);
                            $forIncomDB->friend->update([
                                'incoming_app' => json_encode(array_values($incomApp)),
                            ]);
                        }
                    }
                    
                    Db::commit();
                    return 3; // Операция успешна
                }
            }
        } catch (\Exception $e) {
            Db::rollBack();
            // return $e->getMessage();
            return 0; // error
        }

        return 1; // Заявки нету
    }

    // Удалить из друзей
    public function deleteFriend($userDB) {
        $myFriendsDB = auth()->user()->friend;
        $hisFriendsDB = $userDB->friend;

        $myFriends = json_decode($myFriendsDB->friends);
        $hisFriends = json_decode($hisFriendsDB->friends);

        try {
            // Удаляем друга из друзей (если он есть в друзьях)
            foreach ($myFriends as $key => $item) {
                if ($item->id == $userDB->id) {
                    unset($myFriends[$key]);

                    Db::beginTransaction();
                    $myFriendsDB->update([
                        'friends' => json_encode(array_values($myFriends)),
                    ]);

                    foreach ($hisFriends as $key2 => $item2) {
                        if ($item2->id == auth()->user()->id) {
                            unset($hisFriends[$key2]);

                            $hisFriendsDB->update([
                                'friends' => json_encode(array_values($hisFriends)),
                            ]);
                        }
                    }
                    
                    Db::commit();
                    return 4;
                }
            }

            return 2; // Пользователя в друзьях нету

        } catch (\Exception $e) {
            Db::rollBack();
            //return $e->getMessage();
            return 0;
        }
    }
}