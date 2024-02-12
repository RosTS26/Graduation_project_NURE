<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Games\SeaBattle\BaseController;
use App\Http\Requests\Games\SeaBattle\ReadyGameRequest;
use Illuminate\Http\Request;

class ReadyGameController extends BaseController
{
    public function __invoke(ReadyGameRequest $request) {
        $ships = $request->input('ships');
        $field = $request->input('field');

        return $this->service->readyGame($ships, $field);
    }
}
