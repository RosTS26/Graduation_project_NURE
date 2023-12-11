<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
Use App\Models\User;

class UnbanController extends BaseController
{
    public function __invoke(User $user) {
        return $this->service->userUnban($user);
    }
}
