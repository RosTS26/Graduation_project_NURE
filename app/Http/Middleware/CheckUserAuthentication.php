<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Проверка доступа зареганого юзера к сервису
class CheckUserAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) return $next($request);
        return redirect()->route('login');
    }
}
