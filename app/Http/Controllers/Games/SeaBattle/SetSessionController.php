<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Games\SeaBattle\BaseController;
use App\Http\Requests\Games\SeaBattle\SetSessionRequest;
use Illuminate\Http\Request;

class SetSessionController extends BaseController
{
    public function __invoke(SetSessionRequest $request) {
        $gameID = $request->input('gameID');
        return $this->service->setSessionData($gameID);
    }
}
