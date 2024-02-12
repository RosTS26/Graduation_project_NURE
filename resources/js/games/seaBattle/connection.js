import * as SB from './seaBattle.js';

axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

window.onload = () => {
    // Если произошла перезагрузка - завершаем игру
    var reboot = sessionStorage.getItem('reboot');
    var checkGG = sessionStorage.getItem('checkGameOver');

    if (reboot && !checkGG) SB.gameOver(false);

    sessionStorage.removeItem('reboot');
    sessionStorage.removeItem('checkGameOver');
}

// Предупреждение перед закрытием окна
window.addEventListener('beforeunload', function (e) {
    const confirmationMessage = 'Are you sure you want to leave the game?';
    e.returnValue = confirmationMessage;
    return confirmationMessage;
});

// Конец игры, если пользователь закрыл страницу
window.addEventListener('unload', () => {
    if (!sessionStorage.getItem('checkGameOver')) SB.gameOver(false);
});

let usersConnect;
let waitingTime;
let userStatus = 'host'; // Статус игрока, определяющий хоста и гостя

// Для чата
// function test(opponentID) {
//     $('.search').remove();

//     let textarea = $('<textarea>').addClass('text-input');
//     let button = $('<button>Enter</button>').addClass('btn btn-primary');

//     $('.test').append(textarea).append(button);

//     $('.btn').on('click', function() {
//         if ($('.text-input').val().trim() != '') {
//             let sendMsg = $('.text-input').val();
//             let url = '/game/sea-battle/confirm';
            
//             axios.post(url, { data: sendMsg, id: opponentID})
//                 .then(function(response) {
//                     $('.users-status').html(response.data);
//                 })
//                 .catch(function (error) {
//                     alert("Server error...\n" + error);
//                 });
//         }
//     });
// }

// Создаем игру в БД
function DBCreateInfo(user1_id, user2_id) {
    let url = '/game/sea-battle/start-game';
            
    axios.post(url, {
            user1_id: user1_id,
            user2_id: user2_id,
        })
        .then(function(response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log("Server error...\n" + error);
        });
}

// Запись данны игры в сессию для второго игрока
function SetSessionData(gameID, roomName) {
    let url = '/game/sea-battle/set-session';
            
    axios.post(url, {
            gameID: gameID,
        })
        .then(function(response) {
            console.log(response.data);

            if (!response.data) {
                window.Echo.leave(roomName);
                console.log('Connection error: game id is not correct!');
            }
        })
        .catch(function (error) {
            window.Echo.leave(roomName);
            console.log("Server error...\n" + error);
        });
}


// Web-socket приватной комнаты для двух игроков
function connectPrivateRoom(user1_id, user2_id, userStatus) {
    // Название приватной комнаты
    const roomName = 'Sea-battle-' + Math.min(user1_id, user2_id) + '-' + Math.max(user1_id, user2_id);
    window.usersPrivate;

    window.Echo.join(roomName)
        .here((users) => {
            window.usersPrivate = users;
            
            // Ждем ответ от пользователя в течении 5-ти секунд
            if (userStatus === 'host') {
                waitingTime = setTimeout(() => {
                    window.Echo.leave(roomName);
                    $('.text-search').html('Connection error...');
                    return 0;
                }, 5000);
            }
            // Проверка, находится ли хост в комнате
            else if (userStatus === 'guest' && users.length < 2) {
                window.Echo.leave(roomName);
                $('.text-search').html('Connection error...');
                return 0;
            } else {
                SB.preparation(); // Подготовка к игре
            }
        })
        .joining((user) => {
            // Хост запускает игру
            if (userStatus === 'host') {
                clearTimeout(waitingTime);
                DBCreateInfo(user1_id, user2_id, userStatus);
                SB.preparation();
            }

            // Записываем подключившегося игрока
            const index = window.usersPrivate.findIndex(obj => obj.id === user.id); 
            if (index == -1) window.usersPrivate.push(user);
        })
        .leaving((user) => {
            console.log('The enemy has left the game!');
        });

    // Прослушиваем приватный канал
    window.Echo.private(roomName)
        .listen('.SetSessionData', (res) => { // Запись данных в сессию
            if (userStatus === 'guest') {
                SetSessionData(Number(res.gameID), roomName);
            }
        })
        .listen('.EnemyReady', res => { // Противник готов к игре
            SB.setMoveId(res.moveId);
            SB.startGame();
        })
        .listen('.EnemyShot', res => { // ПРотивник совершил выстрел
            SB.setMoveId(res.data.moveId);
            const cell = $(`[posx = "${res.posx}"][posy = "${res.posy}"].my-cell`);
            
            switch (res.data.status) {
                case 1:
                    cell.html('●󠇫').addClass('busy-cell');   
                    SB.timerSwitch('my');
                    break;
                case 2:
                    cell.html('x').css('color', 'red').addClass('busy-cell');
                    break;
                case 3:
                    cell.html('x').css('color', 'red').addClass('busy-cell');
                    SB.destroyShip(res.data.ship, 'my');
                    break;
            }
        })
        .listen('.EnemyLost', res => { // Враг сдался или вышло время
            if (res.userID !== Number(myID)) SB.getGameOverInfo(true);
        })
        .listen('.EnemyWin', res => { // Враг победил
            if (res.userID !== Number(myID)) SB.getGameOverInfo(false);
        });
}

// ==========================================================================================================

// Web-socket connect (Общая комната для поиска соперников)

window.Echo.join('Game-search')
    .here((users) => {
        usersConnect = users.filter(user => user.id !== Number(myID));
        
        // usersConnect.forEach(element => {
        //     $('.users-connect').append(element.name + '<br>');
        // });
        
        // Если есть кто-то онлайн, выбираем первого игрока
        if (usersConnect.length >= 1) {
            const enemy = usersConnect[0];
            
            // Подключение к приватной комнате (host - user1_id)
            window.Echo.leave('Game-search');
            connectPrivateRoom(myID, enemy.id, 'host');
            
            // Отправляем запрос для конекта с игроком
            let url = '/game/sea-battle/connect';
            axios.post(url, { data: enemy })
                .then(function(response) {
                    $('.text-search').html('Player waiting...');
                    console.log('Request has been sent: ID ' + enemy.id + '\nStatus code: ' + response.data);
                })
                .catch(function (error) {
                    alert("Server error...\n" + error);
                });
        }
    })
    .error((error) => {
        $('.text-search').html("You can't play 2 games at the same time!").css('color', 'red');
    });

// Подтверждение создание матча
window.Echo.channel('Game-search')
    .listen('.ConfirmConnection', (res) => {
        $('.text-search').html('Connection...');

        if (res.enemyID == myID) {
            window.Echo.leave('Game-search');
            connectPrivateRoom(myID, res.userID, 'guest');
            
            // TEST задержки
            // setTimeout(() => {
            // }, 3000);
        }
});