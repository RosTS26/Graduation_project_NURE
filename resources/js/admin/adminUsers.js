import axios from 'axios';

// Настройка csrf-токена
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

let userId = 0;

// Вывод пользователей на экран
function getUsers(data) {
	$('.users-list').html(''); // Очищаем юзер-лист

	if (data.length == 0) {
		$('.users-list').html('<li class="list-info">User(s) not found</li>');
		return 0;
	}

	data.forEach(item => {
		let user = $('<li class="user-item">'+ item['username'] +' (id: '+ item['user_id'] +')</li>');
        let userInfo = $('<a href="/admin/users/'+ item['user_id'] +'"></a>');

		// Формируем блок юзера
		userInfo.append(user);
		$('.users-list').append(userInfo);
	});

	return 0;
}

// Сообщение результата бана/блокировки пользователя
function getMsg(data) {
    let statusColor = ['#19c37d', '#E4AE0B', '#FF2121'];
    $('.res').css({'color': statusColor[data.status], 'display': 'block'}).html(data.msg);
}

$(function() {
    // Поиск юзеров по нику
	$('#btn-search').on('click', function() {
        if ($('#search-text').val().trim() !== '') {
            let search = $('#search-text').val();
            let url = '/admin/users/users-search';
    
            axios.post(url, { search: search })
                .then(function(response) {
                    getUsers(response.data);
                })
                .catch(function (error) {
                    alert("Error search users...\n" + error);
                });
        }
	});

    // Выбор блокировки аккаунта или блокировка возможности отправки сообщений
	$("#ban-acc").change(function() {
        $('.res').html('');
		if ($(this).is(':checked')) {
			$("#styles-ban-acc").css('background', 'linear-gradient(#49B681, #298533)');
			$("#styles-block-chat").css('background', '#454D55');
			$(".ban-or-block").html('Ban/unban a user');
			$('#checkUnban').prop('checked', false).trigger('change');
		}
	});

	$("#block-chat").change(function() {
        $('.res').html('');
		if ($(this).is(':checked')) {
			$("#styles-ban-acc").css('background', '#454D55');
			$("#styles-block-chat").css('background', 'linear-gradient(#49B681, #298533)');
			$(".ban-or-block").html('Block/unblock user chat');
			$('#checkUnban').prop('checked', false).trigger('change');
		}
	});

    // Обработчки нажатия на чекбокс блокировка/разблокировка
	// Меняем кнопки в зависимости от выбраной функции (бан аккаунта/блокировка чата)
	$('#checkUnban').change(function() {
        $('.res').html('');
        $('#cause').val('');
        $('#days').val('');

		if ($(this).is(':checked')) {
			$('#days').prop('disabled', true);
			$('#cause').prop('disabled', true);

			if ($('#ban-acc').is(':checked')) $('.changeBtn').val('Unban').attr('id', 'btnUnban');
			else if ($('#block-chat').is(':checked')) $('.changeBtn').val('Unblock').attr('id', 'btnUnblock');

		} else {
			$('#days').prop('disabled', false);
			$('#cause').prop('disabled', false);

			if ($('#ban-acc').is(':checked')) $('.changeBtn').val('Ban').attr('id', 'btnBan');
			else if ($('#block-chat').is(':checked')) $('.changeBtn').val('Block').attr('id', 'btnBlock');
		}
	});

    // *** User ban ***
	$(".user-ban").on("click", "#btnBan", function() {
        $('.res').html('');

		if ($('#days').val().length == 0 || Number($('#days').val()) < 1) {
            let data = {
                'status': 2,
                'msg': 'Number of days input error!'
            };
            getMsg(data);
		}
		else {
			if (confirm('Ban a user for '+$('#days').val()+' day/days?')) {
				let cause = $('#cause').val();
				let days = $('#days').val();

                let url = '/admin/users/'+ user_id +'/ban';
    
                axios.post(url, { days: days, cause: cause })
                    .then(function(response) {
                        getMsg(response.data);
                    })
                    .catch(function (error) {
                        alert("Error ban user...\n" + error);
                    });
			}
		} 
	});

	// *** User unban ***
	$(".user-ban").on("click", "#btnUnban", function() {
		$('.res').html('');

        if (confirm('Unban this user?')) {
            let url = '/admin/users/'+ user_id +'/unban';
    
            axios.post(url)
                .then(function(response) {
                    getMsg(response.data);
                })
                .catch(function (error) {
                    alert("Error ban user...\n" + error);
                });
        }
	});

	// *** User block chat *** 
	$('.user-ban').on('click', '#btnBlock', function() {
		$('.res').html('');

		if ($('#days').val().length == 0 || Number($('#days').val()) < 1) {
            let data = {
                'status': 2,
                'msg': 'Number of days input error!'
            };
            getMsg(data);
		}
		else {
			if (confirm('Block chat a user for '+$('#days').val()+' day/days?')) {
				let cause = $('#cause').val();
				let days = $('#days').val();

                let url = '/admin/users/'+ user_id +'/block-chat';
    
                axios.post(url, { days: days, cause: cause })
                    .then(function(response) {
                        getMsg(response.data);
                    })
                    .catch(function (error) {
                        alert("Error block chat user...\n" + error);
                    });
			}
		} 
	});

	// *** User unblock chat ***
	$('.user-ban').on('click', '#btnUnblock', function() {
		$('.res').html('');

        if (confirm('Unblock chat this user?')) {
            let url = '/admin/users/'+ user_id +'/unblock-chat';
    
            axios.post(url)
                .then(function(response) {
                    getMsg(response.data);
                })
                .catch(function (error) {
                    alert("Error ban user...\n" + error);
                });
        }
	});

    // Активация radio с функцией блокировки аккаунтов
	$('#ban-acc').prop('checked', true).trigger('change');
});