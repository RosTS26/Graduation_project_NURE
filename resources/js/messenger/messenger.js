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

// =================================================================================

let userChatId = 0;
let myProfileId = myId;

// Адаптивное изменение высоты (для display: block)
let currentHeightTextarea = 29; // Текущая высота блока с чатом
function autoExpand(textarea) {
    textarea.style.height = '29px';

    if (textarea.scrollHeight <= 124) {
        textarea.style.height = textarea.scrollHeight + 'px'; // Меняем высоту

        let heightDif = textarea.clientHeight - currentHeightTextarea // Средняя высота увеличения
        
        // Меняем высоту блока с чатом
        if (heightDif !== 0) {
            let height = $('.chat-list').height(); // Высота чат-контейнера
            $('.chat-list').height(height - heightDif); // Меняем высоту блока с чатом
            currentHeightTextarea = textarea.clientHeight; // Устанавливаем значение текущей высоты
        } 
    } else {
        textarea.style.height = '124px';
    }
}

// Поиск юзеров на строное клиента
function searchChats() {
    $('.not-friends').remove();

    let checkNotFound = true;
    let searchText = $('.form-control').val().toLowerCase();

    if (searchText === '') {
        $('.user-item').each(function() {
            $(this).show();
            checkNotFound = false;
        });
    } else {
        // Проходимся по каждому элементу <li>
        $('.user-item').each(function() {
            // Получаем текущее имя пользователя (в нижнем регистре)
            let username = $(this).find('.name-id b').text().toLowerCase();
            
            // Проверяем, есть ли совпадения текста в имени 
            if (username.includes(searchText)) {
                $(this).show();
                checkNotFound = false;
            } 
            else $(this).hide();
        });
    }

    if (checkNotFound) $('.user-list').append('<p class="not-friends">User "'+ searchText +'" not found!</p>');
    
    return 0;
}

// Отрисовка сообщений 
function getMsgs(msgs) {
	// Выводим на экран сообщения в зависимости от отправителя
	msgs.forEach(item => {
		let msg = $('<div></div>');

		if (Number(item['id']) === userChatId) msg.addClass('message received-message');
		else msg.addClass('message sent-message');

		msg.html('<p class="message-text">' + item['msg'] + '</p>');
		msg.append('<span class="message-time">' + item['time'] + '</span>');
		$('.chat-list').append(msg);
	});

	$('.chat-list').scrollTop($('.chat-list').prop('scrollHeight'));
}

// Загрузка чата с другом
function loadChat(data) {
    // Обработка ошибок сервера
    if (!data) {
        $('.chat-list').html('<div class="chat-info">This chat is not available to you!</div>');
        $('#btnSendMsg').prop('disabled', true);
        return 0;
    }

	let chat = JSON.parse(data.chat);
	let newMsgs = JSON.parse(data.newMsgs);
    
	$('.chat-list').empty();

	// Если оба чата пусты, то выводим сообщение с просьбой ввести сообщение :)
	if (chat.length == 0 && newMsgs.length == 0) {
		$('.chat-list').html('<div class="chat-info">Send a message first!</div>');
		return 0;
	}
	
	chat.length == 0 ? true : getMsgs(chat); // Прорисовка основного чата

	if (newMsgs.length != 0) { 	// Прорисовка чата с новыми сообщениями
		$('.chat-list').append('<div class="newMsgInfo">New message</div>');
		getMsgs(newMsgs);
	}
	return 1;
}

// Отображение сообщений (отправленного или из WebSocket)
function getSocketMsg(data) {
    switch (data) {
        case 0:
            alert('Error send message. Chat not found!');
            break;
        case 1:
            alert('Error send message!');
            break;
        case 2:
            alert('Chat access is blocked!');
            break;
        default:
            // Очищаем chat-list, если присутсвует информационное сообщение
            if ($('.chat-info').length) $('.chat-list').empty();
        
            let msgElement = $('<div></div>');
        
            if (Number(data.id) === userChatId) msgElement.addClass('message received-message');
            else msgElement.addClass('message sent-message');
        
            $('#'+ userChatId +' .last-msg').html(data.msg);
        
            msgElement.html('<p class="message-text">' + data.msg + '</p>');
            msgElement.append('<span class="message-time">' + data.time + '</span>');
            $('.chat-list').append(msgElement);
            $('.chat-list').scrollTop($('.chat-list').prop('scrollHeight'));
    }
}

// ==========================================================================

