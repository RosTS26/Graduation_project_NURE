import axios from 'axios';
// import Echo from "laravel-echo";
// import Pusher from "pusher-js";

// window.Pusher = Pusher;

// window.Echo = new Echo({
// 	broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true,
// });

// // Настройка Axios для отправки заголовка X-Socket-ID
// window.Echo.connector.pusher.connection.bind('connected', function () {
//     axios.defaults.headers.common['X-Socket-ID'] = window.Echo.socketId();
// });

// Настройка csrf-токена
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

// Увеличение блока для ввода сообщения
function autoExpand(textarea) {
	textarea.style.height = 'auto';
	textarea.style.height = (textarea.scrollHeight > 124 ? 124 : textarea.scrollHeight) + 1 + 'px';
}

$('#text-to-send').on('input', function() {
	autoExpand(this);
});

// Отрисовка сообщений 
function getMsgs(msgs) {
	// Выводим на экран сообщения в зависимости от отправителя
	msgs.forEach(item => {
		let msg = $('<div></div>');

		if (Number(item['id']) == 1) msg.addClass('message received-message');
		else msg.addClass('message sent-message');

		msg.html('<p class="message-text">' + item['msg'] + '</p>');
		msg.append('<span class="message-time">' + item['time'] + '</span>');
		$('.chat-container').append(msg);
	});

	$('.chat-container').scrollTop($('.chat-container').prop('scrollHeight'));
}

function loadChat(data) {
	// Обработка ошибок сервера
	if (data == 'error') {
		$('.chat-container').html('<div class="chat-info">Enter a message to start!</div>');
		return 0;
	}

	let chat = JSON.parse(data.chat);
	let newMsgs = JSON.parse(data.newMsgs);
	$('.chat-container').empty();

	// Если оба чата пусты, то выводим сообщение с просьбой ввести сообщение :)
	if (chat.length == 0 && newMsgs.length == 0) {
		$('.chat-container').html('<div class="chat-info">Enter a message to start!</div>');
		return 0;
	}
	
	chat.length == 0 ? true : getMsgs(chat); // Прорисовка основного чата
	if (newMsgs.length != 0) { 				// Прорисовка чата с новыми сообщениями
		// Если последнее сообщение от другого, выводим надпись о новых сообщениях
		if (Number(newMsgs.slice(-1)[0]['id'] == 1)) {
			$('.chat-container').append('<div class="newMsgInfo">New message</div>');
		}
		getMsgs(newMsgs);
	}
	return 1;
}

// Отображение сообщений из WebSocket
function loadWebSocketChat(data) {
	// Очищаем chat-info, если он есть
	if ($('.chat-info').val() === '') $('.chat-container').empty();

	let msgElement = $('<div></div>');

	if (Number(data.id) == 1) msgElement.addClass('message received-message');
	else msgElement.addClass('message sent-message');

	msgElement.html('<p class="message-text">' + data.msg + '</p>');
	msgElement.append('<span class="message-time">' + data.time + '</span>');
	$('.chat-container').append(msgElement);
	$('.chat-container').scrollTop($('.chat-container').prop('scrollHeight'));
}



// === Чат с администратором ===
$(function() {
	// Обработчки нажатия открытия чата с администратором
	$('#admin-chat').change(function() {
		if ($(this).is(':checked')) {
			numNewMsgs = 0;
			// Загружаем чат с администратором
			let url = 'profile/load-chat';
	
			axios.post(url)
				.then(function(chat) {
					loadChat(chat.data);
				})
				.catch(function (error) {
					alert("Error load chat...\n" + error);
				});
	
			$('.admin-chat').css('display', 'block');
			$('.check-newMsg').css('display', 'none');
		} else {
			$('.chat-container').empty();
			$('.admin-chat').css('display', 'none');
		}
	});
	
	// Отправка сообщения
	$('#btnSendMsg').on('click', function() {

		if ($('#text-to-send').val() != '') {
			let sendMsg = $('#text-to-send').val();
			let url = 'profile/sendMsg';

			axios.post(url, { message: sendMsg })
				.then(function(response) {
					loadWebSocketChat(response.data);
				})
				.catch(function (error) {
					alert("Error send message...\n" + error);
				});

			$('#text-to-send').val('');
		}
	});

	// Обработка socket входных сообщений
	window.Echo.private('User-chat-' + userId).listen('.MessageSent', (res) => {
		//Если чат открыт, показываем сообщения и обновляем чаты
		if ($('#admin-chat').is(':checked')) {
			if ($('.chat-info').val() === '') $('.chat-container').empty();

			loadWebSocketChat(res.message); // Выводим сообщение
			
			// Обновляем чаты на сервере
			let url = 'profile/updateChat';

			axios.post(url)
			.then(function(response) {})
			.catch(function (error) {
				alert("Error database...\n" + error);
			});
		} 
		// Если чат закрыт, выводим уведомление про новое сообщение
		else {
			numNewMsgs++;
			$('.check-newMsg').css('display', 'block').html(numNewMsgs);
		}
	});
});