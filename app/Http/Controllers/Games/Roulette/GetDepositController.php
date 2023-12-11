<?php

namespace App\Http\Controllers\Games\Roulette;

use App\Http\Controllers\Games\BaseController;
use App\Http\Requests\Games\Roulette\GetDepositRequest;
use Illuminate\Http\Request;

class GetDepositController extends BaseController
{
    public function __invoke(GetDepositRequest $request)
    {
        $data = $request->validated();
        $res = $this->service->rouletteGetDeposit($data);
        return $res;
    }
}
