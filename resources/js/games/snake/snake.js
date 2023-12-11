// "use strict"
//let ajax = new XMLHttpRequest();
// Подключение Axios
import axios from 'axios';

const canvas = document.getElementById("game");
const ctx = canvas.getContext("2d");

// Загружаем текстуру поля
const ground = new Image();
ground.onload = function() {
	ctx.drawImage(ground, 0, 0);
	// drawImageProportionally(ground);
	ctx.fillStyle = "blue";
	ctx.font = "50px Arial";
	ctx.fillText("Snake!", box * 7, box * 10);
	ctx.fillText('Press "Start"', box * 5, box * 12);
}
ground.src = "/images/games/snake/ground.png";

// Загружаем текстуру еды (яблоко)
const foodImg = new Image();
foodImg.src = "/images/games/snake/food.png";

// Функция изменения размеров экрана (адапативность)
function drawImageProportionally(ground, food) {
	// Размеры canvas
	let canvasWidth = canvas.width;
    let canvasHeight = canvas.height;

	// Соотношение сторон у рисунков
	let groundAspectRatio = ground.width / ground.height;
	let foodAspectRatio = ground.width / ground.height;

	// Вычислите новые размеры изображений, чтобы сохранить пропорции
    let groundNewWidth, groundNewHeight;
    let foodNewWidth, foodNewHeight;

	// Проверяем соотношение по ширине и высоте
	if (canvasWidth / canvasHeight > groundAspectRatio) {
        groundNewWidth = canvasHeight * groundAspectRatio;
        groundNewHeight = canvasHeight;
    } else {
        groundNewWidth = canvasWidth;
        groundNewHeight = canvasWidth / groundAspectRatio;
    }

	ctx.clearRect(0, 0, canvasWidth, canvasHeight);
	ctx.drawImage(ground, 0, 0, groundNewWidth, groundNewHeight);
}

let topScore;
let rendering;
let coef = 600 / canvas.width;	// Коэфициент изменения размеров экрана
let box = 32 / coef; 			// Размер ячейки
let score = 0; 			// Кол-во очков
let speed = 200; 		// Скорость игры (обновление draw в мс)

// Спавн еды на поле
let food = {
	x: Math.floor((Math.random() * 17 + 1)) * box,
	y: Math.floor((Math.random() * 15 + 3)) * box,
};

// Начальный спавн змейки
let snake = [];
snake[0] = {
	x: 9 * box,
	y: 10 * box
};

document.addEventListener("keydown", direction); // работа с клавиатурой

let dir; // переменная для клавиш

// Считывания нажатия клавиш
function direction(event) {
	if(event.keyCode == 65 && dir != "right") {
		dir = "left";
	} else if(event.keyCode == 87 && dir != "down") {
		dir = "up";
	} else if(event.keyCode == 68 && dir != "left") {
		dir = "right";
	} else if(event.keyCode == 83 && dir != "up") {
		dir = "down";
	}
}

document.getElementById('btn-left').addEventListener('click', function() {
	if (dir !== "right") dir = "left";
});
document.getElementById('btn-up').addEventListener('click', function() {
	if (dir !== "down") dir = "up";
});
document.getElementById('btn-right').addEventListener('click', function() {
	if (dir !== "left") dir = "right";
});
document.getElementById('btn-down').addEventListener('click', function() {
	if (dir !== "up") dir = "down";
});

// Считывание нажатие на панель кнопок управления
// $('.btn').on('click', function() {
// 	if($(this).attr('id') == 'btn-left' && dir != "right") {
// 		dir = "left";
// 	} else if($(this).attr('id') == 'btn-up' && dir != "down") {
// 		dir = "up";
// 	} else if($(this).attr('id') == 'btn-right' && dir != "left") {
// 		dir = "right";
// 	} else if($(this).attr('id') == 'btn-down' && dir != "up") {
// 		dir = "down";
// 	}
// });

