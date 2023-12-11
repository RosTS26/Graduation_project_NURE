<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Friends\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Friends\FindRequest;

class FindController extends BaseController
{
    public function __invoke(FindRequest $request) {
        $username = $request->input('username');
        return $this->service->findFriends($username);
    }
}
