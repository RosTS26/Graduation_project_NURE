<?php

namespace App\Http\Controllers\Admin\Chat;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\AdminChat;

class ChatsController extends BaseController
{
    public function __invoke() {
        $processedData = $this->service->getUsersChat();
        return view('admin.chats', compact('processedData'));
    }
}
