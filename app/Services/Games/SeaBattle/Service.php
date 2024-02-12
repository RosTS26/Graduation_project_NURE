<?php

namespace App\Services\Games\SeaBattle;

use App\Models\User;
use App\Models\SbGameStatistics;
use App\Models\SeaBattle;
use Illuminate\Support\Facades\DB; // Подключение транзакций
use Illuminate\Support\Facades\Auth;
use App\Events\SeaBattle\SetSessionEvent;
use App\Events\SeaBattle\EnemyReadyEvent;
use App\Events\SeaBattle\EnemyLostEvent;
use App\Events\SeaBattle\EnemyWinEvent;
use App\Events\SeaBattle\ShotEvent;

class Service
{   
    private $gameDB;
    private $gameID;
    private $myNumber;
    private $hisNumber;

    // Проверка и запись свойств в класс (конструктор*)
    private function setParams() {
        if (session()->has('gameID')) {
            $this->gameID = session('gameID');
            $this->gameDB = SbGameStatistics::find($this->gameID);

            if ($this->gameDB) {
                $this->myNumber = $this->gameDB->user1_id == auth()->user()->id ? 1 : 2;
                $this->hisNumber = $this->myNumber == 1 ? 2 : 1;

                return 1;
            }
        }

        return 0;
    }

    // Проверка на уникальность выстрела
    private function checkAction($x, $y) {
        if (session()->has('action')) {
            $arrAction = session('action');
        
            if (!$arrAction[$y][$x]) {
                $arrAction[$y][$x] = 1;
                session()->put('action', $arrAction); // Записываем выстрел в сессию
                return 1;
            }
        }
        return 0;
    }

    // Проверка кораблей 
    private function shipsInspection($ships, $field) {
        if (count($ships) != 10) return 0; // (10)

        $numOfShips = [4, 3, 2, 1];
        $shipsNumbers = array_fill(0, 10, 1);

        // Проверяем каждый корабль
        foreach ($ships as $ship) {
            $type = $ship['type'];
            $numberShip = $ship['numberShip'];
            
            // Если тип корабля или его номер подделан 
            if (($type != (count($ship) - 2) / 2) ||
                !is_numeric($type) || !is_numeric($numberShip) ||
                $type < 1 || $type > 4 || 
                $numberShip < 1 || $numberShip > 10) return 0; // (1)
            
            // Корабль расположен по вертикале или горизонтали
            $align = ($ship['posx0'] == $ship['posx'.($type-1)]) ? 'y' : 'x';
            $numOfShips[$type - 1] --;
            $shipsNumbers[$numberShip - 1] --;

            if ($shipsNumbers[$numberShip - 1] < 0) return 0; // (2) Проверка на двойника

            $check = 0;
            // Сверяем координаты кораблей с полем
            for ($i = 0; $i < $type; $i++) {
                $posy = $ship['posy' . $i] - 1;
                $posx = $ship['posx' . $i] - 1;
                
                if (!isset($field[$posy][$posx])) return 0; // (3)
                if ($field[$posy][$posx] != $numberShip) return 0; // (4) error (не совпали координаты)
                if ($ship['pos'.$align.$i] != $ship['pos'.$align.'0'] + $i) return 0; // (5) Части корабля разорваны
                

                // Проверка соседних клеток
                for ($y = -1; $y < 2; $y++) {
                    for ($x = -1; $x < 2; $x++) {
                        // Пропускаем цикл, если есть совпадения
                        if ($posy + $y < 0 || $posx + $x < 0 ||
                            $posy + $y > 9 || $posx + $x > 9 ||
                            ($y == 0 && $x == 0)) continue;

                        $cell = $field[$posy + $y][$posx + $x];

                        // Если клетку занимает корабль иного номера - error
                        if ($cell != 0 && $cell != $numberShip) return 0; // (6)
                        // Если по диагонали расположен не 0 - error
                        else if($x != 0 && $y != 0 && $cell != 0) return 0; // (7)
                        // Подсчет частей корабля
                        else if ($cell == $numberShip) $check++;
                    }
                }
            }

            if ($type * 2 - 2 != $check) return 0; // (8)
        }

        // Проверяем на соответствие типов кораблей и их кол-ва
        foreach ($numOfShips as $zero) {
            if ($zero) return 0; // (9)
        }

        return 1;
    }

