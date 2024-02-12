<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\SeaBattle\SeaBattleEvent;

class ConfirmController extends Controller
{
    public function __invoke(Request $request) {
        $data = $request->input('data');
        $enemyID = $request->input('id');

        try {
            // Приватный канал для двоих игроков
            broadcast(new SeaBattleEvent(auth()->user(), (int)$enemyID, $data)); 
        } catch (\Exception $e) {
            return $e;
        }
        return 1;
    }
}
