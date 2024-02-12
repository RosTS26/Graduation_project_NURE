const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/onlineChecker.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.copyDirectory('resources/images', 'public/images');

// Ресурсы админ-панели
mix.js('resources/js/admin/adminChats.js', 'public/js/admin')
    .js('resources/js/admin/adminUsers.js', 'public/js/admin')
    .js('resources/js/admin/searchUsers.js', 'public/js/admin')
    .css('resources/css/admin/adminChats.css', 'public/css/admin')
    .css('resources/css/admin/adminUsers.css', 'public/css/admin');

// Ресурсы игр
mix.js('resources/js/games/snake/snake.js', 'public/js/games/snake')
    .css('resources/css/games/snake/snake.css', 'public/css/games/snake');

mix.js('resources/js/games/tetris/tetrisScriptJQ.js', 'public/js/games/tetris')
    .js('resources/js/games/tetris/tetrisScript.js', 'public/js/games/tetris')
    .css('resources/css/games/tetris/tetrisCSS.css', 'public/css/games/tetris');

mix.js('resources/js/games/roulette/rouletteScript.js', 'public/js/games/roulette')
    .css('resources/css/games/roulette/rouletteCSS.css', 'public/css/games/roulette');

mix.js('resources/js/games/seaBattle/connection.js', 'public/js/games/seaBattle')
    .js('resources/js/games/seaBattle/timer-worker.js', 'public/js/games/seaBattle')
    .js('resources/js/games/seaBattle/seaBattle.js', 'public/js/games/seaBattle')
    .css('resources/css/games/seaBattle/seaBattleCSS.css', 'public/css/games/seaBattle')

// Ресурсы профиля
mix.js('resources/js/profile/profile.js', 'public/js/profile')
    .js('resources/js/profile/chat.js', 'public/js/profile')
    .css('resources/css/profile/profile.css', 'public/css/profile');

// Ресурсы панели друзей
mix.js('resources/js/friends/friends.js', 'public/js/friends')
    .js('resources/js/friends/friendsShow.js', 'public/js/friends')
    .css('resources/css/friends/friends.css', 'public/css/friends');

// Ресурсы мессенджера
mix.js('resources/js/messenger/messenger.js', 'public/js/messenger')
    .js('resources/js/messenger/firstLoad.js', 'public/js/messenger')
    .css('resources/css/messenger/messenger.css', 'public/css/messenger');
