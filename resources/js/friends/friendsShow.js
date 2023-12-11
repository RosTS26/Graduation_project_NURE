import axios from 'axios';

// Настройка csrf-токена
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

var user_id = Number($('#user-id').html());

// Удаление из друзей или отмена заявки
function cancelOrDelete(status) {
    let statusList = [
        $('<div>Error operation!</div>').css('color', '#FF2121'),
        $('<div>No friend request!</div>').css('color', '#E4AE0B'),
        $('<div>This user is not a friend!</div>').css('color', '#E4AE0B'),
        $('<div>Friend request rejected!</div>').css('color', '#E4AE0B'),
        $('<div>The user has been removed from friends!</div>').css('color', '#E4AE0B'),
    ];

    $('.msg-info').html(statusList[status]);

    if (status === 4 || status === 3) {
        $('.button-block').html('<button class="btn btn-outline-primary me-2 add-friend" data-user-id="'+ user_id +'">Add as friend</button>');
    } else {
        $('.button-block').html('');
    }
}

function addFriend(status) {
    let statusList = [
        $('<div>Friend request sent!</div>').css('color', '#19c37d'),
        $('<div>Friend request accepted!</div>').css('color', '#19c37d'),
        $('<div>This user is already your friend!</div>').css('color', '#E4AE0B'),
        $('<div>Friend request pending!</div>').css('color', '#E4AE0B'),
        $('<div>Error when sending friend request!</div>').css('color', '#FF2121'),
    ];

    $('.msg-info').html(statusList[status]);

    if (status === 0) {
        $('.button-block').html('<button class="btn btn-outline-primary me-2 cancel-app" data-user-id="'+ user_id +'">Cancel the application</button>');
    } 
    else if (status === 1) {
        let btn1 = $('<button class="btn btn-outline-primary me-2 send-msg" data-user-id="{{ $user->id }}">Send a message</button>');
        let btn2 = $('<button class="btn btn-outline-warning delete-friend" data-user-id="{{ $user->id }}">Delete friend</button>');
        $('.button-block').html('').append(btn1, btn2);
    }
}

$(function() {
    // Обработчик добавление в друзья
    $('.button-block').on('click', '.add-friend', function() {
        let url = '/friends/add-friend';
        let user_id = $(this).data('user-id');

        axios.post(url, { user_id: user_id })
            .then(function(response) {
                addFriend(response.data);
            })
            .catch(function (error) {
                alert("Error add friend...\n" + error);
            });
    });

    // Обработчик отмены своей заявки (cancel-app)
    $('.button-block').on('click', '.cancel-app', function() {
        let url = '/friends/cancel-app';
        let user_id = $(this).data('user-id');
        let listItem = $(this).closest('li');

        axios.post(url, { user_id: user_id })
            .then(function(response) {
                cancelOrDelete(response.data);
            })
            .catch(function (error) {
                alert("Error cancel application...\n" + error);
            });
    });

    // Обработчик отмены заявки в друзья (reject-app)
    $('.button-block').on('click', '.reject-app', function() {
        let url = '/friends/reject-app';
        let user_id = $(this).data('user-id');
        let listItem = $(this).closest('li');

        axios.post(url, { user_id: user_id })
            .then(function(response) {
                cancelOrDelete(response.data);
            })
            .catch(function (error) {
                alert("Error reject application...\n" + error);
            });
    });

    // Обработчик удаление из друзей (delete-friend)
    $('.button-block').on('click', '.delete-friend', function() {
        if (confirm('Are you sure you want to delete this friend?')) {
            let url = '/friends/delete-friend';
    
            axios.post(url, { user_id: user_id })
                .then(function(response) {
                    cancelOrDelete(response.data);
                })
                .catch(function (error) {
                    alert("Error delete friend...\n" + error);
                });
        } 
    });
});