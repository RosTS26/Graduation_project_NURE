<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snake;
use App\Models\Tetris;
use App\Models\SeaBattle;

class RatingController extends Controller
{
    public function __invoke() {
        // Сортировка по столбцу top_score в порядке убывания
        $snakeDB = Snake::orderBy('top_score', 'desc')->get();
        $tetrisDB = Tetris::orderBy('top_score', 'desc')->get();
        $seaBattleDB = SeaBattle::orderBy('score', 'desc')->get();

        // Объявляем массивы с данными топ пользователей
        $topSnake = $topTetris = $topSeaBattle = array();

        // Записываем данные 10-ти игроков
        for ($i = 0; $i < count($snakeDB) && $i < 10; $i++) {
            $itemSnake = [
                'name' => $snakeDB[$i]->user->name,
                'top_score' => $snakeDB[$i]->top_score,
                'num_of_games' => $snakeDB[$i]->num_of_games,
            ];
            $itemTetris = [
                'name' => $tetrisDB[$i]->user->name,
                'top_score' => $tetrisDB[$i]->top_score,
                'num_of_games' => $tetrisDB[$i]->num_of_games,
            ];
            $itemSeaBattle = [
                'name' => $seaBattleDB[$i]->user->name,
                'score' => $seaBattleDB[$i]->score,
                'num_of_wins' => $seaBattleDB[$i]->num_of_wins,
            ];

            array_push($topSnake, $itemSnake);
            array_push($topTetris, $itemTetris);
            array_push($topSeaBattle, $itemSeaBattle);
        }

        return view('rating', compact('topSnake', 'topTetris', 'topSeaBattle'));
    }
}
