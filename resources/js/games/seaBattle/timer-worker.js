
// Данные для таймеров
let csrf;
let myTimer = 10 * 60 * 1000;
let hisTimer = 10 * 60 * 1000;
let preparationTimer = 60 * 1000;
let timer;

// Подсчет времени
function getTimer(fullTime) {
	let minutes = Math.floor(fullTime / 60000);
    let seconds = Math.floor((fullTime % 60000) / 1000); // Остаток миллисекунд переводим в секунды
	seconds = seconds < 10 ? '0' + seconds : seconds;
    
	return minutes + ':' + seconds;
}

// Таймер для подготовки
function timerForPreparation() {
    preparationTimer -= 1000;
	
    if (preparationTimer < 0) {
        clearInterval(timer);
		postMessage("end-preparation");
        return 0;
    }

    postMessage(getTimer(preparationTimer));
}

// Мой таймер
function myTimerForGame() {
    myTimer -= 1000;

	if (myTimer < 0) {
        clearInterval(timer);
        
        const url = '/game/sea-battle/game-over';
        const data = {check: 'qwerty'};

        // Отправка POST-запроса на сервер
        fetch(url, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(data),
        })
        .then(response => response.text())
        .then(result => {
            result.trim() === '1' ? postMessage("end-my-game") :
                console.log('Status: Error');

            self.close();
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });
    } else {
        postMessage(getTimer(myTimer));
    }
}

// Таймер оппонента
function hisTimerForGame() {
    hisTimer -= 1000;

	if (hisTimer < 0) {
        clearInterval(timer);
		postMessage("end-his-game");
        self.close();
        return 0;
    }

    postMessage(getTimer(hisTimer));
}

onmessage = function(event) {
    let command = event.data.command;

    switch (command) {
        case 'csrf':
            csrf = event.data.csrf;
            break;
        case 'preparation-timer':
            timer = setInterval(timerForPreparation, 1000);
            break;
        case 'game-my-timer':
            timer = setInterval(myTimerForGame, 1000);
            break;
        case 'game-his-timer':
            timer = setInterval(hisTimerForGame, 1000);
            break;
        case 'stop-timer':
            clearInterval(timer);
    }
};
