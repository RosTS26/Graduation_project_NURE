// Search users
import axios from 'axios';

// Настройка Axios для отправки заголовка X-Socket-ID
window.Echo.connector.pusher.connection.bind('connected', function () {
    axios.defaults.headers.common['X-Socket-ID'] = window.Echo.socketId();
});
// Настройка csrf-токена
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

// Вывод пользователей на экран
function getUsers(data) {
	console.log(data);
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
	$('#btn-search').on('click', function() {
		let search = $('#search-text').val();
		let url = '/admin/chats-search';

		axios.post(url, { search: search })
			.then(function(response) {
				getUsers(response.data);
			})
			.catch(function (error) {
				alert("Error search users...\n" + error);
			});
	});
});