// Проигрыш в случае, если змейка съест свой хвост
function eatTail(head, arrSnake) {
	for(let i = 1; i < arrSnake.length; i++) {
		if(head.x == arrSnake[i].x && head.y == arrSnake[i].y) {
			gameOver();
			break;
		}
	}
}

// Код проигрыша (Удар об стену)
function hitWall (x, y) {
	if(x < box || 
	   x > box * 17 || 
	   y < box * 3 || 
	   y > box * 17) {
		gameOver();
	}
}

function gameOver() {
	clearInterval(rendering);
	document.getElementById("btnStart").disabled = false;
	document.getElementById("btnStart").value = "Restart";
	ctx.fillStyle = "blue";
	ctx.fillText("Game Over :(", box * 5, box * 10);
	ctx.fillText(`Score: ${score}`, box * 6.5, box * 12);

	// POST запрос на сервер
	let url = '/game/snake/game-over';

	axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf; // Устанавливаем заголовок для всех запросов

	axios.post(url, { score: score, token: token })
		.then(function(response) {
			// Успешный ответ
			ctx.fillText(response.data, box * 5, box * 14);
		})
		.catch(function (error) {
			// Обработка ошибки
			alert("Server error...\n" + error);
		});

	score = 0;
	snake = [];
	snake[0] = {
		x: 9 * box,
		y: 10 * box
	}
}

// функция игры
function draw() {
	// Рисуем поле и еду
	ctx.drawImage(ground, 0, 0);
	ctx.drawImage(foodImg, food.x, food.y);

	// Цвет змейки и ее расположение
	for (let i = 0; i < snake.length; i++) {
		// Четный элемент - черный, нечетный - красный 
		ctx.fillStyle = i == 0 ? "#B61063" : i % 2 == 0 ? "#FE1B2D" : "#E11425";
		ctx.fillRect(snake[i].x, snake[i].y, box, box);	// Рисуем квадрат элемента змейки
	}

	let snakeX = snake[0].x;
	let snakeY = snake[0].y;

	// Если змейка кушает еду
	if(snakeX == food.x && snakeY == food.y) {
		score++;
		food = {
			x: Math.floor((Math.random() * 17 + 1)) * box,
			y: Math.floor((Math.random() * 15 + 3)) * box,
		}

		// Проверка спавна еды
		let iteration = 0;
		let flag = true

		while(flag) {
			flag = false;
			if (iteration > 10) {
				// Спавним еду в хвосте, если рандом дает задержку
				food = {
					x: snake.slice(-1)[0].x,
					y: snake.slice(-1)[0].y
				}
				flag = false;
			} else {
				for (let i = 0; i < snake.length; i++) {
					// Переспавниваем еду рандомно, если она оказалась внутри змейки
					if (food.x == snake[i].x && food.y == snake[i].y) {
						food = {
							x: Math.floor((Math.random() * 17 + 1)) * box,
							y: Math.floor((Math.random() * 15 + 3)) * box
						};
						flag = true;
						iteration++;
						break;
					}
				}
			}
		}
	} else snake.pop();

	// Надпись очков
	ctx.fillStyle = "white";
	ctx.fillText(":", box * 1.9, box * 1.6);
	ctx.fillText(score, box * 2.5, box * 1.7);

	// Направление движения змейки
	if(dir == "left") snakeX -= box;
	if(dir == "right") snakeX += box;
	if(dir == "up") snakeY -= box;
	if(dir == "down") snakeY += box;

	let newHead = {
		x: snakeX,
		y: snakeY
	};
	
	snake.unshift(newHead);		// Добавляем новый элемент змейки (голову) в начало массива элементов
	hitWall (snake[0].x, snake[0].y);
	eatTail(snake[0], snake);
}

function game() {
	// Вызов функции игры с интервалом speed мс
	rendering = setInterval(() => draw(), speed);
}

// Кнопка запуска игры
let btnStart = document.querySelector("#btnStart");
btnStart.addEventListener("click", function() {
	document.getElementById("btnStart").disabled = true; 
	game(); 
});

// Обработчик события изменения размера окна браузера
// window.addEventListener("resize", function() {
//     drawImageProportionally(ground);
// });