$(function() {
    // Меняем высоту ввода текста
    $('#text-to-send').on('input', function() {
        autoExpand(this);
    });

    // Показать/убрать список юзеров
    $('.toggle-button').on('click', function() {
        $('.user-info').toggleClass('show-user-info');
    });
    // Закрываем панель, если клик был вне нее
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.user-info, .toggle-button').length) {
            $('.user-info').removeClass('show-user-info');
        }
    });

    // Поиск чатов
    //$('#btn-search').on('click', searchChats);
    $('.form-control').on('input', searchChats);

    // Выбор чата
    $('.user-item').on('click', function() {
        if (!$(this).hasClass('focus')) {
            userChatId = Number($(this).attr('id'));
            
            $('.user-info').removeClass('show-user-info'); // Закрываем список (если мобила)
            $('.user-item').removeClass('focus'); // Удаляем класс focus у всех
            $(this).addClass('focus'); // Добавляем класс с зеленым фоном
            $('.chat-list').html(''); // Чистим чат
            $('#btnSendMsg').prop('disabled', false); // Активируем кнопку
            $('.newMsg-info-' + userChatId).css('display', 'none').html(0);
            // $('.user-list').scrollTop(0); // Скролл вверх

            // Формируем окно с информацией о текущем пользователе
            let avatarBlock = $(this).find('.avatar-block').clone();
            let userData = $(this).find('.user-data').clone().children().first().remove();

            // Статус пользователя
            let onlineStatus = $('<div></div>').addClass('user-status').attr('id', userChatId);
            if (avatarBlock.find('.check-online').css('display') === 'block') {
                onlineStatus.html('online').css('color', '#198754');
            } else {
                onlineStatus.html('offline').css('color', '#C09000');
            };
            userData.append(onlineStatus);

            // Кнопка удаления чата
            let deleteChatBtn = $('<button>Delete chat</button>').addClass('btn btn-primary delete-chat'); 

            $('.user-info-panel').empty().append(avatarBlock, userData, deleteChatBtn);
    
            // Загрузка чата с игроками
            let url = 'messenger/load-chat';
        
            axios.post(url, {userChatId: userChatId})
                .then(function(res) {
                    loadChat(res.data); // Отрисовка чата
                    // test promise
                    // return new Promise(function(resolve, reject) {
                    //     setTimeout(() => resolve(123), 2000);
                    // });
                })
                //.then(res => alert(res))
                .catch(function (error) {
                    alert("Error load chat...\n" + error);
                });
        }

        // Удаление чата
        $('.delete-chat').on('click', function() {
            if (confirm('Are you sure you want to delete the chat with this user?')) {
                
                let url = 'messenger/delete-chat';
                axios.post(url, {userChatId: userChatId})
                    .then(function(res) {
                        if (res.data == 0) {
                            $('.chat-list').html('<div class="chat-info">Send a message first!</div>');
                        } else throw 'User not found!';
                    })
                    .catch(function (error) {
                        alert("Error delete chat...\n" + error);
                    });
            }
        })
    });

    // Отправка сообщения
    $('#btnSendMsg').on('click', function() {
        if ($('#text-to-send').val().trim() != '' && userChatId !== 0) {
			let sendMsg = $('#text-to-send').val();
            let url = 'messenger/sendMsg';

            axios.post(url, { userChatId: userChatId, message: sendMsg })
				.then(function(res) {
					 getSocketMsg(res.data);
				})
				.catch(function (error) {
					alert("Error send message...\n" + error);
				});

			$('#text-to-send').val('');
            $('#text-to-send').css('height', '29px');
		}
    });

    // Обработка socket сообщений
	window.Echo.private('Friendly-chat-' + myProfileId)
    .listen('.MessageSent', (res) => {
        // Если чат открыт, выводим сообщение, иначе оповещаем о новом сообщении
        if (res.user.id === userChatId) {
            getSocketMsg(res.message); // Выводим сообщение
			
			// Обновляем чаты на сервере
			let url = 'messenger/update-chat';

			axios.post(url, { userChatId: userChatId })
			.catch(function (error) {
				alert("Error database...\n" + error);
			});
        } else {
            let numNewMsgs = Number($('.newMsg-info-' + res.user.id).html()) + 1;
			$('.newMsg-info-' + res.user.id).css('display', 'block').html(numNewMsgs);
            $('#'+ res.user.id +' .last-msg').html(res.message.msg);
        }
    })
    .listen('.DeleteChat', (res) => {
        if (res.user.id === userChatId) {
            $('.chat-list').html('<div class="chat-info">Chat deleted by friend!</div>');
        }
    });
})