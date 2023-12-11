<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Games\Snake\GameOverController;
use App\Http\Controllers\Profile\AdminChatController;
use App\Http\Controllers\Admin\Chat\UsersChatController;

//test 
//Route::post('/test', [AdminChatController::class, 'loadChat'])->name('test');

Auth::routes(); // Роуты регистрации
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/logout')->middleware('redirectGetRequests');
Route::get('/ban-info', 'App\Http\Controllers\BanInfoController')->name('ban');

// Группировка роутов для зарегестрированных и незабаненых пользователей
Route::group(['middleware' => ['auth.check', 'ban.check'],
    'namespace' => 'App\Http\Controllers'], function() {
    // Route::get('/index', [IndexController::class, 'index'])->name('index');
    // Route::get('/sse', 'SSEController@stream');
    // Route::get('/offline', 'OfflineController');
    
    // Роут таблицы рейтингов
    Route::get('/rating', 'RatingController')->name('rating.index');

    // Группировка роутов профиля
    Route::group(['namespace' => 'Profile',
        'prefix' => 'profile'], function() {
        Route::get('/', 'IndexController')->name('profile.index');
        Route::get('/edit', 'EditController')->name('profile.edit');
        Route::patch('/edit', 'UpdateController')->name('profile.update');

        Route::get('/settings', 'SettingController')->name('profile.settings');
        Route::delete('/settings', 'DeleteController')->name('profile.delete');
        Route::patch('/settings/password-update', 'PasswordUpdateController')->name('profile.passwordUpdate');
        Route::get('/settings/password-update', 'PasswordUpdateController')->middleware('redirectGetRequests');

        // Чат с администратором (WebSocket)
        Route::post('/load-chat', [AdminChatController::class, 'loadChat'])->name('profile.loadChat');
        Route::post('/sendMsg', [AdminChatController::class, 'sendMsg'])->name('profile.sendMsg');
        Route::post('/updateChat', [AdminChatController::class, 'updateChat'])->name('profile.updateChat');

        Route::get('/load-chat')->middleware('redirectGetRequests');
        Route::get('/sendMsg')->middleware('redirectGetRequests');
        Route::get('/updateChat')->middleware('redirectGetRequests');
    });

    // Роуты мессенджера
    Route::group(['namespace' => 'Messenger',
        'prefix' => 'messenger'], function() {
        Route::get('/', 'IndexController')->name('messenger.index');
        Route::post('/load-chat', 'LoadChatController');
        Route::post('/sendMsg', 'SendMsgController');
        Route::post('/update-chat', 'UpdateChatController');
        Route::post('/delete-chat', 'DeleteChatController');

        Route::get('/load-chat')->middleware('redirectGetRequests');
        Route::get('/sendMsg-chat')->middleware('redirectGetRequests');
        Route::get('/update-chat')->middleware('redirectGetRequests');
        Route::get('/delete-chat')->middleware('redirectGetRequests');
    });

    // Роуты друзей
    Route::group(['namespace' => 'Friends',
        'prefix' => 'friends'], function() {
        Route::get('/', 'IndexController')->name('friends.index');
        Route::get('/{user}', 'ShowController')->name('friends.show');
        Route::post('/', 'OptionController');
        Route::post('/find', 'FindController');
        Route::post('/add-friend', 'AddFriendController');
        Route::post('/delete-friend', 'DeleteFriendController');
        Route::post('/cancel-app', 'CancelAppController');
        Route::post('/reject-app', 'RejectAppController');

        Route::get('/find')->middleware('redirectGetRequests');
        Route::get('/add-friend')->middleware('redirectGetRequests');
        Route::get('/delete-friend')->middleware('redirectGetRequests');
        Route::get('/cancel-app')->middleware('redirectGetRequests');
        Route::get('/reject-app')->middleware('redirectGetRequests');
    });

    // Роуты админ-панели
    Route::group(['namespace' => 'Admin',
        'prefix' => 'admin',
        'middleware' => 'admin.check'], function() {

        // Обработка роутов юзеров
        Route::group(['namespace' => 'User',
            'prefix' => 'users'], function() {
            Route::get('/', 'IndexController')->name('admin.user.index');
            Route::get('/{user}', 'ShowController')->name('admin.user.show');
            Route::post('/{user}/ban', 'BanController');
            Route::post('/{user}/unban', 'UnbanController');
            Route::post('/{user}/block-chat', 'BlockChatController');
            Route::post('/{user}/unblock-chat', 'UnblockChatController');
            Route::post('/users-search', 'UsersSearchController');

            Route::get('/users-search')->middleware('redirectGetRequests');
            Route::get('/{user}/ban')->middleware('redirectGetRequests');
            Route::get('/{user}/unban')->middleware('redirectGetRequests');
            Route::get('/{user}/block-chat')->middleware('redirectGetRequests');
            Route::get('/{user}/unblock-chat')->middleware('redirectGetRequests');
            
        });
        
        // Чат с игроками 
        Route::group(['namespace' => 'Chat',
            'prefix' => 'chats'], function() {
            Route::get('/', 'ChatsController')->name('admin.chats');
            Route::post('/chats-search', 'ChatsSearchController');
            Route::get('/chats-search')->middleware('redirectGetRequests');
            Route::post('/load-chat', [UsersChatController::class, 'loadChat']);
            Route::post('/sendMsg', [UsersChatController::class, 'sendMsg']);
            Route::post('/updateChat', [UsersChatController::class, 'updateChat']);

            Route::get('/load-chat')->middleware('redirectGetRequests');
            Route::get('/sendMsg')->middleware('redirectGetRequests');
            Route::get('/updateChat')->middleware('redirectGetRequests');
        });
    });

    // Группировка игр
    Route::group(['namespace' => 'Games',
        'prefix' => 'game'], function() {
        // Группировка роутов змейки
        Route::group(['namespace' => 'Snake',
            'prefix' => 'snake'], function() {
            Route::get('/', 'IndexController')->name('game.snake.index');
            Route::post('/game-over', 'GameOverController')->name('game.snake.gameover');
            Route::get('/game-over', 'GameOverController')->middleware('redirectGetRequests');
        });

        // Группировка роутов тетриса
        Route::group(['namespace' => 'Tetris',
            'prefix' => 'tetris'], function() {
            Route::get('/', 'IndexController')->name('game.tetris.index');
            Route::post('/game-over', 'GameOverController')->name('game.tetris.gameover');
            Route::get('/game-over', 'GameOverController')->middleware('redirectGetRequests');
        });
        
        // Группировка роутов рулетки
        Route::group(['namespace' => 'Roulette',
            'prefix' => 'roulette'], function() {
            Route::get('/', 'IndexController')->name('game.roulette.index');
            Route::post('/game-over', 'GameOverController')->name('game.roulette.gameover');
            Route::post('/get-deposit', 'GetDepositController')->name('game.roulette.getdeposit');
            Route::get('/game-over', 'GameOverController')->middleware('redirectGetRequests');
            Route::get('/get-deposit', 'GetDepositController')->middleware('redirectGetRequests');
        });
    });
});