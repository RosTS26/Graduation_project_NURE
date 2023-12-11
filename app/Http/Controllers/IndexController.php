<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Snake;

// Контроллер стартовой страницы
class IndexController extends Controller
{
    public function index() {
        $user = User::find(1);
        dump($user->Snake);
        dump($user->Tetris);
        dump($user->Roulette);
    }
}