<?php

namespace App\Http\Controllers\Profile;

use App\Services\Profile\Service;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}