<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Quizion">
        <meta name="description" content="Quizion, a multi platform kvízalkalmazás. Egy leginkább tanárokat és diákokat megcélzó kooperációs, valamint tanuló alkalmazás.">
        <meta name="author" content="Quizion">
        <link rel="icon" href="{{ url('images/quizion.ico') }}">
        <link rel="stylesheet" href="{{ url('css/app.css') }}">
        <title>Quizion @yield("title")</title>
    </head>
    <body>
        <div id="loader_div"><div id="loader"></div></div>
        <div class="header_logo">
            <div class="header_background">
                <img class="logo" src="{{ url('images/logo.png') }}" alt="Quizion" title="Quizion">
            </div>
        </div>

        <div class="container">
            @yield("content")
        </div>

        <div class="footer">
            <h4>© Copyright - quizion.hu - Minden jog fenntartva.</h4>
            <a href="#" target="jog">Jogi-, adatvédelmi nyilatkozat.</a>
        </div>
        <script src="{{ url('js/loader.js') }}"></script>
    </body>
</html>
