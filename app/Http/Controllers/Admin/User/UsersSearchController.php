<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserSearchRequest;

// Контроллер поиска пользователей по их имени
class UsersSearchController extends BaseController
{
    public function __invoke(UserSearchRequest $request) {
        try {
            $search = $request->input('search');
            $users = $this->service->searchUsers($search);

            return json_encode($users);

        } catch (\Exception $e) {
            // Возможно, также следует вернуть клиенту сообщение об ошибке.
            return response()->json($e->getMessage());
        }
    }
}
