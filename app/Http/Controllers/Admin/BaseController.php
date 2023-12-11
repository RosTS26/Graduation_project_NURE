<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\Service;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}