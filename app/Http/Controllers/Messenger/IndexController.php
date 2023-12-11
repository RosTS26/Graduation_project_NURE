<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Messenger\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Messenger\UserIdRequest;

class IndexController extends BaseController
{
    public function __invoke(UserIdRequest $request) {
        // dd($this->service->sendMsg(2, '12345qewrt'));
        $user_id = $request->input('id');
        $friends = $this->service->getFriends();
        $blockChat = $this->service->checkBlockChat();
        
        return view('messenger.messenger', compact('user_id', 'friends', 'blockChat'));
    }
}
