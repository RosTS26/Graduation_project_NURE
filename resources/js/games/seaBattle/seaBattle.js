// Демонстрация на экране координат Х и У
const abc = 'ABCDEFGHIJ';

for (let i = 0; i < 10; i++) {
	let viewX = $('<div></div>').addClass('view-coord').html(abc[i]);
	let viewY = $('<div></div>').addClass('view-coord').html(i + 1);
	
	$('.view-x').append(viewX);
	$('.view-y').append(viewY);
}


// Показывать текст с предупреждением
function infoPanel(text) {
    clearTimeout(timerView);
    $('.panel-info').html(text).css('display', 'block');
    timerView = setTimeout(() => $('.panel-info').css('display', 'none'), 5000);
}

// Принудительно отключение от канала
function forcedShutdown () {
    if (window.usersPrivate.length === 2) {
        let id1 = window.usersPrivate[0].id;
        let id2 = window.usersPrivate[1].id;
        let roomName = 'Sea-battle-' + Math.min(id1, id2) + '-' + Math.max(id1, id2);

        window.Echo.leave(roomName);
    }
}

// Генерация ячеек 10х10
function setCells(cellName, field) {
    for (let y = 0; y < 10; y++) {
        for (let x = 0; x < 10; x++) {
            let cell = $('<div></div>').addClass(cellName); // Создаем ячейку

            if (cellName == 'my-cell') $(cell).attr('status', 0);
            $(cell).attr('letter', abc[x])
                .attr('posX', x + 1)
                .attr('posY', y + 1);

            $(field).append(cell);
        }
    }
}

// Переключатель таймера
export function timerSwitch(whose) {
    $('.'+ move +'-timer-game').css('color', '#000');
    move = whose;
    $('.'+ whose +'-timer-game').css('color', 'red');

    timerWorker.postMessage({ command: 'stop-timer'});
    timerWorker.postMessage({ command: 'game-'+ whose +'-timer'});

    // Замена текста хода
    let moveText = whose === 'my' ? "You'r move!" : 'Enemy move!';
    $('.players-move').html(moveText)
        .css('animation', whose === 'my' ? 'shadowAnimation 1s infinite' : 'none');
}

// ================================================================================

const timerWorker = new Worker('/js/games/seaBattle/timer-worker.js');
timerWorker.postMessage({
    command: 'csrf',
    csrf: csrf,
});
const statusList = ['empty', 'selected', 'occupied', 'buffer']; // Статус клетки поля
const arrColors = ['', '#2FCC71', '#1e488c', '']; // Цвета клетки (F4CA16 - желтый)
var fieldOfFire = []; // Поле для фиксирования выстрелов
var field = []; // Игровое поле 10х10
var ships = []; // Корабли - их тип и координаты
var numOfShips = [4, 3, 2, 1]; // Кол-во кораблей (от мелких до большого)
var selectedShip = 0;
var align = 'x';
var timerView;
var moveId = 0;
var move;

for (let y = 0; y < 10; y ++) {
	field[y] = [];
	fieldOfFire[y] = [];
	for (let x = 0; x < 10; x++) {
		field[y].push(0);
		fieldOfFire[y].push(0);
	}
}

export function setMoveId(item) {
    moveId = item;
}

// === Очистка поля ===
function clearField() {
	numOfShips = [4, 3, 2, 1];
	ships = [];

	for (let y = 0; y < 10; y ++) {
		field[y] = [];
		for (let x = 0; x < 10; x++) {
			field[y].push(0);
		}
	}

	$('.my-cell').css('background', '').attr('status', 0);

	numOfShips.forEach((item, index) => {
		$('.quantity-' + (index + 1)).html(item + 'x:');
	});
}

// === Показываем выбранный кораблик на поле ===
function viewShipHover (element, status) {
	if (selectedShip < 1 || selectedShip > 4) return 0;

	let elements = [];
	let thisX = Number($(element).attr('posx'));
	let thisY = Number($(element).attr('posy'));
	let freeCell = true;
	// Поворот фигуры
    let rX = align == 'x' ? 1 : 0;
	let rY = align == 'y' ? 1 : 0;

    if (thisY > 11 - selectedShip && rY) thisY = 11 - selectedShip;
	else if (thisX > 11 - selectedShip && rX) thisX = 11 - selectedShip;
	
	// Проверяем клетки и записываем в массив 
	for (let i = 0; i < selectedShip; i++) {
		let cell = $(`[posX = "${thisX + (i * rX)}"][posY = "${thisY + (i * rY)}"]`);
		
		if (cell.attr('status') != 0 && cell.attr('status') != 1) freeCell = false;

		elements.push(cell);
	}

	// Отображаем кораблик на поле
	elements.forEach(item => {
		if (freeCell) item.attr('status', status); // Устанавливаем статус клекте, если она свободна
		
		// Красим клетки в подходящий цвет
		if (status === 0 || freeCell) {
			let itemStatus = Number($(item).attr('status'));
			item.css('background', arrColors[itemStatus]);
		} else item.css('background', 'red');
	});
}

