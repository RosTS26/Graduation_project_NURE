<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
Use App\Models\User;
use App\Http\Requests\Admin\BanRequest;

class BanController extends BaseController
{
    public function __invoke(User $user, BanRequest $request) {
        try {
            $data = $request->validated();
            return $this->service->userBan($user, $data);
        }
        catch (\Exception $e) {
            return [
                'status' => 2,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
