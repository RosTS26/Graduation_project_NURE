<?php

namespace App\Http\Controllers\Games\Roulette;

use App\Http\Controllers\Games\BaseController;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $token = auth()->user()->password;
        return view('game.roulette', compact('token'));
    }
}
