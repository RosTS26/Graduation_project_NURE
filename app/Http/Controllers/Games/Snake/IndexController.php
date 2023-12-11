<?php

namespace App\Http\Controllers\Games\Snake;

use App\Http\Controllers\Games\BaseController;
use App\Http\Requests\Games\Snake\GameOverRequest;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $token = auth()->user()->password;
        return view('game.snake', compact('token'));
    }
}
