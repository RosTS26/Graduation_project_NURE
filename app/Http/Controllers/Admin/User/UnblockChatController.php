<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\User;

class UnblockChatController extends BaseController
{
    public function __invoke(User $user) {
        return $this->service->unblockChat($user);
    }
}
