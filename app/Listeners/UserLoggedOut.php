<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;

// Обработчик выхода из аккаунта/окончание сессии
class UserLoggedOut
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(Logout $event)
    {
        $user = $event->user; // Получите текущего пользователя
        $user->update(['online' => 0]); // Установите статус "оффлайн" для пользователя
    }
}
