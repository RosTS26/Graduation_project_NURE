<?php

namespace App\Services\Profile;

use App\Models\User;
use App\Models\Snake;
use App\Models\Tetris;
use App\Models\Roulette;
use Illuminate\Support\Facades\DB; // Подключение транзакций
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Service
{   
    // Обновление данных профиля
    public function update($data) {
        try {
            Db::beginTransaction();

            // Фильтруем данные, которые не пришли для обновления (null)
            $data = array_filter($data, function($value) {
                return $value !== null;
            });

            // Проверка, хочет ли пользователь сменить аватарку
            if (array_key_exists('avatar', $data)) {
                // Сохраняем новую аватарку на сервере
                $newAvatar = 'avatar_'. auth()->user()->id . '.' . $data['avatar']->extension();
                $data['avatar']->move(public_path('images/avatars'), $newAvatar);
                $data['avatar'] = '/images/avatars/' . $newAvatar;
            }

            // Обновление данных
            User::find(auth()->user()->id)->update($data);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
        }
    }

    // Обновление пароля
    public function passwordUpdate($data) {
        try {
            Db::beginTransaction();

            $newPassword['password'] = Hash::make($data['password']);
            User::find(Auth()->user()->id)->update($newPassword);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return false;
            //return $e->getMessage();
        }

        return true;
    }
    
    // Удаление аккаунта
    public function deleteAccount($data) {
        try {
            Db::beginTransaction();

            $user = User::find(Auth()->user()->id);
            
            // Удаляем все данные связанные с юзером
            $user->snake->delete();
            $user->tetris->delete();
            $user->roulette->delete();
            $user->delete();
            
            Auth::logout(); // Выход пользователя

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
        }
    }
}