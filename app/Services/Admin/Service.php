<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\UserBan;
use App\Models\BlockUserChat;
use App\Models\AdminChat;
use Illuminate\Support\Facades\DB; // Подключение транзакций
use Illuminate\Support\Facades\Auth;

class Service {   
    // Возврат информации про пользователей
    private function getUsersInfo($data) {
        $processedData = [];

        // Проверка кол-ва новых сообщений
        foreach ($data as $chat) {
            $num = 0;

            $newMsgs = json_decode($chat->new_msgs); // Получаем чат с новыми сообщениями
            end($newMsgs); // Ставим указатель на последний элемент чата
            // Если последнее сообщение от админа или сообщений нету, пропускаем
            if (current($newMsgs)) {
                if (current($newMsgs)->id != auth()->user()->id) $num = count($newMsgs);
            }

            $processedData[] = [
                'user_id' => $chat->user_id,
                'username' => $chat->username,
                'numNewMsgs' => $num,
            ];
        }

        // Сортируем и поднимаем вверх списка тех, у кого новые сообщения
        usort($processedData, function ($a, $b) {
            return $b['numNewMsgs'] - $a['numNewMsgs'];
        });

        return $processedData;
    }

    // Проверка существуют ли любые актуальные блокировки у пользователя
    private function checkBan($banInfo) {
        $currentDateTime = new \DateTime();

        if (count($banInfo) > 0) {
            foreach ($banInfo as $row) {
                $end_date = \DateTime::createFromFormat('Y-m-d H:i:s', $row['end_date']);

                // Возвращаем данные про актуальную блокировку
                if ($currentDateTime < $end_date) return $row;
            }
        }

        return false;
    }

    // ====================================================================

    // Возврат всех чатов с игроками
    public function getUsersChat() {
        $data = AdminChat::all('user_id', 'username', 'new_msgs');
        return $this->getUsersInfo($data);
    }

    // Поиск чатов игроков
    public function searchChat($search) {
        try {
            DB::beginTransaction();

            if ($search === '') {
                $users = AdminChat::all('user_id', 'username', 'new_msgs');
            } else {
                $users = AdminChat::where('username', 'like', '%'. $search .'%')->get();
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return [];
            //return $e->getMessage();
        }

        return $this->getUsersInfo($users);
    }

    // Поиск юзера по его имени
    public function searchUsers($search) {
        $users = User::where('name', 'like', '%'. $search .'%')->get();
        $processedUsers = [];

        foreach ($users as $user) {
            $processedUsers[] = [
                'user_id' => $user->id,
                'username' => $user->name,
            ];
        }

        return $processedUsers;
    }

    // Блокировка юзера
    public function userBan(User $user, $data) {
        // Проверка на дурака
        if (auth()->user()->id === $user->id) {
            return [
                'status' => 1,
                'msg' => "You can't banned yourself!",
            ];
        }
        // Есть ли причина, дефолт = "ban"
        if (empty($data['cause'])) $data['cause'] = 'ban';

        try {
            DB::beginTransaction();

            $currentDateTime = new \DateTime();
            $userBanInfo = UserBan::where('user_id', '=', $user->id)->get();

            // Проверка на блокировку
            if ($this->checkBan($userBanInfo)) {
                return [
                    'status' => 1,
                    'msg' => "User ". $user->name ." is already banned!",
                ];
            }
    
            $banDateTime = new \DateTime();
            $banDateTime->modify('+'. $data['days'] .' days');
            $start_date = $currentDateTime->format('Y-m-d H:i:s');
            $end_date = $banDateTime->format('Y-m-d H:i:s');
    
            UserBan::create([
                'user_id' => $user->id,
                'username' => $user->name,
                'cause' => $data['cause'],
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            Db::commit();

            return [
                'status' => 0,
                'msg' => "User ". $user->name ." banned for ". $data['days'] ." day(s)!",
            ];
        } catch (\Exception $e) {
            Db::rollBack();
            return [
                'status' => 2,
                'msg' => 'Operation error!'
            ];
        }
    }

    // Разблокировка пользователя
    public function userUnban(User $user) {
        try {
            $userBanInfo = UserBan::where('user_id', '=', $user->id)->get();

            // Записываем данные про блокировку
            $currentBan = $this->checkBan($userBanInfo);

            if ($currentBan) {
                $start_date = \DateTime::createFromFormat('Y-m-d H:i:s', $currentBan['start_date']);

                UserBan::where('start_date', '=', $currentBan['start_date'])
                    ->update(['end_date' => $start_date]);

                return [
                    'status' => 0,
                    'msg' => "User ". $user->name ." unbanned!",
                ];
            }

            Db::commit();
        } catch (\Exception $e) {
            return [
                'status' => 2,
                'msg' => 'Operation error!'
            ];
        }
        
        return [
            'status' => 1,
            'msg' => "User ". $user->name ." is not banned!",
        ];
    }

    // Блокировка чата пользователя
    public function blockChat(User $user, $data) {
        if (auth()->user()->id === $user->id) {
            return [
                'status' => 1,
                'msg' => "You can't blocked chat yourself!",
            ];
        }
        if (empty($data['cause'])) $data['cause'] = 'block';

        try {
            DB::beginTransaction();

            $currentDateTime = new \DateTime();
            $userBlockInfo = BlockUserChat::where('user_id', '=', $user->id)->get();

            if ($this->checkBan($userBlockInfo)) {
                return [
                    'status' => 1,
                    'msg' => "User ". $user->name ." is already blocked chat!",
                ];
            }
    
            $blockDateTime = new \DateTime();
            $blockDateTime->modify('+'. $data['days'] .' days');
            $start_date = $currentDateTime->format('Y-m-d H:i:s');
            $end_date = $blockDateTime->format('Y-m-d H:i:s');
    
            BlockUserChat::create([
                'user_id' => $user->id,
                'username' => $user->name,
                'cause' => $data['cause'],
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            Db::commit();

            return [
                'status' => 0,
                'msg' => "User ". $user->name ." blocked chat for ". $data['days'] ." day(s)!",
            ];
        } catch (\Exception $e) {
            Db::rollBack();
            return [
                'status' => 2,
                'msg' => 'Operation error!'
            ];
        }
    }

    // Разблокировка чата с игроками
    public function unblockChat(User $user) {
        try {
            $userBlockInfo = BlockUserChat::where('user_id', '=', $user->id)->get();

            $currentBlock = $this->checkBan($userBlockInfo);
            
            if ($currentBlock) {
                $start_date = \DateTime::createFromFormat('Y-m-d H:i:s', $currentBlock['start_date']);

                BlockUserChat::where('start_date', '=', $currentBlock['start_date'])
                    ->update(['end_date' => $start_date]);

                return [
                    'status' => 0,
                    'msg' => "User ". $user->name ." unblocked chat!",
                ];
            }

            Db::commit();

            return [
                'status' => 1,
                'msg' => "User ". $user->name ." is not blocked chat!",
            ];
        } catch (\Exception $e) {
            return [
                'status' => 2,
                'msg' => 'Operation error!'
            ];
        }
    }
}