    // Рандомное размищение кораблей на поле
    private function getRandomShips() {
        $arrWidthCoords = []; // Координаты и статус всех ячеек
        $numOfShips = [4, 3, 2, 1];
	    $ships = [];
        $numberShip = 1;

        for ($y = 1; $y < 11; $y ++) {
            for ($x = 1; $x < 11; $x++) {
                $obj = [
                    'x' => $x,
                    'y' => $y,
                    'status' => 0,
                ];

                array_push($arrWidthCoords, $obj);
            }
        }

        // Проходимся по каждому типу кораблю
        for ($i = count($numOfShips); $i > 0; $i--) {
            $type = $i;
            $num = $numOfShips[$i - 1];

            // Корабль текущего типа
            for ($j = 0; $j < $num; $j++) {
                // Рандомное положение корабля
                $randomAlign = rand(0, 1) == 0 ? 'x' : 'y'; // Паралельно координате
                $xory = $randomAlign === 'x' ? 'y' : 'x';

                // x or y = MAX 10 - type + 1
                $freeCells = array_filter($arrWidthCoords, function($item) use ($xory, $type) {
                    return $item['status'] === 0 && $item[$xory] <= 10 - $type + 1;
                });
                
                $placesForShip = [];

                // Находим все доступные места для корабля на поле
                foreach ($freeCells as $item) {
                    $firstCoord = $item[$xory];
                    $alignCoord = $item[$randomAlign];
                    $place = [];
                    $check = 0;
                    
                    for ($cell = 0; $cell < $type; $cell++) {
                        // Ищем следующий элемент
                        $foundCell = null;
                        foreach ($arrWidthCoords as $obj) {
                            if ($obj[$xory] == $firstCoord + $cell && $obj[$randomAlign] == $alignCoord) {
                                $foundCell = $obj;
                                break;
                            }
                        }

                        array_push($place, $foundCell);

                        if ($obj['status'] == 0) $check++;
                        else break;
                    }

                    if ($check == $type) array_push($placesForShip, $place); 
                }

                // Выбираем рандомуню клетку из доступных мест
                $index = random_int(0, count($placesForShip) - 1);
                $randomPlace = $placesForShip[$index];

                $objShip = [
                    'type' => $type,
                    'numberShip' => $numberShip,
                ];
                // Записываем координаты корабля в общий массив
                for ($el = 0; $el < $type; $el++) {
                    $posx = $randomPlace[$el]['x'];
                    $posy = $randomPlace[$el]['y'];

                    $objShip['posx' . $el] = $posx;
                    $objShip['posy' . $el] = $posy;
                    
                    // Так же занимаем клетку в массиве свободных клеток
                    for ($y = -1; $y < 2; $y++) {
                        for ($x = -1; $x < 2; $x++) {
                            if ($posy + $y < 1 || $posx + $x < 1 ||
                                $posy + $y > 10 || $posx + $x > 10) continue;

                            foreach ($arrWidthCoords as $key => $obj) {
                                if ($obj['x'] == $posx + $x && $obj['y'] == $posy + $y) {
                                    $arrWidthCoords[$key]['status'] = 1;
                                    break;
                                }
                            }
                        }
                    }
                }

                array_push($ships, $objShip);
                $numberShip++;
            }
        }

        return $ships;
    }

    // Добавление всем кораблям доп.свойств
    private function addProp($ships) {
        foreach ($ships as $key => $ship) {
            $ships[$key]['destroyed'] = 0;
        }
        return $ships;
    }

