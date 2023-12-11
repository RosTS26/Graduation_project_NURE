<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Friends\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Friends\UserIdRequest;

class AddFriendController extends BaseController
{
    public function __invoke(UserIdRequest $request) {
        $user_id = $request->input('user_id');
        return $this->service->addFriend($user_id);
    }
}
