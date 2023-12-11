/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/profile/chat.js":
/*!**************************************!*\
  !*** ./resources/js/profile/chat.js ***!
  \**************************************/
/***/ (() => {

eval("// Увеличение блока для ввода сообщения\nfunction autoExpand(textarea) {\n  textarea.style.height = 'auto';\n  textarea.style.height = (textarea.scrollHeight > 124 ? 124 : textarea.scrollHeight) + 1 + 'px';\n}\n$(function () {\n  $('#text-to-send').on('input', function () {\n    autoExpand(this);\n  });\n\n  // Обработчки нажатия открытия чата с администратором\n  $('#admin-chat').change(function () {\n    if ($(this).is(':checked')) {\n      $('.admin-chat').css('display', 'block');\n      $('.check-newMsg').remove();\n    } else {\n      $('.admin-chat').css('display', 'none');\n    }\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJhdXRvRXhwYW5kIiwidGV4dGFyZWEiLCJzdHlsZSIsImhlaWdodCIsInNjcm9sbEhlaWdodCIsIiQiLCJvbiIsImNoYW5nZSIsImlzIiwiY3NzIiwicmVtb3ZlIl0sInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9wcm9maWxlL2NoYXQuanM/YWYzNiJdLCJzb3VyY2VzQ29udGVudCI6WyIvLyDQo9Cy0LXQu9C40YfQtdC90LjQtSDQsdC70L7QutCwINC00LvRjyDQstCy0L7QtNCwINGB0L7QvtCx0YnQtdC90LjRj1xyXG5mdW5jdGlvbiBhdXRvRXhwYW5kKHRleHRhcmVhKSB7XHJcblx0dGV4dGFyZWEuc3R5bGUuaGVpZ2h0ID0gJ2F1dG8nO1xyXG5cdHRleHRhcmVhLnN0eWxlLmhlaWdodCA9ICh0ZXh0YXJlYS5zY3JvbGxIZWlnaHQgPiAxMjQgPyAxMjQgOiB0ZXh0YXJlYS5zY3JvbGxIZWlnaHQpICsgMSArICdweCc7XHJcbn1cclxuXHJcblxyXG4kKGZ1bmN0aW9uKCkge1xyXG5cdCQoJyN0ZXh0LXRvLXNlbmQnKS5vbignaW5wdXQnLCBmdW5jdGlvbigpIHtcclxuXHRcdGF1dG9FeHBhbmQodGhpcyk7XHJcblx0fSk7XHJcblx0XHJcblx0Ly8g0J7QsdGA0LDQsdC+0YLRh9C60Lgg0L3QsNC20LDRgtC40Y8g0L7RgtC60YDRi9GC0LjRjyDRh9Cw0YLQsCDRgSDQsNC00LzQuNC90LjRgdGC0YDQsNGC0L7RgNC+0LxcclxuXHQkKCcjYWRtaW4tY2hhdCcpLmNoYW5nZShmdW5jdGlvbigpIHtcclxuXHRcdGlmICgkKHRoaXMpLmlzKCc6Y2hlY2tlZCcpKSB7XHJcblx0XHRcdCQoJy5hZG1pbi1jaGF0JykuY3NzKCdkaXNwbGF5JywgJ2Jsb2NrJyk7XHJcblx0XHRcdCQoJy5jaGVjay1uZXdNc2cnKS5yZW1vdmUoKTtcclxuXHJcblx0XHR9IGVsc2Uge1xyXG5cdFx0XHQkKCcuYWRtaW4tY2hhdCcpLmNzcygnZGlzcGxheScsICdub25lJyk7XHJcblx0XHR9XHJcblx0fSk7XHJcbn0pOyJdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQSxTQUFTQSxVQUFVQSxDQUFDQyxRQUFRLEVBQUU7RUFDN0JBLFFBQVEsQ0FBQ0MsS0FBSyxDQUFDQyxNQUFNLEdBQUcsTUFBTTtFQUM5QkYsUUFBUSxDQUFDQyxLQUFLLENBQUNDLE1BQU0sR0FBRyxDQUFDRixRQUFRLENBQUNHLFlBQVksR0FBRyxHQUFHLEdBQUcsR0FBRyxHQUFHSCxRQUFRLENBQUNHLFlBQVksSUFBSSxDQUFDLEdBQUcsSUFBSTtBQUMvRjtBQUdBQyxDQUFDLENBQUMsWUFBVztFQUNaQSxDQUFDLENBQUMsZUFBZSxDQUFDLENBQUNDLEVBQUUsQ0FBQyxPQUFPLEVBQUUsWUFBVztJQUN6Q04sVUFBVSxDQUFDLElBQUksQ0FBQztFQUNqQixDQUFDLENBQUM7O0VBRUY7RUFDQUssQ0FBQyxDQUFDLGFBQWEsQ0FBQyxDQUFDRSxNQUFNLENBQUMsWUFBVztJQUNsQyxJQUFJRixDQUFDLENBQUMsSUFBSSxDQUFDLENBQUNHLEVBQUUsQ0FBQyxVQUFVLENBQUMsRUFBRTtNQUMzQkgsQ0FBQyxDQUFDLGFBQWEsQ0FBQyxDQUFDSSxHQUFHLENBQUMsU0FBUyxFQUFFLE9BQU8sQ0FBQztNQUN4Q0osQ0FBQyxDQUFDLGVBQWUsQ0FBQyxDQUFDSyxNQUFNLENBQUMsQ0FBQztJQUU1QixDQUFDLE1BQU07TUFDTkwsQ0FBQyxDQUFDLGFBQWEsQ0FBQyxDQUFDSSxHQUFHLENBQUMsU0FBUyxFQUFFLE1BQU0sQ0FBQztJQUN4QztFQUNELENBQUMsQ0FBQztBQUNILENBQUMsQ0FBQyIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy9wcm9maWxlL2NoYXQuanMiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/profile/chat.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/profile/chat.js"]();
/******/ 	
/******/ })()
;