<?php

namespace App\Http\Controllers\Games\Tetris;

use App\Http\Controllers\Games\BaseController;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $token = auth()->user()->password;
        return view('game.tetris', compact('token'));
    }
}
