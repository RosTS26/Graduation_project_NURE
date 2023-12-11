<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminChatChannel
{
    public function __construct()
    {
        //
    }

    // Проверка, каким пользователям можно подключаться к каналу (авторизованным)
    public function join(User $user)
    {
        return auth()->check();
    }
}
