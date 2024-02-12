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

/***/ "./resources/js/messenger/firstLoad.js":
/*!*********************************************!*\
  !*** ./resources/js/messenger/firstLoad.js ***!
  \*********************************************/
/***/ (() => {

eval("// Запускаем скрипт, если страничка была загружена с параметром user_id\n$(function () {\n  new Promise(function (resolve, reject) {\n    var checkWS = function checkWS() {\n      return window.usersOnline ? resolve() : setTimeout(checkWS, 200);\n    };\n    checkWS();\n  }).then(function () {\n    if ($('.user-item#' + userChatId).length) {\n      $('.user-item#' + userChatId).trigger('click');\n    } else {\n      $('.chat-list').html('<div class=\"chat-info\">This chat is not available to you!</div>');\n    }\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyIkIiwiUHJvbWlzZSIsInJlc29sdmUiLCJyZWplY3QiLCJjaGVja1dTIiwid2luZG93IiwidXNlcnNPbmxpbmUiLCJzZXRUaW1lb3V0IiwidGhlbiIsInVzZXJDaGF0SWQiLCJsZW5ndGgiLCJ0cmlnZ2VyIiwiaHRtbCJdLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvbWVzc2VuZ2VyL2ZpcnN0TG9hZC5qcz8yYjg4Il0sInNvdXJjZXNDb250ZW50IjpbIi8vINCX0LDQv9GD0YHQutCw0LXQvCDRgdC60YDQuNC/0YIsINC10YHQu9C4INGB0YLRgNCw0L3QuNGH0LrQsCDQsdGL0LvQsCDQt9Cw0LPRgNGD0LbQtdC90LAg0YEg0L/QsNGA0LDQvNC10YLRgNC+0LwgdXNlcl9pZFxyXG4kKGZ1bmN0aW9uKCkge1xyXG4gICAgbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgIGNvbnN0IGNoZWNrV1MgPSAoKSA9PiB3aW5kb3cudXNlcnNPbmxpbmUgPyByZXNvbHZlKCkgOiBzZXRUaW1lb3V0KGNoZWNrV1MsIDIwMCk7XHJcbiAgICAgICAgY2hlY2tXUygpO1xyXG4gICAgfSlcclxuICAgIC50aGVuKCgpID0+IHtcclxuICAgICAgICBpZiAoJCgnLnVzZXItaXRlbSMnICsgdXNlckNoYXRJZCkubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgICQoJy51c2VyLWl0ZW0jJyArIHVzZXJDaGF0SWQpLnRyaWdnZXIoJ2NsaWNrJyk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgJCgnLmNoYXQtbGlzdCcpLmh0bWwoJzxkaXYgY2xhc3M9XCJjaGF0LWluZm9cIj5UaGlzIGNoYXQgaXMgbm90IGF2YWlsYWJsZSB0byB5b3UhPC9kaXY+Jyk7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0pOyJdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQUEsQ0FBQyxDQUFDLFlBQVc7RUFDVCxJQUFJQyxPQUFPLENBQUMsVUFBQ0MsT0FBTyxFQUFFQyxNQUFNLEVBQUs7SUFDN0IsSUFBTUMsT0FBTyxHQUFHLFNBQVZBLE9BQU9BLENBQUE7TUFBQSxPQUFTQyxNQUFNLENBQUNDLFdBQVcsR0FBR0osT0FBTyxDQUFDLENBQUMsR0FBR0ssVUFBVSxDQUFDSCxPQUFPLEVBQUUsR0FBRyxDQUFDO0lBQUE7SUFDL0VBLE9BQU8sQ0FBQyxDQUFDO0VBQ2IsQ0FBQyxDQUFDLENBQ0RJLElBQUksQ0FBQyxZQUFNO0lBQ1IsSUFBSVIsQ0FBQyxDQUFDLGFBQWEsR0FBR1MsVUFBVSxDQUFDLENBQUNDLE1BQU0sRUFBRTtNQUN0Q1YsQ0FBQyxDQUFDLGFBQWEsR0FBR1MsVUFBVSxDQUFDLENBQUNFLE9BQU8sQ0FBQyxPQUFPLENBQUM7SUFDbEQsQ0FBQyxNQUFNO01BQ0hYLENBQUMsQ0FBQyxZQUFZLENBQUMsQ0FBQ1ksSUFBSSxDQUFDLGlFQUFpRSxDQUFDO0lBQzNGO0VBQ0osQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL21lc3Nlbmdlci9maXJzdExvYWQuanMiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/messenger/firstLoad.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/messenger/firstLoad.js"]();
/******/ 	
/******/ })()
;