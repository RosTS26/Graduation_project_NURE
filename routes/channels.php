<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Broadcasting\AdminChatChannel;
use App\Models\SbGameStatistics;

// Доступ пользователя к новой игре
function gameSBCheck() {
    // Если у пользователя есть незаконченная игра - запрет на поиск новой
    if (session()->has('gameID')) {
        $checkGame = SbGameStatistics::find(session('gameID'));
        return $checkGame ? $checkGame->winner : 1;
    }

    return 1;

    // $games = SbGameStatistics::where(function($query) use ($user) {
    //     $query->where('user1_id', $user->id)
    //     ->orWhere('user2_id', $user->id);
    // })->where('winner', null)->get();

    // return count($games) > 0 ? false : true;
}

// Роуты (Каналы) WebSocket
Broadcast::channel('AdminChat', AdminChatChannel::class);
Broadcast::channel('User-chat-{id}', function($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Friendly-chat-{id}', function($user, $id) {
    return (int) $user->id === (int) $id;
});

// Морской бой
Broadcast::channel('Game-search', function($user) {       
    if (auth()->check() && gameSBCheck()) {
        return ['id' => $user->id, 'name' => $user->name];
    } 
});

Broadcast::channel('Sea-battle-{user1_id}-{user2_id}', function($user, $user1_id, $user2_id) {
    if ((int) $user->id === (int) $user1_id || (int) $user->id === (int) $user2_id) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});

// Online/offline check
Broadcast::channel('Auth-check', function($user) {
    if (auth()->check()) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});