// === Размещение корабля на поле ===
function putShip(cells) {
	const shipType = cells.length; // Тип корабля (одинарный, двойной...)
	
	// Этап проверок
	if ($('.btn-ship:checked').length == 0) {
		infoPanel('Select the desired ship!');
		return 0;
	}
	
	if (numOfShips[shipType - 1] < 1) {
		infoPanel('Maximum number of ships of this type!');
		return 0;
	}

	if (cells.length < 1 || shipType > 4) return 0;

    const numberShip = numOfShips.reduce(function (accumulator, currentValue) {
        return accumulator + currentValue;
    }, 0);
	
	// Формируем объект корабль
	let ship = { 
        type: shipType,
        numberShip: numberShip,
    };
	
	cells.each((index, item) => {
		if ($(item).attr('status') != 1) return 0; // Если клетка занята - leave

		ship['posx' + index] = Number($(item).attr('posx'));
		ship['posy' + index] = Number($(item).attr('posy'));

		field[ship['posy' + index] - 1][ship['posx' + index] - 1] = numberShip;
		
		// Закрываем клетки, которые находятся рядом
		for (let y = -1; y < 2; y++) {
			for (let x = -1; x < 2; x++) {
				let thisCell = $(`[posX = "${ship['posx' + index] + x}"][posY = "${ship['posy' + index] + y}"]`);
				
				if (thisCell.attr('status') == 0) {
					thisCell.attr('status', 3);
                    // field[ship['posy' + index] + y - 1][ship['posx' + index] + x - 1] = 2;
				}
			}
		}
	});
	
	// -1 на экране доступных кораблей
	let quantity = $('.quantity-' + shipType).html()[0];
	$('.quantity-' + shipType).html(quantity - 1 + 'x:');
	
	ships.push(ship);
	numOfShips[shipType - 1]--; // Уменьшаем кол-во доступных кораблей на 1

	viewShipHover(cells[0], 2); // Ставим корабль на поле
}

// === Рандомное размещение кораблей на поле ===
function randomField() {
	clearField();

	let arrWidthCoords = []; // Координаты и статус всех ячеек

	for (let y = 1; y < 11; y ++) {
		for (let x = 1; x < 11; x++) {
			let obj = {
				'x': x,
				'y': y,
				'status': 0,
			}
			arrWidthCoords.push(obj);
		}
	}

	// Проходимся по каждому кораблю
	for (let i = numOfShips.length; i > 0; i--) {
		const type = i;
		const num = numOfShips[i - 1];

		selectedShip = type;

		// Текущий корабль
		for (let j = 0; j < num; j++) {
			// Рандомное положение корабля
			const randomAlign = Math.round(Math.random()) == 0 ? 'x' : 'y'; // Паралельно координате
			const xory = randomAlign === 'x' ? 'y' : 'x';
			align = xory;

			// x or y = MAX 10 - type + 1
			const freeCells = arrWidthCoords.filter(item => item.status === 0 && item[xory] <= 10 - type + 1);
			let placesForShip = [];

			// Находим все доступные места для корабля на поле
			freeCells.forEach((item) => {
				const firstCoord = item[xory];
				const alignCoord = item[randomAlign];
				
				let place = []; // Предпологаемое место корабля
				let check = 0;

				for (let cell = 0; cell < type; cell++) {
					 // Ищем следующий элемент
					let foundCell = arrWidthCoords.find(obj => obj[xory] == firstCoord + cell && obj[randomAlign] == alignCoord);
					place.push(foundCell);
					if (foundCell.status === 0) check++;
					else break;
					// check += (thisCell.status === 0) ? 1 : 0; 
				}

				if (check === type) placesForShip.push(place); 
			});

			// Выбираем рандомуню клетку из доступных мест
			let index = Math.floor(Math.random() * placesForShip.length);
			const randomPlace = placesForShip[index];

			// Выбираем элементы поля DOM
			for (let el = 0; el < type; el++) {
				let posx = randomPlace[el].x;
				let posy = randomPlace[el].y;

				let thisCell = $(`[posx = "${posx}"][posy = "${posy}"]`);
				thisCell.attr('status', 1); // Занимаем клетку для корабля
				
				// Так же занимаем клетку в массиве свободных клеток
				for (let y = -1; y < 2; y++) {
					for (let x = -1; x < 2; x++) {
						if (posy + y < 1 || posx + x < 1 ||
							posy + y > 10 || posx + x > 10) continue;

						let id = arrWidthCoords.findIndex(obj => obj.x == posx + x && obj.y == posy + y);
						arrWidthCoords[id].status = 1;
					}
				}
			}

			let cells = $(`[status = 1]`);
			putShip(cells);
		}
	}

    $('#ship-4').click();
}

