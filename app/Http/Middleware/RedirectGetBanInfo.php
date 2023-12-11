<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserBan;

// Middleware на проверку разрешение перехода по ссылке ban-info
class RedirectGetBanInfo
{
    public function handle(Request $request, Closure $next)
    {
        $currentDateTime = new \DateTime();
        $userBanInfo = UserBan::where('user_id', '=', auth()->user()->id)->get();

        if (count($userBanInfo) > 0) {
            foreach ($userBanInfo as $row) {
                $end_date = \DateTime::createFromFormat('Y-m-d H:i:s', $row['end_date']);

                // Переходим на страницу информации о блокировки пользователя
                if ($currentDateTime < $end_date) {
                    $request->merge(['banInfo' => $row]); // Записываем инфу про бан
                    return $next($request);    
                }
            }
        }

        // Если блокировок нету, перенаправляем пользователя на домашнюю страницу
        return redirect()->route('home');
    }
}
