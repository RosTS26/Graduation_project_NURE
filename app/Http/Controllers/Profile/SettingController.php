<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\BaseController;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    public function __invoke()
    {
        return view('profile/profileSettings');
    }
}
