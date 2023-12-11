<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Profile\AdminChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    //Route::post('login', [AuthController::class, 'login']);
    //Route::post('logout', [AuthController::class, 'logout']);
    //Route::post('refresh', [AuthController::class, 'refresh']);
    //Route::post('me', [AuthController::class, 'me']);

});

Route::group(['middleware' => 'jwt.auth',
    'namespace' => 'App\Http\Controllers'], function() {

    // Группировка роутов профиля
    Route::group(['namespace' => 'Profile',
        'prefix' => 'profile'], function() {

        Route::patch('/settings/password-update', 'PasswordUpdateController')->name('profile.passwordUpdate');

        // Чат с администратором (WebSocket)
        Route::post('/load-chat', [AdminChatController::class, 'loadChat'])->name('profile.loadChat');
        Route::post('/load-newMsgs', [AdminChatController::class, 'loadNewMsgs'])->name('profile.loadNewMsgs');
        Route::post('/sendMsg', [AdminChatController::class, 'sendMsg'])->name('profile.sendMsg');
        Route::post('/updateChat', [AdminChatController::class, 'updateChat'])->name('profile.updateChat');
    });

    // Группировка игр
    Route::group(['namespace' => 'Games',
        'prefix' => 'game'], function() {
        // Группировка роутов змейки
        Route::group(['namespace' => 'Snake',
            'prefix' => 'snake'], function() {
            Route::post('/game-over', 'GameOverController')->name('game.snake.gameover');
        });

        // Группировка роутов тетриса
        Route::group(['namespace' => 'Tetris',
            'prefix' => 'tetris'], function() {
            Route::post('/game-over', 'GameOverController')->name('game.tetris.gameover');
        });
        
        // Группировка роутов рулетки
        Route::group(['namespace' => 'Roulette',
            'prefix' => 'roulette'], function() {
            Route::post('/game-over', 'GameOverController')->name('game.roulette.gameover');
            Route::post('/get-deposit', 'GetDepositController')->name('game.roulette.getdeposit');
        });
    });
});