// =======================================================================================

// ===== Подготовка к игре =====
export function preparation () {
    sessionStorage.setItem('reboot', true); // Перезагрузка страницы - проигрыш

    $('.search').remove();

    setCells('my-cell', '.my-field');
    $('.game').css('display', 'flex');

    // Запускаем таймер
    timerWorker.postMessage({ command: 'preparation-timer'});

    timerWorker.onmessage = function(event) {
        if (event.data === "end-preparation") {
            ready(field, ships, numOfShips);
        } else {
            $('.timer-value').html(event.data);
        }
    };

    // === Обработка нажатий на кнопки ===
    $('.my-cell').on('click', function() {
        let cells = $(`[status = 1]`); // Координаты занятых клеток
        putShip(cells);
    }).on('mouseenter', function() {
        viewShipHover($(this), 1);
    }).on('mouseleave', function() {
        viewShipHover($(this), 0);
    });

    $('.btn-ship').on('click', function() {
        selectedShip = Number($(this).attr('id').slice(-1));
    });

    // Поворот корабля на 90 град
    $('#rotate').on('click', function() {
        align = align === 'x' ? 'y' : 'x';;
    });

    // Очистка поля
    $('#clear-field').on('click', function() {
        clearField();
    });

    // Рандомное заполнение поля
    $('#random-field').on('click', function() {
        randomField();
    });

    // Готовность к игре
    $('#ready').on('click', function() {
        for (let i = 0; i < numOfShips.length; i++) {
            if (numOfShips[i] !== 0) {
                infoPanel('Place all ships on the field!');
                return 0;
            }
        }
        
        timerWorker.postMessage({ command: 'stop-timer'});
        ready(field, ships, numOfShips);
    });

    $('#ship-4').click();
}

// Ожидание оппонента (ОТПРАВИТЬ НА СЕРВЕР ПРОВЕРКУ ВРЕМЕНИ ЧЕРЕЗ 1 МИНУТУ!!!)
function waitingEnemy() {
    let gif = $('<div></div>').addClass('gif-box').css({
        'height': '213px',
        'width': 'auto',
        'box-shadow': '0 0 8px 8px #C8C8C8 inset'
    });
    let text = $('<p>Waiting for the opponent...</p>').addClass('timer-ship');
    
    $('.ships-block').html(gif).append(text).css('justify-content', 'center');

    $('.my-cell').off('click').off('mouseenter').off('mouseleave');
}

// ===== Готов к игре =====
function ready(fieldDB, shipsDB) {
	// Отправляем данные на сервер. При положительном ответе запускаем игру startGame
	let url = '/game/sea-battle/ready-game';
            
    axios.post(url, {field: fieldDB, ships: shipsDB})
        .then(function(res) {
            switch (res.data.status) {
                case 0: 
                    console.log(res.data);
                    window.Echo.disconnect();
                    return 0;
                case 1: // Waiting enemy; ships - error
                    waitingEnemy();
                    break;
                case 2: // Waiting enemy; ships - OK
                    moveId = res.data.moveId;
                    startGame();
                    break;
            }

            // Опопвещаем игрока, что корабли были сгенерированы на сервере
            if (res.data.shipsError) {
                infoPanel('The field with your ships was random generated!');
            }
            
            // Прорисовка итогового поля
            clearField();
            res.data.ships.forEach(obj => {
                let len = Object.keys(obj).length / 2; // Длина корабля

                for (let i = 0; i < len; i++) {
                    let cell = $(`[posx = "${obj['posx'+i]}"][posy = "${obj['posy'+i]}"].my-cell`);
                    cell.attr('status', 2).css('background', '#1e488c'); // Занимаем клетку для корабля
                }
            });
        })
        .catch(function (error) {
            console.log(error);
        });
}

// =======================================================================================