    // Корабль уничтожен
    private function destroyShip($ship, $action) {
        for ($i = 0; $i < $ship['type']; $i++) {
            for ($y = -1; $y < 2; $y++) {
                for ($x = -1; $x < 2; $x++) {
                    if ($ship['posy'.$i] + $y < 1 || $ship['posx'.$i] + $x < 1 ||
                        $ship['posy'.$i] + $y > 10 || $ship['posx'.$i] + $x > 10) continue;

                    $action[$ship['posy'.$i] + $y - 1][$ship['posx'.$i] + $x - 1] = 1;
                }
            }
        }
        return $action; 
    }

    // Завершение игры
    private function finishGame($gameDB, $winner, $loser) {
        $winnerDB = SeaBattle::find($winner);
        $loserDB = SeaBattle::find($loser);

        if ($winnerDB && $loserDB) {
            DB::beginTransaction();
            // Записываем в БД победителя и "закрываем" ее
            $gameDB->update([
                'winner' => $winner,
                'players_move' => 0,
            ]);

            // Записываем данные в БД победителя
            $numWins = $winnerDB->num_of_wins + 1;
            $numGames = $winnerDB->num_of_games + 1;
            $score = $winnerDB->score + $gameDB->score;

            $winnerDB->update([
                'num_of_wins' => $numWins,
                'num_of_games' => $numGames,
                'score' => $score,
            ]);

            // Записываем данные в БД проигравшему
            $numGames = $loserDB->num_of_games + 1;
            $score = max($loserDB->score - $gameDB->score, 0);

            $loserDB->update([
                'num_of_games' => $numGames,
                'score' => $score,
            ]);
            Db::commit();

            return 1;

        } else return 0;
    }

    // ==================================================================================================================

    // Запись данных в сессию
    public function setSessionData($gameID) {
        $game = SbGameStatistics::find($gameID);

        $arrAction = array_fill(0, 10, array_fill(0, 10, 0));
        $attackedShips = array_fill(0, 10, 0);

        if ($game) {
            session([
                'gameID' => $gameID,
                'action' => $arrAction,
                'attackedShips' => $attackedShips,
                'numDestroyed' => 0,
            ]);

            return 'Game ID: '. $gameID;
        } else return 0;
    }

    // Подготовка к игре (Хост создает игру)
    public function startGame($user1_id, $user2_id) {
        $game = SbGameStatistics::create([
            'user1_id' => $user1_id,
            'user2_id' => $user2_id,
            'ships1' => json_encode([]),
            'ships2' => json_encode([]),
            'players_move' => $user1_id,
            'score' => 30
        ]);

        $this->setSessionData($game->id);

        // Оповещаем игрока про создание новой игры
        broadcast(new SetSessionEvent(auth()->user(), $user2_id, $game->id))->toOthers();

        return $user1_id . " --- ". $user2_id;
    }

    // Конец игры
    public function gameOver() {
        if ($this->setParams()) {
            $loser = auth()->user()->id;
            $winner = $this->gameDB['user'.$this->hisNumber.'_id'];

            // Проверка на победителя
            if (!$this->gameDB->winner) {
                if ($this->finishGame($this->gameDB, $winner, $loser)) {
                    broadcast(new EnemylostEvent(auth()->user(), $winner))->toOthers();
                    return 1;
                }
            }
        }

        return 0;
    }

