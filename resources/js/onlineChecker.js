
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
	broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
});

// Настройка Axios для отправки заголовка X-Socket-ID
// window.Echo.connector.pusher.connection.bind('connected', function () {
//     axios.defaults.headers.common['X-Socket-ID'] = window.Echo.socketId();
// });

let timeoutIds = {};

window.Echo.join('Auth-check')
    .here((users) => {

        window.usersOnline = users;
        $('.check-online').each(function() {
            let thisId = Number($(this).attr('id'));
            // Проверяем, есть ли в списках онлайн наши друзья
            if (users.some(obj => obj.id === thisId)) {
                $(this).css('display', 'block');
            }
        });

        $('.loading').remove();
        $('.user-item').css('display', 'flex');
        $('.not-friends').css('display', 'block');
    })
    .joining((user) => {
        if (timeoutIds[user.id]) {
            clearTimeout(timeoutIds[user.id]); // Отменяем отлючение
            delete timeoutIds[user.id];
        }
        $('#'+ user.id +'.check-online').css('display', 'block');
        $('#'+ user.id +'.user-status').html('online').css('color', '#198754');
    })
    .leaving((user) => {
        // Ждем 3 секунды и отключаем пользователя
        timeoutIds[user.id] = setTimeout(() => {
            $('#'+ user.id +'.check-online').css('display', 'none');
            $('#'+ user.id +'.user-status').html('offline').css('color', '#C09000');
            delete timeoutIds[user.id]; // Удаляем свойство после завершения фукции
        }, 3000);
    });

// ============================================================================

// // Функция выхода из сети, если пользователь не онлайн
// function sendLogoutRequest() {
//     document.getElementById('logout-form').submit();
// }

// // Сброс времени для выхода пользователя из сеанса
// function resetActivityTimeout() {
//     clearTimeout(activityTimeout);
//     activityTimeout = setTimeout(sendLogoutRequest, 1800000); // Таймер на 30 минут
// }

// let activityTimeout = setTimeout(sendLogoutRequest, 1800000);

// // Если пользователь двигает мышкой или жмет на клавиши клавиатуры, то таймер сбрасывается
// document.addEventListener('mousemove', resetActivityTimeout);
// document.addEventListener('keydown', resetActivityTimeout);

// window.addEventListener("unload", function() {
//     navigator.sendBeacon("/offline");
// });

// window.onbeforeunload = function(event) {
//     if ( $( event.target.activeElement ).is("a") || refresh === true) {
//         return "test";
//     }
//     return "Данные не сохранены. Точно перейти?";
// };

