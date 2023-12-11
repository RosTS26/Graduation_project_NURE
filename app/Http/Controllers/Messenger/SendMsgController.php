<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Messenger\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Messenger\SendMsgRequest;

class SendMsgController extends BaseController
{
    public function __invoke(SendMsgRequest $request) {
        $userChatId = $request->input('userChatId');
        $message = $request->input('message');
        return $this->service->sendMsg($userChatId, $message);
    }
}
