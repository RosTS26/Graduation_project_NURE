<?php

namespace App\Http\Controllers\Games\Roulette;

use App\Http\Controllers\Games\BaseController;
use App\Http\Requests\Games\Roulette\ScrollRequest;
use Illuminate\Http\Request;

class GameOverController extends BaseController
{
    public function __invoke(ScrollRequest $request)
    {
        $data = $request->validated();
        $res = $this->service->rouletteGameOver($data);
        return $res;
    }
}
