<?php

namespace App\Services\Games;

use App\Models\User;
use App\Models\Snake;
use App\Models\Tetris;
use App\Models\Roulette;
use Illuminate\Support\Facades\DB; // Подключение транзакций

// Реализация работы с БД через класс Service
// Контроллеры наследуются от базового контроллера, который имеет свйоство service
// Свойство $service инициализируется в базовом контроллере в конструкторе
class Service
{   
    // Snake
    public function snakeGameOver($data) {
        $newRecord = false; // Новый рекорд

        try {
            DB::beginTransaction();

            $snakeDB = auth()->user()->snake;

            if ($data['token'] === auth()->user()->password) {
                // Прибавляем +1 к отыгранным играм
                $numOfGames = $snakeDB->num_of_games;
                $numOfGames++;

                // Записываем новый рекорд
                $topScore = $data['score'] > $snakeDB->top_score ? $data['score'] : $snakeDB->top_score;
                // Фиксируем новый рекорд
                $newRecord = $topScore > $snakeDB->top_score ? true : false;

                // Обновляем таблицу Змейки
                $snakeDB->update([
                    'top_score' => $topScore,
                    'num_of_games' => $numOfGames,
                ]);

            } else {
                return 'DATABASE ERROR!';
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
        }

        if ($newRecord) return 'New record!';
    }

    // Tetris
    public function tetrisGameOver($data) {
        $newRecord = false; // Новый рекорд

        try {
            DB::beginTransaction();

            $tetrisDB = auth()->user()->tetris;

            if ($data['token'] === auth()->user()->password) {

                $numOfGames = $tetrisDB->num_of_games;
                $numOfGames++;

                // Записываем новый рекорд
                $topScore = $data['score'] > $tetrisDB->top_score ? $data['score'] : $tetrisDB->top_score;
                // Фиксируем новый рекорд
                $newRecord = $topScore > $tetrisDB->top_score ? true : false;

                $tetrisDB->update([
                    'top_score' => $topScore,
                    'num_of_games' => $numOfGames,
                ]);

            } else {
                return 'DATABASE ERROR!';
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
        }

        if ($newRecord) return 'New record!';
    }

    // Roulette
    public function rouletteGetDeposit($data) {
        try {
            DB::beginTransaction();

            $user = User::find(auth()->user()->id);

            if ($data['token'] === $user->password) {
                $deposit = $user->roulette['deposit'];
            } else {
                return 'error';
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
        }

        return $deposit;
    }

    public function rouletteGameOver($data) {
        try {
            DB::beginTransaction();

            $user = User::find(auth()->user()->id);

            if ($data['token'] === $user->password) {

                $numOfGames = $user->roulette['num_of_games'];
                $numOfGames++;

                // Обновляем депозит игрока
                $deposit = $user->roulette['deposit'] + $data['score'];

                $user->roulette->update([
                    'deposit' => $deposit,
                    'num_of_games' => $numOfGames,
                ]);

            } else {
                return 'error';
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $e->getMessage();
        }

        return $deposit;
    }
}