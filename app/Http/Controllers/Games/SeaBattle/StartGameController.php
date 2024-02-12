<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Games\SeaBattle\BaseController;
use App\Http\Requests\Games\SeaBattle\StartGameRequest;
use Illuminate\Http\Request;

class StartGameController extends BaseController
{
    public function __invoke(StartGameRequest $request) {
        $user1_id = $request->input('user1_id');
        $user2_id = $request->input('user2_id');

        return $this->service->startGame($user1_id, $user2_id);
    }
}
