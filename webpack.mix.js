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

/*
mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);
*/
mix
.js('resources/js/quiz.js', 'public/js/quiz.js')
.js('resources/js/quizzes.js', 'public/js/quizzes.js')
.js('resources/js/leaderboard.js', 'public/js/leaderboard.js')
.js('resources/js/form.js', 'public/js/form.js')
.js('resources/js/loader.js', 'public/js/loader.js')
.combine('resources/css/*.css','public/css/app.css');
