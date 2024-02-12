<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Games\SeaBattle\ConnectRequest;
use Illuminate\Http\Request;
use App\Events\SeaBattle\GameSearchSBEvent;

class ConnectController extends Controller
{
    public function __invoke(ConnectRequest $request) {
        $enemy = $request->input('data');
        try {
            // host отправляет запрос на подключение guest
            broadcast(new GameSearchSBEvent(auth()->user(), $enemy['id']))->toOthers(); 
        } catch (\Exception $e) {
            return $e;
        }
        return 1;
    }
}
