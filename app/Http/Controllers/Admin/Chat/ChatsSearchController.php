<?php

namespace App\Http\Controllers\Admin\Chat;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ChatSearchRequest;
use App\Models\AdminChat;

class ChatsSearchController extends BaseController
{
    public function __invoke(ChatSearchRequest $request) {
        try {
            $search = $request->input('search');
            $users = $this->service->searchChat($search);

            return json_encode($users);

        } catch (\Exception $e) {
            // Возможно, также следует вернуть клиенту сообщение об ошибке.
            return response()->json($e->getMessage());
        }
    }
}
