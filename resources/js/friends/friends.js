import axios from 'axios';

// Настройка csrf-токена
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

let usersOnline;

// Вывод пользователей на экран
function getUsersList(data) {

    // Функция группировки информации о пользователях
    function listGroup(users) {
        $('.friends-list').html(''); // Очищаем поле с пользователями

        // Проходимся по каждому пользователю
        users.forEach(item => {
            // Формируем лист пользователя
            let status;
            let list = $('<li></li>').addClass('user-item').attr('id', item.id);
            let avatar = $('<div></div>').addClass('avatar-block').html('<a href="/friends/'+ item.id +'"><img class="avatar" src="'+ item.avatar +'"></a>');
            let userInfo = $('<div></div>').addClass('user-info').html('<p><b>'+ item.name +'</b> (id: '+ item.id +')</p>');
            let buttonBlock = $('<div></div>').addClass('button-block-' + item.id);

            // Уникальные ссылки для каждого списка
            if ($('#option1').is(':checked')) {
                buttonBlock.append('<a href="/messenger?id='+ item.id +'" class="btn-user-info send-msg" data-user-id="'+ item.id +'">Send a message</a>');
                buttonBlock.append('<button class="btn-user-info delete-friend" data-user-id="'+ item.id +'">Delete friend</button>');

                // Статус онлайн (только для друзей)
                if (window.usersOnline.some(obj => obj.id === item.id)) {
                    status = '<span id="'+item.id+'" class="check-online position-absolute translate-middle badge rounded-pill"> </span>';
                } else {
                    status = '<span style="display: none;" id="'+item.id+'" class="check-online position-absolute translate-middle badge rounded-pill"> </span>';
                }
            } else if ($('#option2').is(':checked')) {
                buttonBlock.append('<button class="btn-user-info cancel-app" data-user-id="'+ item.id +'">Cancel the application</button>');
            } else if ($('#option3').is(':checked')) {
                buttonBlock.append('<button class="btn-user-info add-friend accept" data-user-id="'+ item.id +'">Accept</button>');
                buttonBlock.append('<button class="btn-user-info reject-app" data-user-id="'+ item.id +'">Reject</button>');
            }

            userInfo.append(buttonBlock);
            list.append(avatar.append(status)).append(userInfo);

            // Добавляем получившийся лист к общему списку
            $('.friends-list').append(list).append('<hr>');
        });
    }

    if ($('#option1').is(':checked') && data.length === 0) {
        $('.friends-list').html('<p class="not-friends">You have no friends :(<br>Send a friend request in the <b>"sent app"</b> tab</p>');
        return 0;
    }
    else if ($('#option2').is(':checked') && data.length === 0) {
        $('.friends-list').html('<p class="not-friends">You have no friend requests sent!</p>');
        return 0;
    }
    else if ($('#option3').is(':checked') && data.length === 0) {
        $('.friends-list').html('<p class="not-friends">No incoming friend requests!</p>');
        return 0;
    }

    listGroup(data);
}

// Выводим найденых пользователей
function getFindFriends(users) {
    if (users.length === 0) {
        $('.friends-list').html('<p class="not-friends">There are no users with this name!</p>');
    } else {
        $('.friends-list').html('');

        users.forEach(item => {
            // Формируем лист пользователя
            let list = $('<li></li>').addClass('user-item').attr('id', item.id);
            let avatar = $('<div></div>').addClass('avatar-block').html('<a href="/friends/'+ item.id +'"><img class="avatar" src="'+ item.avatar +'"></a>');
            let userInfo = $('<div></div>').addClass('user-info').html('<p><b>'+ item.name +'</b> (id: '+ item.id +')</p>');
            let buttonBlock = $('<div></div>').addClass('button-block-' + item.id);

            // Устанавливаем статус текущего пользователя
            let status = [
                '<button class="btn-user-info add-friend" data-user-id="'+ item.id +'">Add as friend</button>',
                '<a href="/messenger?id='+ item.id +'" class="btn-user-info send-msg" data-user-id="'+ item.id +'">Send a message</a>',
                '<button class="btn-user-info cancel-app" data-user-id="'+ item.id +'">Cancel the application</button>'
            ]
            userInfo.append(buttonBlock.append(status[item.status]));

            list.append(avatar).append(userInfo);

            // Добавляем получившийся лист к общему списку
            $('.friends-list').append(list).append('<hr>');
        });
    }
}

// Поиск юзеров на строное клиента
function searchFriends() {
    $('.not-friends').remove();

    let checkNotFound = true;
    let searchText = $('.form-control').val().toLowerCase();

    if (searchText == '') {
        $('.user-item').each(function() {
            $(this).show();
            $(this).next('hr').show();
            checkNotFound = false;
        });
    } else {
        // Проходимся по каждому элементу <li>
        $('.user-item').each(function() {
            // Получаем текущее имя пользователя (в нижнем регистре)
            let username = $(this).find('.user-info b').text().toLowerCase();
            if (username.includes(searchText)) {
                $(this).show();
                $(this).next('hr').show();
                checkNotFound = false;
            } else {
                $(this).hide();
                $(this).next('hr').hide();
            }
        });
    }

    if (checkNotFound) $('.friends-list').append('<p class="not-friends">Not found!</p>');
    
    return 0;
}

