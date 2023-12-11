<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminChat;

class IndexController extends BaseController
{
    public function __invoke()
    {
        // Информация о профиле
        $profile = [
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'avatar' => auth()->user()->avatar,
            'create' => auth()->user()->created_at->format('d F Y'),
        ];

        // Информация о играх
        $info = User::find(auth()->user()->id);
        $gamesInfo = [
            'snake' => $info->snake,
            'tetris' => $info->tetris,
            'roulette' => $info->roulette];
        
        // Кол-во новых сообщений от админ-чата
        $adminChat = AdminChat::where('user_id', '=', auth()->user()->id)->first();

        $numNewMsgs = 0;
        if (!empty($adminChat)) {
            foreach (json_decode($adminChat->new_msgs) as $item) {
                if ($item->id !== auth()->user()->id) $numNewMsgs++;
            }
        }
        
        return view('profile.profile', compact('profile', 'gamesInfo', 'numNewMsgs'));
    }
}
