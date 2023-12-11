<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Messenger\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Messenger\UserChatIdRequest;

class DeleteChatController extends BaseController
{
    public function __invoke(UserChatIdRequest $request) {
        $userChatId = $request->input('userChatId');
        return $this->service->deleteChat($userChatId);
    }
}
