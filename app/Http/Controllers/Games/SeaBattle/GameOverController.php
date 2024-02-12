<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Games\SeaBattle\BaseController;
use App\Http\Requests\Games\SeaBattle\GameOverRequest;
use Illuminate\Http\Request;

class GameOverController extends BaseController
{
    public function __invoke(GameOverRequest $request) {
        $request->input('check');

        return $this->service->GameOver();
    }
}
