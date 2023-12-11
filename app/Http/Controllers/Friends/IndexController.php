<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Friends\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Friend;

class IndexController extends BaseController
{
    public function __invoke() {
        $friendsData = $this->service->option('friends');
        $numIncomApp = $this->service->getNumIncomApp();

        return view('friends.friends', compact('friendsData', 'numIncomApp'));
    }
}
