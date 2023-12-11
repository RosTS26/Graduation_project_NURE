<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snake;
use App\Models\Tetris;
use App\Models\Roulette;

class RatingController extends Controller
{
    public function __invoke() {
        // Сортировка по столбцу top_score в порядке убывания
        $topSnakeScore = Snake::orderBy('top_score', 'desc')->get();
        $topTetrisScore = Tetris::orderBy('top_score', 'desc')->get();
        $topRouletteDeposit = Roulette::orderBy('deposit', 'desc')->get();

        // Объявляем массивы с данными топ пользователей
        $topSnake = $topTetris = $topRoulette = array();

        // Записываем данные 10-ти игроков
        for ($i = 0; $i < count($topSnakeScore) && $i < 10; $i++) {
            $itemSnake = [
                'name' => $topSnakeScore[$i]->user->name,
                'top_score' => $topSnakeScore[$i]->top_score,
                'num_of_games' => $topSnakeScore[$i]->num_of_games,
            ];
            $itemTetris = [
                'name' => $topTetrisScore[$i]->user->name,
                'top_score' => $topTetrisScore[$i]->top_score,
                'num_of_games' => $topTetrisScore[$i]->num_of_games,
            ];
            $itemRoulette = [
                'name' => $topRouletteDeposit[$i]->user->name,
                'deposit' => $topRouletteDeposit[$i]->deposit,
                'num_of_games' => $topRouletteDeposit[$i]->num_of_games,
            ];

            array_push($topSnake, $itemSnake);
            array_push($topTetris, $itemTetris);
            array_push($topRoulette, $itemRoulette);
        }

        return view('rating', compact('topSnake', 'topTetris', 'topRoulette'));
    }
}
