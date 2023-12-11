import axios from 'axios';
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
window.Echo.connector.pusher.connection.bind('connected', function () {
    axios.defaults.headers.common['X-Socket-ID'] = window.Echo.socketId();
});

// Настройка csrf-токена
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

let userChatId = 0; // Выбранный чат

// Увеличение блока для ввода сообщения
let currentHeightTextarea = 28; // Текущая высота блока с чатом

function autoExpand(textarea) {
    textarea.style.height = '28px';

    if (textarea.scrollHeight <= 108) {
        textarea.style.height = textarea.scrollHeight + 'px'; // Меняем высоту

        let height = $('.chat-container').height(); // Высота чат-контейнера
        let heightDif = textarea.clientHeight - currentHeightTextarea // Средняя высота увеличения

        // Меняем высоту блока с чатом
        if (heightDif != 0) {
            $('.chat-container').height(height - heightDif); // Меняем высоту блока с чатом
            currentHeightTextarea = textarea.clientHeight;
        } 
    } else {
        textarea.style.height = '124px';
    }
}

// Отрисовка сообщений 
function getMsgs(msgs) {
	// Выводим на экран сообщения в зависимости от отправителя
	msgs.forEach(item => {
		let msg = $('<div></div>');

		if (Number(item['id']) != 1) msg.addClass('message received-message');
		else msg.addClass('message sent-message');

		msg.html('<p class="message-text">' + item['msg'] + '</p>');
		msg.append('<span class="message-time">' + item['time'] + '</span>');
		$('.chat-container').append(msg);
	});

	$('.chat-container').scrollTop($('.chat-container').prop('scrollHeight'));
}

// Загрузка чата
function loadChat(data) {
	// Обработка ошибок сервера
	if (data == 'error') {
		$('.chat-container').html('<div class="chat-info">Chat is not loaded!</div>');
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
		if (Number(newMsgs.slice(-1)[0]['id'] != 1)) {
			$('.chat-container').append('<div class="newMsgInfo">New message</div>');
		}
		getMsgs(newMsgs);
	}
	return 1;
}

// Отображение сообщений из WebSocket
function loadWSMessage(data) {
    if (data == 'error') {
		alert('Error send message!');
		return 0;
	}
	// Очищаем chat-info, если он есть
	//if ($('.chat-info').val() === '') $('.chat-container').empty();

	let msgElement = $('<div></div>');

	if (Number(data.id) != 1) msgElement.addClass('message received-message');
	else msgElement.addClass('message sent-message');

	msgElement.html('<p class="message-text">' + data.msg + '</p>');
	msgElement.append('<span class="message-time">' + data.time + '</span>');
	$('.chat-container').append(msgElement);
	$('.chat-container').scrollTop($('.chat-container').prop('scrollHeight'));
}

// Отображение нового чата
function createNewChatWS(data) {
	let user = $('<li class="user-item" id="'+ data.id +'"></li>');
	let infoNewMsgs = $('<span class="check-newMsg user-'+ data.id +' fs-6 position-absolute translate-middle badge rounded-pill bg-danger">1</span>');
	let userInfo = $('<div class='+ data.id +'>'+ data.name +' (id: '+ data.id +')</div>');
	
	// Формируем новый чат
	user.append(infoNewMsgs).append(userInfo);
	$('.users-list').prepend(user);
}

// Вывод пользователей на экран
function getUsers(data) {
	$('.users-list').html(''); // Очищаем юзер-лист

	if (data.length == 0) {
		$('.users-list').html('<li>Not found</li>');
		return 0;
	}

	data.forEach(item => {
		let numNewMsgs = Number(item['numNewMsgs']); // Кол-во новых сообщений
		let user = $('<li class="user-item" id="'+ item['user_id'] +'"></li>');
		let infoNewMsgs = $('<span class="check-newMsg user-'+ item['user_id'] +' fs-6 position-absolute translate-middle badge rounded-pill bg-danger"></span>');
		let userInfo = $('<div class='+ item['user_id'] +'>'+ item['username'] +' (id: '+ item['user_id'] +')</div>');
		
		if (numNewMsgs > 0) infoNewMsgs.html(numNewMsgs);
		else if(numNewMsgs > 99) infoNewMsgs.html('99+');
		else infoNewMsgs.html(0).css('display', 'none');

		// Формируем блок с чатом 
		user.append(infoNewMsgs).append(userInfo);
		$('.users-list').append(user);
	});

	return 0;
}

$(function() {
	$('#text-to-send').on('input', function() {
		autoExpand(this);
	});
	
	// Поиск чатов по нику игрока
	$('#btn-search').on('click', function() {
		let search = $('#search-text').val();
		let url = '/admin/chats/chats-search';

		axios.post(url, { search: search })
			.then(function(response) {
				getUsers(response.data);
			})
			.catch(function (error) {
				alert("Error search users...\n" + error);
			});
	});

    // Панель для выбора чата с игроком
	$('.users-list').on('click', '.user-item', function() {
		// Получаем id открытого чата
		userChatId = Number($(this).attr('id'));

		$(this).prependTo('.users-list'); // Помещаем вверх списка
		$('.users-info').scrollTop(0); // Скроллим вверх
		$('.user-item').css('background-color', '#454D55'); // Красим все кнопки в серый
		$(this).css('background-color', '#19C37D'); // Красим в салатовый цвет выбранный чат
		$('.chat-container').html(''); // Очищаем чат-контейнер
		$('#btnSendMsg').prop('disabled', false); // Активируем кнопку "отправить"
		$('#text-to-send').prop('disabled', false); // Активируем текстовое поле
		$('.user-' + userChatId).css('display', 'none').html(0); // Очищаем уведомления о новых сообщениях
		
		// Загружаем основной чат с игроком
        let url = 'chats/load-chat';
	
        axios.post(url, {user_id: userChatId})
            .then(function(chat) {
                loadChat(chat.data);
            })
            .catch(function (error) {
                alert("Error load chat...\n" + error);
            });
	});

	// Отправка сообщения
	$('#btnSendMsg').on('click', function() {
		if ($('#text-to-send').val().trim() != '' && userChatId != 0) {
			let sendMsg = $('#text-to-send').val();
            let url = 'chats/sendMsg';

            axios.post(url, { user_id: userChatId, message: sendMsg })
				.then(function(response) {
					loadWSMessage(response.data);
				})
				.catch(function (error) {
					alert("Error send message...\n" + error);
				});

			$('#text-to-send').val(''); 
		}
	});

    // Обработка socket входных сообщений от пользователей
	window.Echo.channel('AdminChat')
	// Новое сообщение
	.listen('.MessageSent', (res) => {
		//Если чат с пользователем открыт, показываем сообщения и обновляем чаты
		if (res.user.id === userChatId) {

			loadWSMessage(res.message); // Выводим сообщение
			
			// Обновляем чаты на сервере
			let url = 'chats/updateChat';

			axios.post(url, { user_id: userChatId })
			.then(function(response) {})
			.catch(function (error) {
				alert("Error database...\n" + error);
			});
		} 
		// Если чат закрыт, выводим уведомление про новое сообщение
		else {
            let numNewMsgs = Number($('.user-' + res.user.id).html()) + 1;
			$('.user-' + res.user.id).css('display', 'block').html(numNewMsgs);
		}
	})
	// Новый чат
	.listen('.NewChat', (res) => {
		createNewChatWS(res.user);
	});
});