// Запускаем скрипт, если страничка была загружена с параметром user_id
$(function() {
    if ($('.user-item#' + userChatId).length) {
        $('.user-item#' + userChatId).trigger('click');
    } else {
        $('.chat-list').html('<div class="chat-info">This chat is not available to you!</div>');
    }
});