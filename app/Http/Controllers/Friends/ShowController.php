<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Friend;

class ShowController extends Controller
{
    public function __invoke(User $user) {
        // Если id совпадает с зарегестрированным аккаунтом, переходим в профиль
        if ($user->id === auth()->user()->id) return redirect()->route('profile.index');

        // Определяем статус игрока относительно зарегестрированного пользователя
        $status = 0; // Статус пользователя

        // Получаем инфомацию о списках в БД друзьях
        $friendDB = auth()->user()->friend;
        $decodedFriends = json_decode($friendDB->friends, true);
        $decodedSent = json_decode($friendDB->sent_app, true);
        $decodedIncom = json_decode($friendDB->incoming_app, true);

        // Перезаписываем id, делая массив и чисел, а не из объектов (id => num)
        $friendDB = [
            'friends' => [],
            'sent_app' => [],
            'incoming_app' => [],
        ];

        if ($decodedFriends) $friendDB['friends'] = array_column($decodedFriends, 'id');
        if ($decodedSent) $friendDB['sent_app'] = array_column($decodedSent, 'id');
        if ($decodedIncom) $friendDB['incoming_app'] = array_column($decodedIncom, 'id');

        // Проверяем, в какой колонке находится текущий пользователь
        if (in_array($user->id, $friendDB['friends'])) $status = 1; // Статус друг
        else if (in_array($user->id, $friendDB['sent_app'])) $status = 2; // Статус заявка отправлена
        else if (in_array($user->id, $friendDB['incoming_app'])) $status = 3; // Заявка получена
        else $status = 0; // Статус по умолчанию

        return view('friends.friendsShow', compact('user', 'status'));
    }
}
