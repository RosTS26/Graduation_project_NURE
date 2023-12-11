<?php

namespace App\Http\Controllers\Games;

use App\Services\Games\Service; // Подключаем класс Service для реализации сервисов
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $service;

    // Инициализация свойства $service для связи контроллеров с объектом класса Service
    public function __construct(Service $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }
}