// === Add friend ===
function addFriend (status, user_id) {
    let statusList = [
        $('<div>Friend request sent!</div>').css('color', '#19c37d'),
        $('<div>Friend request accepted!</div>').css('color', '#19c37d'),
        $('<div>This user is already your friend!</div>').css('color', '#E4AE0B'),
        $('<div>Friend request pending!</div>').css('color', '#E4AE0B'),
        $('<div>Error when sending friend request!</div>').css('color', '#FF2121'),
    ];

    $('.button-block-' + user_id).html(statusList[status]);
}

// === Cancel (reject) or delete app ===
function cancelOrDelete (status, user_id, listItem) {
    // Очищаем информацию о пользователе со страницы
    if (status === 4 || status === 3) {
        listItem.next('hr').addBack().remove();
    } else {
        let statusList = [
            $('<div>Error operation!</div>').css('color', '#FF2121'),
            $('<div>No friend request!</div>').css('color', '#E4AE0B'),
            $('<div>This user is not your friend!</div>').css('color', '#E4AE0B'),
        ];
    
        $('.button-block-' + user_id).html(statusList[status]);
    }
}

// Уменьшаем оповещение о кол-ве заявок в друзья 
function numIncomApp() {
    let num = Number($('.numIncom').html());
    num--;

    $('.numIncom').html(num);
    if (num <= 0) $('.numIncom').css('display', 'none');
}

// =======================================================================

$(function() {
    // Обработчки radio-button
    $('input[type="radio"]').change(function() {

        // Поиск новых друзей
        if ($('#option4').is(':checked')) {
            $('.form-control').attr('placeholder', 'Find new friends');
            $('.friends-list').html('<p class="not-friends">To find a friend, enter his name and click the search button</p>');

            $('.search-btn').off('click').on('click', function() {
                if ($('.form-control').val().trim() !== '') {
                    let url = '/friends/find';
                    let username = $('.form-control').val();

                    axios.post(url, { username: username })
                        .then(function(response) {
                            getFindFriends(response.data);
                        })
                        .catch(function (error) {
                            alert("Error find friends...\n" + error);
                        });
                }
            });

            return 0;
        }

        $('.form-control').attr('placeholder', 'Search for friends');
        
        let url = '/friends';
        let option = 'friends';

        // === My friends ===
        if ($('#option1').is(':checked')) option = 'friends';
        // === Sent app ===
        else if ($('#option2').is(':checked')) option = 'sentApp';
        // === Incom app ===
        else if ($('#option3').is(':checked')) option = 'incomApp';
        
        axios.post(url, { option: option })
            .then(function(response) {
                getUsersList(response.data);
            })
            .catch(function (error) {
                alert("Error option...\n" + error);
            });

        $('.search-btn').off('click').on('click', searchFriends);
    });

    // Поиск по текущим друзьям/заявкам
    $('.search-btn').off('click').on('click', searchFriends);

    // Обработчик добавление в друзья
    $('.friends-list').on('click', '.add-friend', function() {
        let url = '/friends/add-friend';
        let user_id = $(this).data('user-id');

        axios.post(url, { user_id: user_id })
            .then(function(response) {
                addFriend(response.data, user_id);
            })
            .catch(function (error) {
                alert("Error add friend...\n" + error);
            });
    });

    $('.friends-list').on('click', '.accept', numIncomApp);

    // Обработчик отмены своей заявки (cancel-app)
    $('.friends-list').on('click', '.cancel-app', function() {
        let url = '/friends/cancel-app';
        let user_id = $(this).data('user-id');
        let listItem = $(this).closest('li');

        axios.post(url, { user_id: user_id })
            .then(function(response) {
                cancelOrDelete(response.data, user_id, listItem);
            })
            .catch(function (error) {
                alert("Error cancel application...\n" + error);
            });
    });

    // Обработчик отмены заявки в друзья (reject-app)
    $('.friends-list').on('click', '.reject-app', function() {
        let url = '/friends/reject-app';
        let user_id = $(this).data('user-id');
        let listItem = $(this).closest('li');

        axios.post(url, { user_id: user_id })
            .then(function(response) {
                cancelOrDelete(response.data, user_id, listItem);
                numIncomApp();
            })
            .catch(function (error) {
                alert("Error reject application...\n" + error);
            });
    });

    // Обработчик удаление из друзей (delete-friend)
    $('.friends-list').on('click', '.delete-friend', function() {
        if (confirm('Are you sure you want to delete this friend?')) {
            let url = '/friends/delete-friend';
            let user_id = $(this).data('user-id');
            let listItem = $(this).closest('li');
    
            axios.post(url, { user_id: user_id })
                .then(function(response) {
                    cancelOrDelete(response.data, user_id, listItem);
                })
                .catch(function (error) {
                    alert("Error delete friend...\n" + error);
                });
        } 
    });
});