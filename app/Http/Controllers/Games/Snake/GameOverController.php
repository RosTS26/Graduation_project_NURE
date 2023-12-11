<?php

namespace App\Http\Controllers\Games\Snake;

use App\Http\Controllers\Games\BaseController;
use App\Http\Requests\Games\Snake\GameOverRequest;
use Illuminate\Http\Request;

class GameOverController extends BaseController
{
    public function __invoke(GameOverRequest $request)
    {
        $data = $request->validated(); // Класс request (для оптимизации кода)
        $res = $this->service->snakeGameOver($data);
        
        if (!empty($res)) return $res;
    }
}
