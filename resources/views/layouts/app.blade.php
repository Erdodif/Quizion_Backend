<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Quizion, Quiz, Quizzes">
        <meta name="description" content="Quizion, the multi platform quiz app.">
        <meta name="author" content="Quizion">
        <link rel="icon" href="{{ url('images/quizion.ico') }}">
        <link rel="stylesheet" href="{{ url('css/style.css') }}">
        <script src="{{ url('js/loader.js') }}"></script>
        <title>Quizion @yield("title")</title>
    </head>
    <body>
        <div class="wrapper">
            <div id="loader_div"><div id="loader"></div></div>
            <div class="header_logo">
                <div class="header_background">
                    <img class="logo" src="{{ url('images/logo.png') }}" alt="Quizion" title="Quizion">
                </div>
            </div>

            <div class="container">
                @yield("content")
            </div>
        </div>
        <div class="footer">
            <h4>© Copyright - quizion.hu - All rights reserved.</h4>
            <a href="#" target="copyright">Legal and data protection statement.</a>
        </div>
    </body>
</html>