    // Готовность к игре
    public function readyGame($ships, $field) {
        try {
            DB::beginTransaction(); // start DB transaction
        
            if ($this->setParams()) {

                // Заполено ли поле в БД
                $checkShips = json_decode($this->gameDB[('ships' . $this->myNumber)]);
                if (count($checkShips) > 0) throw new \Exception('Error: field is filled!', 500);

                $myShips = $ships;
                $shipsError = 0;
                $status = 1;
                
                // Проверка на правильное расположение кораблей
                if (!$this->shipsInspection($ships, $field)) {
                    $shipsError = 1;
                    $myShips = $this->getRandomShips();
                }
                
                // Заносим в БД свои корабли
                $finalShips = json_encode($this->addProp($myShips));
                $this->gameDB->update([
                    'ships'.$this->myNumber => $finalShips
                ]);
                Db::commit(); // end DB transaction
                
                // Проверяем, готов ли оппонент к игре
                $hisShips = json_decode($this->gameDB[('ships' . $this->hisNumber)]);

                if (count($hisShips) > 0) {
                    // Оповещаем второго игрока про готовность
                    $hisId = $this->gameDB['user'.$this->hisNumber.'_id'];
                    broadcast(new EnemyReadyEvent(auth()->user(), $hisId, $this->gameDB->players_move))->toOthers();
                    $status = 2;
                }
                
                $data = [
                    'status' => $status,
                    'ships' => $myShips,
                    'shipsError' => $shipsError,
                    'moveId' => $this->gameDB->players_move,
                ];
            
                return $data;
            } else throw new \Exception('Error: session data or DataBase not found!', 500);
        } catch (\Exception $e) {
            Db::rollBack();
            $data = [
                'status' => 0,
                'message' => $e->getMessage(),
            ];
            return $data;
        }
    }

    // Обработка выстрела
    public function shotCheck($posx, $posy) {
        $data = ['status' => 0];

        if ($this->setParams()) {
            // Проверка на возможность выстрелить
            if (!$this->gameDB->winner &&
            $this->gameDB->players_move == auth()->user()->id &&
            $this->checkAction($posx-1, $posy-1)) {

                $enemyId = $this->gameDB['user'.$this->hisNumber.'_id'];
                $data['moveId'] = $enemyId;
                $data['status'] = 1;
                $data['xxx'] = session('numDestroyed');

                $enemyShips = (array)json_decode($this->gameDB['ships' . $this->hisNumber]);

                foreach ($enemyShips as $key => $dataShip) {
                    $ship = (array)$dataShip;

                    for ($i = 0; $i < $ship['type']; $i++) {
                        if ($ship['destroyed']) break;

                        // Фиксируем попадание, status - 2
                        if ($ship['posx'.$i] == $posx && $ship['posy'.$i] == $posy) {
                            $data['status'] = 2;
                            $data['moveId'] = auth()->user()->id;

                            $attacked = session('attackedShips');
                            $attacked[$ship['numberShip'] - 1] += 1;
                            session()->put('attackedShips', $attacked);

                            // Уничтожение корабля, status - 3
                            if (session('attackedShips')[$ship['numberShip'] - 1] == $ship['type']) {
                                $currentNum = session('numDestroyed');
                                session()->put('numDestroyed', $currentNum + 1);
                                $enemyShips[$key]->destroyed = 1;

                                $this->gameDB->update([
                                    'ships'.$this->hisNumber => json_encode($enemyShips)
                                ]);

                                $updateCoords = $this->destroyShip($ship, session('action'));
                                session()->put('action', $updateCoords);
                                
                                $data['status'] = 3;
                                $data['ship'] = $ship;
                                
                                broadcast(new ShotEvent(auth()->user(), $enemyId, $data, $posx, $posy))->toOthers();

                                // Победа (если уничтожены все корабли)
                                if (session('numDestroyed') == 10) {
                                    $winner = auth()->user()->id;
                                    $loser = $this->gameDB['user'.$this->hisNumber.'_id'];

                                    if ($this->finishGame($this->gameDB, $winner, $loser)) {
                                        $data['status'] = 4;
                                        broadcast(new EnemyWinEvent($winner, $loser))->toOthers();
                                    } else {
                                        $data['status'] = 0;
                                    }
                                }

                                return $data;
                            }

                            broadcast(new ShotEvent(auth()->user(), $enemyId, $data, $posx, $posy))->toOthers();
                            return $data;
                       }
                    }
                }

                // Промах, status - 1 (Меняем ход игрока; оповещаем оппонента)
                $this->gameDB->update([
                    'players_move' => $enemyId,
                ]);

                broadcast(new ShotEvent(auth()->user(), $enemyId, $data, $posx, $posy))->toOthers();
                return $data;
            }
        }

        return $data; // Error, status - 0
    }
}