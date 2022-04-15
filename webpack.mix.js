const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
.js('resources/js/quiz.js', 'public/js/quiz.js')
.js('resources/js/leaderboard.js', 'public/js/leaderboard.js')
.js('resources/js/form.js', 'public/js/form.js')
.js('resources/js/loader.js', 'public/js/loader.js')
.js('resources/js/variables.js', 'public/js/variables.js')
.js('resources/js/topButton.js', 'public/js/topButton.js')
.sass('resources/css/landing.scss','public/scss/app.css')
.combine('resources/css/*.css','public/css/app.css');
