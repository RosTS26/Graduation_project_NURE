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

/***/ "./resources/js/games/seaBattle/timer-worker.js":
/*!******************************************************!*\
  !*** ./resources/js/games/seaBattle/timer-worker.js ***!
  \******************************************************/
/***/ (() => {

eval("// Данные для таймеров\nvar csrf;\nvar myTimer = 10 * 60 * 1000;\nvar hisTimer = 10 * 60 * 1000;\nvar preparationTimer = 60 * 1000;\nvar timer;\n\n// Подсчет времени\nfunction getTimer(fullTime) {\n  var minutes = Math.floor(fullTime / 60000);\n  var seconds = Math.floor(fullTime % 60000 / 1000); // Остаток миллисекунд переводим в секунды\n  seconds = seconds < 10 ? '0' + seconds : seconds;\n  return minutes + ':' + seconds;\n}\n\n// Таймер для подготовки\nfunction timerForPreparation() {\n  preparationTimer -= 1000;\n  if (preparationTimer < 0) {\n    clearInterval(timer);\n    postMessage(\"end-preparation\");\n    return 0;\n  }\n  postMessage(getTimer(preparationTimer));\n}\n\n// Мой таймер\nfunction myTimerForGame() {\n  myTimer -= 1000;\n  if (myTimer < 0) {\n    clearInterval(timer);\n    var url = '/game/sea-battle/game-over';\n    var data = {\n      check: 'qwerty'\n    };\n\n    // Отправка POST-запроса на сервер\n    fetch(url, {\n      method: 'POST',\n      headers: {\n        'Content-Type': 'application/json',\n        'X-CSRF-TOKEN': csrf\n      },\n      body: JSON.stringify(data)\n    }).then(function (response) {\n      return response.text();\n    }).then(function (result) {\n      result.trim() === '1' ? postMessage(\"end-my-game\") : console.log('Status: Error');\n      self.close();\n    })[\"catch\"](function (error) {\n      console.error('Error during fetch:', error);\n    });\n  } else {\n    postMessage(getTimer(myTimer));\n  }\n}\n\n// Таймер оппонента\nfunction hisTimerForGame() {\n  hisTimer -= 1000;\n  if (hisTimer < 0) {\n    clearInterval(timer);\n    postMessage(\"end-his-game\");\n    self.close();\n    return 0;\n  }\n  postMessage(getTimer(hisTimer));\n}\nonmessage = function onmessage(event) {\n  var command = event.data.command;\n  switch (command) {\n    case 'csrf':\n      csrf = event.data.csrf;\n      break;\n    case 'preparation-timer':\n      timer = setInterval(timerForPreparation, 1000);\n      break;\n    case 'game-my-timer':\n      timer = setInterval(myTimerForGame, 1000);\n      break;\n    case 'game-his-timer':\n      timer = setInterval(hisTimerForGame, 1000);\n      break;\n    case 'stop-timer':\n      clearInterval(timer);\n  }\n};//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJjc3JmIiwibXlUaW1lciIsImhpc1RpbWVyIiwicHJlcGFyYXRpb25UaW1lciIsInRpbWVyIiwiZ2V0VGltZXIiLCJmdWxsVGltZSIsIm1pbnV0ZXMiLCJNYXRoIiwiZmxvb3IiLCJzZWNvbmRzIiwidGltZXJGb3JQcmVwYXJhdGlvbiIsImNsZWFySW50ZXJ2YWwiLCJwb3N0TWVzc2FnZSIsIm15VGltZXJGb3JHYW1lIiwidXJsIiwiZGF0YSIsImNoZWNrIiwiZmV0Y2giLCJtZXRob2QiLCJoZWFkZXJzIiwiYm9keSIsIkpTT04iLCJzdHJpbmdpZnkiLCJ0aGVuIiwicmVzcG9uc2UiLCJ0ZXh0IiwicmVzdWx0IiwidHJpbSIsImNvbnNvbGUiLCJsb2ciLCJzZWxmIiwiY2xvc2UiLCJlcnJvciIsImhpc1RpbWVyRm9yR2FtZSIsIm9ubWVzc2FnZSIsImV2ZW50IiwiY29tbWFuZCIsInNldEludGVydmFsIl0sInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9nYW1lcy9zZWFCYXR0bGUvdGltZXItd29ya2VyLmpzPzUwN2YiXSwic291cmNlc0NvbnRlbnQiOlsiXHJcbi8vINCU0LDQvdC90YvQtSDQtNC70Y8g0YLQsNC50LzQtdGA0L7QslxyXG5sZXQgY3NyZjtcclxubGV0IG15VGltZXIgPSAxMCAqIDYwICogMTAwMDtcclxubGV0IGhpc1RpbWVyID0gMTAgKiA2MCAqIDEwMDA7XHJcbmxldCBwcmVwYXJhdGlvblRpbWVyID0gNjAgKiAxMDAwO1xyXG5sZXQgdGltZXI7XHJcblxyXG4vLyDQn9C+0LTRgdGH0LXRgiDQstGA0LXQvNC10L3QuFxyXG5mdW5jdGlvbiBnZXRUaW1lcihmdWxsVGltZSkge1xyXG5cdGxldCBtaW51dGVzID0gTWF0aC5mbG9vcihmdWxsVGltZSAvIDYwMDAwKTtcclxuICAgIGxldCBzZWNvbmRzID0gTWF0aC5mbG9vcigoZnVsbFRpbWUgJSA2MDAwMCkgLyAxMDAwKTsgLy8g0J7RgdGC0LDRgtC+0Log0LzQuNC70LvQuNGB0LXQutGD0L3QtCDQv9C10YDQtdCy0L7QtNC40Lwg0LIg0YHQtdC60YPQvdC00YtcclxuXHRzZWNvbmRzID0gc2Vjb25kcyA8IDEwID8gJzAnICsgc2Vjb25kcyA6IHNlY29uZHM7XHJcbiAgICBcclxuXHRyZXR1cm4gbWludXRlcyArICc6JyArIHNlY29uZHM7XHJcbn1cclxuXHJcbi8vINCi0LDQudC80LXRgCDQtNC70Y8g0L/QvtC00LPQvtGC0L7QstC60LhcclxuZnVuY3Rpb24gdGltZXJGb3JQcmVwYXJhdGlvbigpIHtcclxuICAgIHByZXBhcmF0aW9uVGltZXIgLT0gMTAwMDtcclxuXHRcclxuICAgIGlmIChwcmVwYXJhdGlvblRpbWVyIDwgMCkge1xyXG4gICAgICAgIGNsZWFySW50ZXJ2YWwodGltZXIpO1xyXG5cdFx0cG9zdE1lc3NhZ2UoXCJlbmQtcHJlcGFyYXRpb25cIik7XHJcbiAgICAgICAgcmV0dXJuIDA7XHJcbiAgICB9XHJcblxyXG4gICAgcG9zdE1lc3NhZ2UoZ2V0VGltZXIocHJlcGFyYXRpb25UaW1lcikpO1xyXG59XHJcblxyXG4vLyDQnNC+0Lkg0YLQsNC50LzQtdGAXHJcbmZ1bmN0aW9uIG15VGltZXJGb3JHYW1lKCkge1xyXG4gICAgbXlUaW1lciAtPSAxMDAwO1xyXG5cclxuXHRpZiAobXlUaW1lciA8IDApIHtcclxuICAgICAgICBjbGVhckludGVydmFsKHRpbWVyKTtcclxuICAgICAgICBcclxuICAgICAgICBjb25zdCB1cmwgPSAnL2dhbWUvc2VhLWJhdHRsZS9nYW1lLW92ZXInO1xyXG4gICAgICAgIGNvbnN0IGRhdGEgPSB7Y2hlY2s6ICdxd2VydHknfTtcclxuXHJcbiAgICAgICAgLy8g0J7RgtC/0YDQsNCy0LrQsCBQT1NULdC30LDQv9GA0L7RgdCwINC90LAg0YHQtdGA0LLQtdGAXHJcbiAgICAgICAgZmV0Y2godXJsLCB7XHJcbiAgICAgICAgICAgIG1ldGhvZDogJ1BPU1QnLFxyXG4gICAgICAgICAgICBoZWFkZXJzOiB7IFxyXG4gICAgICAgICAgICAgICAgJ0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi9qc29uJyxcclxuICAgICAgICAgICAgICAgICdYLUNTUkYtVE9LRU4nOiBjc3JmLFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBib2R5OiBKU09OLnN0cmluZ2lmeShkYXRhKSxcclxuICAgICAgICB9KVxyXG4gICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLnRleHQoKSlcclxuICAgICAgICAudGhlbihyZXN1bHQgPT4ge1xyXG4gICAgICAgICAgICByZXN1bHQudHJpbSgpID09PSAnMScgPyBwb3N0TWVzc2FnZShcImVuZC1teS1nYW1lXCIpIDpcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKCdTdGF0dXM6IEVycm9yJyk7XHJcblxyXG4gICAgICAgICAgICBzZWxmLmNsb3NlKCk7XHJcbiAgICAgICAgfSlcclxuICAgICAgICAuY2F0Y2goZXJyb3IgPT4ge1xyXG4gICAgICAgICAgICBjb25zb2xlLmVycm9yKCdFcnJvciBkdXJpbmcgZmV0Y2g6JywgZXJyb3IpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICBwb3N0TWVzc2FnZShnZXRUaW1lcihteVRpbWVyKSk7XHJcbiAgICB9XHJcbn1cclxuXHJcbi8vINCi0LDQudC80LXRgCDQvtC/0L/QvtC90LXQvdGC0LBcclxuZnVuY3Rpb24gaGlzVGltZXJGb3JHYW1lKCkge1xyXG4gICAgaGlzVGltZXIgLT0gMTAwMDtcclxuXHJcblx0aWYgKGhpc1RpbWVyIDwgMCkge1xyXG4gICAgICAgIGNsZWFySW50ZXJ2YWwodGltZXIpO1xyXG5cdFx0cG9zdE1lc3NhZ2UoXCJlbmQtaGlzLWdhbWVcIik7XHJcbiAgICAgICAgc2VsZi5jbG9zZSgpO1xyXG4gICAgICAgIHJldHVybiAwO1xyXG4gICAgfVxyXG5cclxuICAgIHBvc3RNZXNzYWdlKGdldFRpbWVyKGhpc1RpbWVyKSk7XHJcbn1cclxuXHJcbm9ubWVzc2FnZSA9IGZ1bmN0aW9uKGV2ZW50KSB7XHJcbiAgICBsZXQgY29tbWFuZCA9IGV2ZW50LmRhdGEuY29tbWFuZDtcclxuXHJcbiAgICBzd2l0Y2ggKGNvbW1hbmQpIHtcclxuICAgICAgICBjYXNlICdjc3JmJzpcclxuICAgICAgICAgICAgY3NyZiA9IGV2ZW50LmRhdGEuY3NyZjtcclxuICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgY2FzZSAncHJlcGFyYXRpb24tdGltZXInOlxyXG4gICAgICAgICAgICB0aW1lciA9IHNldEludGVydmFsKHRpbWVyRm9yUHJlcGFyYXRpb24sIDEwMDApO1xyXG4gICAgICAgICAgICBicmVhaztcclxuICAgICAgICBjYXNlICdnYW1lLW15LXRpbWVyJzpcclxuICAgICAgICAgICAgdGltZXIgPSBzZXRJbnRlcnZhbChteVRpbWVyRm9yR2FtZSwgMTAwMCk7XHJcbiAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgIGNhc2UgJ2dhbWUtaGlzLXRpbWVyJzpcclxuICAgICAgICAgICAgdGltZXIgPSBzZXRJbnRlcnZhbChoaXNUaW1lckZvckdhbWUsIDEwMDApO1xyXG4gICAgICAgICAgICBicmVhaztcclxuICAgICAgICBjYXNlICdzdG9wLXRpbWVyJzpcclxuICAgICAgICAgICAgY2xlYXJJbnRlcnZhbCh0aW1lcik7XHJcbiAgICB9XHJcbn07XHJcbiJdLCJtYXBwaW5ncyI6IkFBQ0E7QUFDQSxJQUFJQSxJQUFJO0FBQ1IsSUFBSUMsT0FBTyxHQUFHLEVBQUUsR0FBRyxFQUFFLEdBQUcsSUFBSTtBQUM1QixJQUFJQyxRQUFRLEdBQUcsRUFBRSxHQUFHLEVBQUUsR0FBRyxJQUFJO0FBQzdCLElBQUlDLGdCQUFnQixHQUFHLEVBQUUsR0FBRyxJQUFJO0FBQ2hDLElBQUlDLEtBQUs7O0FBRVQ7QUFDQSxTQUFTQyxRQUFRQSxDQUFDQyxRQUFRLEVBQUU7RUFDM0IsSUFBSUMsT0FBTyxHQUFHQyxJQUFJLENBQUNDLEtBQUssQ0FBQ0gsUUFBUSxHQUFHLEtBQUssQ0FBQztFQUN2QyxJQUFJSSxPQUFPLEdBQUdGLElBQUksQ0FBQ0MsS0FBSyxDQUFFSCxRQUFRLEdBQUcsS0FBSyxHQUFJLElBQUksQ0FBQyxDQUFDLENBQUM7RUFDeERJLE9BQU8sR0FBR0EsT0FBTyxHQUFHLEVBQUUsR0FBRyxHQUFHLEdBQUdBLE9BQU8sR0FBR0EsT0FBTztFQUVoRCxPQUFPSCxPQUFPLEdBQUcsR0FBRyxHQUFHRyxPQUFPO0FBQy9COztBQUVBO0FBQ0EsU0FBU0MsbUJBQW1CQSxDQUFBLEVBQUc7RUFDM0JSLGdCQUFnQixJQUFJLElBQUk7RUFFeEIsSUFBSUEsZ0JBQWdCLEdBQUcsQ0FBQyxFQUFFO0lBQ3RCUyxhQUFhLENBQUNSLEtBQUssQ0FBQztJQUMxQlMsV0FBVyxDQUFDLGlCQUFpQixDQUFDO0lBQ3hCLE9BQU8sQ0FBQztFQUNaO0VBRUFBLFdBQVcsQ0FBQ1IsUUFBUSxDQUFDRixnQkFBZ0IsQ0FBQyxDQUFDO0FBQzNDOztBQUVBO0FBQ0EsU0FBU1csY0FBY0EsQ0FBQSxFQUFHO0VBQ3RCYixPQUFPLElBQUksSUFBSTtFQUVsQixJQUFJQSxPQUFPLEdBQUcsQ0FBQyxFQUFFO0lBQ1ZXLGFBQWEsQ0FBQ1IsS0FBSyxDQUFDO0lBRXBCLElBQU1XLEdBQUcsR0FBRyw0QkFBNEI7SUFDeEMsSUFBTUMsSUFBSSxHQUFHO01BQUNDLEtBQUssRUFBRTtJQUFRLENBQUM7O0lBRTlCO0lBQ0FDLEtBQUssQ0FBQ0gsR0FBRyxFQUFFO01BQ1BJLE1BQU0sRUFBRSxNQUFNO01BQ2RDLE9BQU8sRUFBRTtRQUNMLGNBQWMsRUFBRSxrQkFBa0I7UUFDbEMsY0FBYyxFQUFFcEI7TUFDcEIsQ0FBQztNQUNEcUIsSUFBSSxFQUFFQyxJQUFJLENBQUNDLFNBQVMsQ0FBQ1AsSUFBSTtJQUM3QixDQUFDLENBQUMsQ0FDRFEsSUFBSSxDQUFDLFVBQUFDLFFBQVE7TUFBQSxPQUFJQSxRQUFRLENBQUNDLElBQUksQ0FBQyxDQUFDO0lBQUEsRUFBQyxDQUNqQ0YsSUFBSSxDQUFDLFVBQUFHLE1BQU0sRUFBSTtNQUNaQSxNQUFNLENBQUNDLElBQUksQ0FBQyxDQUFDLEtBQUssR0FBRyxHQUFHZixXQUFXLENBQUMsYUFBYSxDQUFDLEdBQzlDZ0IsT0FBTyxDQUFDQyxHQUFHLENBQUMsZUFBZSxDQUFDO01BRWhDQyxJQUFJLENBQUNDLEtBQUssQ0FBQyxDQUFDO0lBQ2hCLENBQUMsQ0FBQyxTQUNJLENBQUMsVUFBQUMsS0FBSyxFQUFJO01BQ1pKLE9BQU8sQ0FBQ0ksS0FBSyxDQUFDLHFCQUFxQixFQUFFQSxLQUFLLENBQUM7SUFDL0MsQ0FBQyxDQUFDO0VBQ04sQ0FBQyxNQUFNO0lBQ0hwQixXQUFXLENBQUNSLFFBQVEsQ0FBQ0osT0FBTyxDQUFDLENBQUM7RUFDbEM7QUFDSjs7QUFFQTtBQUNBLFNBQVNpQyxlQUFlQSxDQUFBLEVBQUc7RUFDdkJoQyxRQUFRLElBQUksSUFBSTtFQUVuQixJQUFJQSxRQUFRLEdBQUcsQ0FBQyxFQUFFO0lBQ1hVLGFBQWEsQ0FBQ1IsS0FBSyxDQUFDO0lBQzFCUyxXQUFXLENBQUMsY0FBYyxDQUFDO0lBQ3JCa0IsSUFBSSxDQUFDQyxLQUFLLENBQUMsQ0FBQztJQUNaLE9BQU8sQ0FBQztFQUNaO0VBRUFuQixXQUFXLENBQUNSLFFBQVEsQ0FBQ0gsUUFBUSxDQUFDLENBQUM7QUFDbkM7QUFFQWlDLFNBQVMsR0FBRyxTQUFBQSxVQUFTQyxLQUFLLEVBQUU7RUFDeEIsSUFBSUMsT0FBTyxHQUFHRCxLQUFLLENBQUNwQixJQUFJLENBQUNxQixPQUFPO0VBRWhDLFFBQVFBLE9BQU87SUFDWCxLQUFLLE1BQU07TUFDUHJDLElBQUksR0FBR29DLEtBQUssQ0FBQ3BCLElBQUksQ0FBQ2hCLElBQUk7TUFDdEI7SUFDSixLQUFLLG1CQUFtQjtNQUNwQkksS0FBSyxHQUFHa0MsV0FBVyxDQUFDM0IsbUJBQW1CLEVBQUUsSUFBSSxDQUFDO01BQzlDO0lBQ0osS0FBSyxlQUFlO01BQ2hCUCxLQUFLLEdBQUdrQyxXQUFXLENBQUN4QixjQUFjLEVBQUUsSUFBSSxDQUFDO01BQ3pDO0lBQ0osS0FBSyxnQkFBZ0I7TUFDakJWLEtBQUssR0FBR2tDLFdBQVcsQ0FBQ0osZUFBZSxFQUFFLElBQUksQ0FBQztNQUMxQztJQUNKLEtBQUssWUFBWTtNQUNidEIsYUFBYSxDQUFDUixLQUFLLENBQUM7RUFDNUI7QUFDSixDQUFDIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL2dhbWVzL3NlYUJhdHRsZS90aW1lci13b3JrZXIuanMiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/games/seaBattle/timer-worker.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/games/seaBattle/timer-worker.js"]();
/******/ 	
/******/ })()
;