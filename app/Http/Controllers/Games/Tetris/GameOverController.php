<?php

namespace App\Http\Controllers\Games\Tetris;

use App\Http\Controllers\Games\BaseController;
use App\Http\Requests\Games\Tetris\GameOverRequest;
use Illuminate\Http\Request;

class GameOverController extends BaseController
{
    public function __invoke(GameOverRequest $request)
    {
        $data = $request->validated();
        $res = $this->service->tetrisGameOver($data);
        
        if (!empty($res)) return $res;
    }
}
