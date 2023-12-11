<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\BaseController;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke()
    {
        $profile = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ];

        return view('profile/profileEdit', compact('profile'));
    }
}
