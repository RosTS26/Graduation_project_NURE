<?php

namespace App\Http\Controllers\Games\SeaBattle;

use App\Services\Games\SeaBattle\Service;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }
}