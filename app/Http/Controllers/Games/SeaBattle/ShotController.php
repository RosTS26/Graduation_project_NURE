<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Http\Controllers\Games\SeaBattle\BaseController;
use App\Http\Requests\Games\SeaBattle\ShotRequest;
use Illuminate\Http\Request;

class ShotController extends BaseController
{
    public function __invoke(ShotRequest $request) {
        $posx = $request->input('posx');
        $posy = $request->input('posy');

        return $this->service->shotCheck($posx, $posy);
    }
}