// ===== Начало игры (главная функция) =====
export function startGame() {
	$('.menu').remove();

	$('.my-cell').each((index, item) => {
		let status = Number($(item).attr('status'));
		if (status == 1) {
			$(item).attr('status', 0);
			status = 0;
		}
			
		$(item).css('background', arrColors[status]);
	});

	$('.my-cell').off('click').off('mouseenter').off('mouseleave');

	// Создаем второе поле
	setCells('his-cell', '.his-field');
	$('.field2').css('display', 'flex');
	$('.game-menu').css('display', 'flex')

	// Запуск таймера игры
    move = moveId == myID ? 'my' : 'his';
    $('.'+ move +'-timer-game').css('color', 'red');

    timerWorker.postMessage({ command: 'game-'+ move +'-timer'});
    timerWorker.onmessage = function(event) {
        switch (event.data) {
            case "end-my-game":
                getGameOverInfo(false);
                break;
            case "end-his-game":
                getGameOverInfo(true);
                break;
            default:
                $('.'+ move +'-timer-game').html(event.data);
        }
    };

    // Чей ход?
    let moveText = move === 'my' ? "You'r move!" : 'Enemy move!';
    $('.players-move').html(moveText)
        .css('animation', move === 'my' ? 'shadowAnimation 1s infinite' : 'none');

	// === Методы обработки нажатия на поле соперника ===
	$('.his-cell').on('click', async function() {
		// Проверка возможности хода
		if (moveId == myID) {
            let thisMoveId = moveId;
			let posy = Number($(this).attr('posy'));
			let posx = Number($(this).attr('posx'));
            moveId = 0;

			if (fieldOfFire[posy-1][posx-1] === 0) {
                moveId = await shot(posx, posy, thisMoveId, $(this)); // Ожидаем ответа от сервера
            } else moveId = thisMoveId;
		}
	});

    // Сдаться
    $('#give-up').on('click', () => {
        gameOver(true);
    });
}

// ===== Конец игры =====
export function gameOver(showInfo) {
    let url = '/game/sea-battle/game-over';
    axios.post(url, {check: 'qwerty'})
        .then(res => {
            if (!res.data) console.log('Status: Error');
            else if (showInfo) getGameOverInfo(false);
        })
        .catch(error => {
            console.log("Server error...\n" + error);
        });
}

// Информация про конец игры
export function getGameOverInfo(winOrLose) {
    sessionStorage.setItem('checkGameOver', true); // Конец игры (true)

    timerWorker.postMessage({ command: 'stop-timer'}); // Останавливаем таймер

    $('.my-cell').off('click').off('mouseenter').off('mouseleave');
    $('.his-cell').off('click');
    $('#give-up').prop('disabled', true);

    let gameOverText = $('<p>Game over!</p>');
    let whoWinText = $('<p></p>')
        .html(winOrLose ? 'Victory!' : 'Loss!')
        .css('color', winOrLose ? 'green' : 'red');

    $('.general-info').html(gameOverText).append(whoWinText);
    $('.ships-block').html(gameOverText)
        .append(whoWinText)
        .css('justify-content', 'center');
}

// Фиксация выстрела (Возвращает id игрока, который должен ходить)
async function shot(posx, posy, moveId, cell) {
    return new Promise((resolve, reject) => {
        // Отправляем данные на сервер. Выстрел по полю игрока
        let url = '/game/sea-battle/shot';
                            
        axios.post(url, {posx: posx, posy: posy})
            .then(function(res) {
                moveId = res.data.moveId ?? moveId; // Следующий ход для игрока 
                fieldOfFire[posy-1][posx-1] = 1; // Записываем выстрел
    
                switch (res.data.status) {
                    case 0: 
                        console.log('Error shot...\n'); // Error, status - 0
                        fieldOfFire[posy-1][posx-1] = 0;
                        break;
                    case 1: // Промах
                        cell.html('●󠇫').addClass('busy-cell');
                        timerSwitch('his');
                        break;
                    case 2: // Попадание
                        cell.html('x').css('color', 'red').addClass('busy-cell');
                        break;
                    case 3: // Уничтожил
                        cell.html('x').css('color', 'red').addClass('busy-cell');
                        destroyShip(res.data.ship, 'his');
                        break;
                    case 4: // Победа
                        cell.html('x').css('color', 'red').addClass('busy-cell');
                        destroyShip(res.data.ship, 'his');
                        getGameOverInfo(true);
                        break;
                }

                resolve(moveId); // Возвращаем promise после обработки ответа от сервера
            })
            .catch(function (error) {
                console.log("Server error...\n" + error);
                resolve(moveId);
            });
    });
}

// Корабль уничтожен
export function destroyShip(ship, whose) {
    for (let i = 0; i < ship['type']; i++) {
        for (let y = -1; y < 2; y++) {
            for (let x = -1; x < 2; x++) {
                let cell = $(`[posx = "${ship['posx'+i] + x}"][posy = "${ship['posy'+i] + y}"].${whose}-cell`);

                if (ship['posy'+i] + y < 1 || ship['posx'+i] + x < 1 ||
                    ship['posy'+i] + y > 10 || ship['posx'+i] + x > 10 ||
                    cell.hasClass('busy-cell')) continue;

                cell.html('●󠇫').addClass('busy-cell');

                whose === 'his' ? fieldOfFire[ship['posy'+i] + y - 1][ship['posx'+i] + x - 1] = 1 : 0;
            }
        }
    }

    let horm = whose === 'his' ? 'my' : 'his';
    let numDestroy = Number($(`.${horm}-destroy`).html()) + 1;
    $(`.${horm}-destroy`).html(numDestroy);
}

// ==========================================================================================
