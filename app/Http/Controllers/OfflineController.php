<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OfflineController extends Controller
{
    public function __invoke() {
        // auth()->user()->update(['online' => 0]);
        // auth()->user()->update(['role' => 'test-offline']);
        $pusher = new Pusher('0a3e3f8a95c64ceda120', '9ba4dc47a28f5006ada8', '1670402', ['cluster' => 'eu']);
        
        $channelInfo = $pusher->get('/channels/AdminChat');

        return $channelInfo->user_count;
    }
}
