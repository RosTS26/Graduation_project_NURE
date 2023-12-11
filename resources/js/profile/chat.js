// Увеличение блока для ввода сообщения
function autoExpand(textarea) {
	textarea.style.height = 'auto';
	textarea.style.height = (textarea.scrollHeight > 124 ? 124 : textarea.scrollHeight) + 1 + 'px';
}


$(function() {
	$('#text-to-send').on('input', function() {
		autoExpand(this);
	});
	
	// Обработчки нажатия открытия чата с администратором
	$('#admin-chat').change(function() {
		if ($(this).is(':checked')) {
			$('.admin-chat').css('display', 'block');
			$('.check-newMsg').remove();

		} else {
			$('.admin-chat').css('display', 'none');
		}
	});
});